<?php

namespace Modules\Sales\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Faker\Provider\pt_BR\PhoneNumber;
use Illuminate\Foundation\Testing\TestResponse;
use Modules\Catalog\Models\Template;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Jobs\CheckOutProducts;
use Modules\Sales\Models\Packing;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\Size;
use Modules\User\Models\User;
use Tests\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class PackingControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @var string
     */
    private $uri = '/packings/';

    /**
     * @var \Modules\Sales\Models\Packing
     */
    private $packing;

    /**
     * @var \Modules\Stock\Models\Product
     */
    private $product;

    /**
     * @var \Modules\User\Models\User
     */
    private $user;

    /**
     * @var array
     */
    private $jsonStructure = [
        'id',
        'status',
        'seller_id',
        'seller' => [
            'id',
            'name',
            'document',
            'identity',
            'work_card',
            'email',
            'type',
            'remuneration',
            'birth_date',
            'admission_date',
            'created_at',
            'updated_at',
            'address',
            'phone',
        ],
        'products' => [
            [
                'id',
                'product_id',
                'reference',
                'thumbnail',
                'size',
                'color',
                'price',
                'status',
            ],
        ],
        'created_at',
        'updated_at',

    ];

    /**
     * @var array
     */
    private $errorStructure = [
        'id',
        'status',
        'title',
        'message',
        'links',
        'meta',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->faker->addProvider(new PhoneNumber($this->faker));
        $this->user = factory(User::class)->state('fake')->create(['type' => EmployeeTypes::TYPE_ADMIN]);
        $this->product = factory(Product::class)->create();
        $this->packing = factory(Packing::class)->make();
    }

    /** @test */
    public function get_packings(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('GET', $this->uri)->assertOk()->assertJsonStructure([$this->jsonStructure]);
    }

    /** @test */
    public function create_packing(): void
    {
        $products = [];
        foreach ($this->packing->products()->distinct()->get(['reference']) as $reference) {
            $products[] = [
                'reference' => $reference->reference,
                'amount' => $this->packing->products()->where('reference', $reference->reference)->count(),
            ];
        }

        $response = $this->actingAs($this->user)->json('POST', $this->uri, [
            'seller' => $this->packing->seller_id,
            'products' => $products
        ]);

        $response
            ->assertStatus(201)
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function create_packing_fails(): void
    {
        $this->actingAs($this->user)->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function get_packing(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->packing->id)
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function get_packing_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->packing->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function get_packing_not_modified(): void
    {
        $this->persist();

        $response = $this->actingAs($this->user)->json('GET', $this->uri.$this->packing->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $this->actingAs($this->user)
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->packing->id)
            ->assertStatus(304);
    }

    /** @test */
    public function update_packing(): void
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

    /**  @test */
    public function update_packing_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('PATCH', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('PATCH', $this->uri.$this->packing->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)
            ->json('PATCH', $this->uri.$this->packing->id, [])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function delete_template(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->packing->id)->assertStatus(204);
    }

    /** @test */
    public function delete_template_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->packing->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function check_out_packing(): void
    {
        \Queue::fake();

        $this->persist();

        $packing = $this->packing;

        $checked = [];
        foreach ($packing->products()->distinct()->get(['reference']) as $reference) {
            $checked[] = [
                'reference' => $reference->reference,
                'amount' => $packing->products()->where('reference', $reference->reference)->count(),
            ];
        }

        \Queue::assertNothingPushed();

        $response = $this->actingAs($this->user)->json('POST', $this->uri.$packing->id, compact('checked'));

        \Queue::assertPushed(CheckOutProducts::class, function (CheckOutProducts $job) use ($packing) {
            return $job->packing->id === $packing->id;
        });

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**  @test */
    public function check_out_packing_fails(): void
    {
        \Queue::fake();

        $this->persist();

        $this->actingAs($this->user)->json('POST', $this->uri.$this->packing->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();

        $this->actingAs($this->user)
            ->json('PATCH', $this->uri.$this->packing->id, [])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        User::truncate();
        Product::truncate();
        Color::truncate();
        Size::truncate();
        Template::truncate();
        Image::truncate();
        Packing::truncate();

        parent::tearDown();
    }

    /**
     * @return \Modules\Sales\Tests\Feature\Http\Controllers\PackingControllerTest
     */
    private function persist(): PackingControllerTest
    {
        $this->packing->save();

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update(): TestResponse
    {
        factory(User::class)->state('fake')->create(['type' => EmployeeTypes::TYPE_SELLER]);

        $products = [];
        foreach ($this->packing->products()->distinct()->get(['reference']) as $reference) {
            $products[] = [
                'reference' => $reference->reference,
                'amount' => $this->packing->products()->where('reference', $reference->reference)->count(),
            ];
        }

        return $this->actingAs($this->user)->json('PUT', $this->uri.$this->packing->id, [
            'seller' => $this->packing->seller_id,
            'products' => $products
        ]);
    }
}
