<?php

namespace Modules\Stock\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Catalog\Models\Template;
use Modules\Stock\Models\Product;
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
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->product = factory(Product::class)->make();
    }

    /**
     * @test
     */
    public function get_products()
    {
        $this->persist();

        $this->json('GET', $this->uri)->assertOk()->assertJsonStructure([
            [
                'template' => [
                    'id',
                    'reference',
                    'prices' => [
                        [
                            'id',
                            'price',
                            'started_at',
                        ],
                    ],
                    'price',
                    'created_at',
                    'updated_at',
                    'images',
                ],
                'amount',
            ],
        ]);

        $query = http_build_query(['template' => $this->product->template_id]);

        $this->json('GET', "{$this->uri}?$query")->assertOk()->assertJsonStructure([
            [
                'template' => [
                    'id',
                    'reference',
                    'prices' => [
                        [
                            'id',
                            'price',
                            'started_at',
                        ],
                    ],
                    'price',
                    'created_at',
                    'updated_at',
                    'images',
                ],
                'size',
                'color',
                'amount',
                'products' => [
                    [
                        'id',
                        'barcode',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function create_product()
    {
        $amount = $this->faker->numberBetween(1, 5);

        $response = $this->json('POST', $this->uri, [
            'amount'   => $amount,
            'size'     => $this->product->size,
            'color'    => $this->product->color,
            'template' => $this->product->template_id,
            'price'    => $this->product->price->price,
            'images'   => $this->getImages(),
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
    public function create_product_fails()
    {
        $this->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_product()
    {
        $this->persist();

        $this
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
    public function get_product_fails()
    {
        $this->persist();

        $this
            ->json('GET', $this->uri.$this->product->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_product_not_modified()
    {
        $this->persist();

        $response = $this->json('GET', $this->uri.$this->product->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $this
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->product->id)
            ->assertStatus(304);
    }

    /**
     * @test
     */
    public function update_product()
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
    public function update_product_fails()
    {
        $this->persist();

        $this->json('PUT', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('PUT', $this->uri.$this->product->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this
            ->json('PUT', $this->uri.$this->product->id, [
                'price' => '0',
            ])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function delete_product()
    {
        $this->persist();

        $this->json('DELETE', $this->uri.$this->product->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function delete_template_image()
    {
        $product = $this->json('POST', $this->uri, [
            'size'     => $this->product->size,
            'color'    => $this->product->color,
            'template' => $this->product->template_id,
            'price'    => $this->product->price->price,
            'amount' => 1,
            'images'   => $this->getImages(),
        ])->assertStatus(200)
            //->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure([$this->jsonStructure]);

        $product = json_decode($product->getContent())[0];

        $this->json('DELETE', '/images/'.$product->images[0]->id.$this->uri.$product->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function delete_product_fails()
    {
        $this->persist();

        $this->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('DELETE', $this->uri.$this->product->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Product::truncate();
        Template::truncate();
        Image::truncate();

        parent::tearDown();
    }

    /**
     * @return $this
     */
    private function persist()
    {
        $this->product->save();

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update()
    {
        return $this->json('PUT', $this->uri.$this->product->id, [
            'price'  => $this->faker->numberBetween(899, 1299),
            'size'   => $this->product->size,
            'color'  => $this->product->color,
            'images' => $this->getImages(),
        ]);
    }
}
