<?php

namespace Modules\Catalog\Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Catalog\Models\Template;
use Tests\RefreshDatabase;
use Tests\TestCase;

class TemplateControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
            'price'     => $this->template->price->price,
            'is_active' => $this->template->is_active,
            'images'    => $this->getImages(),
        ]);

        $response->dump();

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
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
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
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
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
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
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
            'price'     => $this->faker->numberBetween(899, 1299),
            'is_active' => $this->faker->boolean(80),
            'images'    => $this->getImages(),
        ]);
    }

    /**
     * @return array
     */
    private function getImages(): array
    {
        Storage::fake('public');

        $images = [];
        $total = $this->faker->numberBetween(1, 5);

        for ($i = 0; $i < $total; $i++) {
            $images[] = UploadedFile::fake()->image($this->faker->name.'.jpg', 1200, 720);
        }

        return $images;
    }
}
