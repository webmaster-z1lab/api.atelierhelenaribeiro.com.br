<?php

namespace Modules\Stock\Tests\Feature\Http\Controllers;

use Modules\Stock\Models\Product;
use Tests\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $uri = 'products/';
    /**
     * @var \Modules\Stock\Models\Product
     */
    private $product;

    private $jsonStructure = [
        'id',
        'barcode',
        'size',
        'color'    => ['name', 'value'],
        'template' => [
            'id',
            'reference',
            'price',
            'created_at',
            'updated_at',
            'images',
        ],
        'price'    => [
            'id',
            'price',
            'started_at',
        ],
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

        $this->json('GET', $this->uri)->assertOk()->assertJsonStructure([$this->jsonStructure]);
    }

    /**
     * @test
     */
    public function create_product()
    {
        $response = $this->json('POST', $this->uri, [
            'size'     => $this->product->size,
            'color'    => $this->product->color->name,
            'template' => $this->product->template_id,
            'price'    => $this->product->price->price,
        ]);

        $response
            ->assertStatus(201)
            ->assertHeader('ETag')
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
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
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
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
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
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
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
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
    public function delete_product_fails()
    {
        $this->persist();

        $this->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('DELETE', $this->uri.$this->product->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    public function tearDown(): void
    {
        Product::truncate();

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
            'price' => $this->faker->numberBetween(899, 1299),
            'size' => $this->product->size,
            'color' => $this->product->color->name,
            'template' => $this->product->template_id
        ]);
    }
}
