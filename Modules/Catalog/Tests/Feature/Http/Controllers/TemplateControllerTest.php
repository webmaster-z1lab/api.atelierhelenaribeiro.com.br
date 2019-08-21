<?php

namespace Modules\Catalog\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Catalog\Models\Template;
use Tests\ImageFiles;
use Tests\RefreshDatabase;
use Tests\TestCase;

class TemplateControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, ImageFiles;

    private $uri = '/templates/';
    /**
     * @var \Modules\Catalog\Models\Template
     */
    private $template;

    private $jsonStructure = [
        'id',
        'reference',
        'price',
        'created_at',
        'updated_at',
        'images',
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

        $this->template = factory(Template::class)->make();
    }

    /**
     * @test
     */
    public function get_templates()
    {
        $this->json('GET', $this->uri)->assertOk()->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function create_template()
    {
        $response = $this->json('POST', $this->uri, [
            'reference' => $this->template->reference,
            'price'     => $this->template->price->price_float,
            'is_active' => $this->template->is_active,
            'images'    => $this->getImages(),
        ]);

        $response->dump()
            ->assertStatus(201)
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function create_template_fails()
    {
        $this->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_template()
    {
        $this->persist();

        $this
            ->json('GET', $this->uri.$this->template->id)
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function get_template_fails()
    {
        $this->persist();

        $this
            ->json('GET', $this->uri.$this->template->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_template_not_modified()
    {
        $this->persist();

        $response = $this->json('GET', $this->uri.$this->template->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $this
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->template->id)
            ->assertStatus(304);
    }

    /**
     * @test
     */
    public function update_template()
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
    public function update_template_fails()
    {
        $this->persist();

        $this->json('PATCH', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('PATCH', $this->uri.$this->template->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this
            ->json('PATCH', $this->uri.$this->template->id, [
                'price' => '0',
            ])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function delete_template()
    {
        $this->persist();

        $this->json('DELETE', $this->uri.$this->template->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function delete_template_image()
    {
        $response = $this->json('POST', $this->uri, [
            'reference' => $this->template->reference,
            'price'     => $this->template->price->price_float,
            'is_active' => $this->template->is_active,
            'images'    => $this->getImages(),
        ]);

        $response
            ->assertStatus(201)
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $template = json_decode($response->getContent());

        $this->json('DELETE', 'images/'.$template->images[0]->id.$this->uri.$template->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function get_template_gallery()
    {
        $response = $this->json('POST', $this->uri, [
            'reference' => $this->template->reference,
            'price'     => $this->template->price->price_float,
            'is_active' => $this->template->is_active,
            'images'    => $this->getImages(),
        ]);

        $response
            ->assertStatus(201)
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $template = json_decode($response->getContent());

        $this->json('GET', $this->uri.$template->id.'/gallery')
            ->assertOk()
            ->assertJsonStructure([
                [
                    'id',
                    'template_id',
                    'product_id',
                    'name',
                    'extension',
                    'path',
                    'icon',
                    'size',
                    'size_in_bytes',
                ],
            ]);
    }

    /**
     * @test
     */
    public function delete_template_fails()
    {
        $this->persist();

        $this->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('DELETE', $this->uri.$this->template->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_new_reference()
    {
        $this->json('GET', $this->uri.'reference')->assertOk()->assertJsonStructure(['reference']);
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Template::truncate();
        Image::truncate();

        parent::tearDown();
    }

    /**
     * @return $this
     */
    private function persist()
    {
        $this->template->save();

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update()
    {
        return $this->json('PUT', $this->uri.$this->template->id, [
            'price'     => $this->faker->randomFloat(2, 899.11, 1299.99),
            'is_active' => $this->faker->boolean(80),
            'images'    => $this->getImages(),
        ]);
    }
}
