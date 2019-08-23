<?php

namespace Modules\Catalog\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Illuminate\Foundation\Testing\TestResponse;
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
        'thumbnail',
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
    public function get_templates(): void
    {
        $this->json('GET', $this->uri)->assertOk()->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function create_template(): void
    {
        $response = $this->sendPostRequest();

        $response
            ->assertStatus(201)
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function create_template_fails(): void
    {
        $this->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_template(): void
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
    public function get_template_fails(): void
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
    public function get_template_not_modified(): void
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
    public function update_template(): void
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
    public function update_template_fails(): void
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
    public function delete_template(): void
    {
        $this->persist();

        $this->json('DELETE', $this->uri.$this->template->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function delete_template_image(): void
    {
        $response = $this->sendPostRequest();

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
    public function get_template_gallery(): void
    {
        $response = $this->sendPostRequest();

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
    public function delete_template_fails(): void
    {
        $this->persist();

        $this->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('DELETE', $this->uri.$this->template->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_new_reference(): void
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

        $this->destroyImages();

        parent::tearDown();
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function sendPostRequest(): TestResponse
    {
        return $this->json('POST', $this->uri, [
            'reference' => $this->template->reference,
            'price'     => $this->template->price->price_float,
            'is_active' => $this->template->is_active,
            'images'    => factory(Image::class, 2)->make(),
        ]);
    }

    /**
     * @return $this
     */
    private function persist(): TemplateControllerTest
    {
        $this->template->save();

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update(): TestResponse
    {
        return $this->json('PUT', $this->uri.$this->template->id, [
            'price'     => $this->faker->randomFloat(2, 899.11, 1299.99),
            'is_active' => $this->faker->boolean(80),
            'images'    => factory(Image::class, 2)->make(),
        ]);
    }
}
