<?php

namespace Modules\Stock\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Catalog\Models\Template;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Stock\Models\Product;
use Modules\User\Models\User;
use Tests\ImageFiles;
use Tests\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, ImageFiles;

    private $uri = '/products/';
    /**
     * @var \Modules\Stock\Models\Product
     */
    private $product;

    private $jsonStructure = [
        'id',
        'barcode',
        'size',
        'color',
        'template_id',
        'thumbnail',
        'template' => [
            'id',
            'reference',
            'price',
            'created_at',
            'updated_at',
            'images',
        ],
        'price',
        'images',
        'prices'   => [
            [
                'id',
                'price',
                'started_at',
            ],
        ],
        'created_at',
        'updated_at',
    ];

    private $errorStructure = [
        'id',
        'status',
        'title',
        'message',
        'links',
        'meta',
    ];

    /**
     * @var \Modules\User\Models\User
     */
    private $user;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->product = factory(Product::class)->make();
        $this->user = factory(User::class)->state('fake')->create(['type' => EmployeeTypes::TYPE_ADMIN]);
    }

    /**
     * @test
     */
    public function get_products(): void
    {
        $this->persist();

        $this->json('GET', $this->uri)->assertOk()->assertJsonStructure([
            [
                'template',
                'size',
                'color',
                'amount',
                'products' => [
                    [
                        'id',
                        'barcode',
                        'thumbnail',
                        'size',
                        'color',
                        'template_id',
                        'price',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function create_product(): void
    {
        $amount = $this->faker->numberBetween(1, 3);

        $response = $this->actingAs($this->user)->json('POST', $this->uri, [
            'amount'          => $amount,
            'size'            => $this->product->size,
            'color'           => $this->product->color,
            'template'        => $this->product->template_id,
            'price'           => $this->product->price->price_float,
            'images'          => factory(Image::class, 2)->make(),
            'template_images' => [
                $this->product->template->images->first()->id,
            ],
        ]);

        $response
            ->assertStatus(200)
            //->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure([$this->jsonStructure])
            ->assertJsonCount($amount);
    }

    /**
     * @test
     */
    public function create_product_fails(): void
    {
        $this->actingAs($this->user)->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_product(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->product->id)
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function get_product_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->product->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_product_not_modified(): void
    {
        $this->persist();

        $response = $this->actingAs($this->user)->json('GET', $this->uri.$this->product->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $this->actingAs($this->user)
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->product->id)
            ->assertStatus(304);
    }

    /**
     * @test
     */
    public function update_product(): void
    {
        $this->persist();

        $response = $this->update();

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function update_product_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('PUT', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('PUT', $this->uri.$this->product->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)
            ->json('PUT', $this->uri.$this->product->id, [
                'price' => '0',
            ])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function delete_product(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->product->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function delete_product_image(): void
    {
        $product = $this->actingAs($this->user)
            ->json('POST', $this->uri, [
                'size'     => $this->product->size,
                'color'    => $this->product->color,
                'template' => $this->product->template_id,
                'price'    => $this->product->price->price_float,
                'amount'   => 1,
                'images'   => factory(Image::class, 2)->make(),
            ])
            ->assertStatus(200)
            ->assertJsonStructure([$this->jsonStructure]);

        $product = json_decode($product->getContent())[0];

        $this->actingAs($this->user)->json('DELETE', '/images/'.$product->images[0]->id.$this->uri.$product->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function delete_product_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->product->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Product::truncate();
        Template::truncate();
        Image::truncate();

        $this->destroyImages();

        parent::tearDown();
    }

    /**
     * @return $this
     */
    private function persist(): ProductControllerTest
    {
        $this->product->save();

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update(): TestResponse
    {
        return $this->actingAs($this->user)->json('PUT', $this->uri.$this->product->id, [
            'price'  => $this->faker->randomFloat(2, 899.11, 1299.99),
            'size'   => $this->product->size,
            'color'  => $this->product->color,
            'images' => factory(Image::class, 2)->make(),
        ]);
    }
}
