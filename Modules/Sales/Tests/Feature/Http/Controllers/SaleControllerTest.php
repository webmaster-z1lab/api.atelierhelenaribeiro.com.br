<?php

namespace Modules\Sales\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Faker\Provider\pt_BR\PhoneNumber;
use Illuminate\Foundation\Testing\TestResponse;
use Modules\Catalog\Models\Template;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Jobs\UpdateProductsStatus;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Sale;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\ProductStatus;
use Modules\Stock\Models\Size;
use Modules\User\Models\User;
use Tests\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class SaleControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @var string
     */
    private $uri = '/sales/';

    /**
     * @var \Modules\Sales\Models\Sale
     */
    private $sale;

    /**
     * @var \Modules\User\Models\User
     */
    private $user;

    /**
     * @var array
     */
    private $jsonStructure = [
        'id',
        'date',
        'visit_id',
        'total_amount',
        'total_price',
        'discount',
        'seller_id',
        'seller'   => [
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
            'address' => [
                'id',
                'street',
                'number',
                'complement',
                'district',
                'postal_code',
                'city',
                'state',
                'formatted',
            ],
            'phone'   => [
                'id',
                'area_code',
                'phone',
                'international',
                'number',
                'is_whatsapp',
                'formatted',
            ],
        ],
        'customer_id',
        'customer' => [
            'id',
            'company_name',
            'trading_name',
            'document',
            'state_registration',
            'municipal_registration',
            'email',
            'annotation',
            'status',
            'contact',
            'seller',
            'address' => [
                'id',
                'street',
                'number',
                'complement',
                'district',
                'postal_code',
                'city',
                'state',
                'formatted',
            ],
            'phones'  => [
                [
                    'id',
                    'area_code',
                    'phone',
                    'international',
                    'number',
                    'is_whatsapp',
                    'formatted',
                ],
            ],
            'owners'  => [
                [
                    'id',
                    'name',
                    'document',
                    'email',
                    'birth_date',
                    'phone' => [
                        'id',
                        'area_code',
                        'phone',
                        'international',
                        'number',
                        'is_whatsapp',
                        'formatted',
                    ],
                ],
            ],
        ],
        'products' => [
            [
                'thumbnail',
                'size',
                'color',
                'price',
                'amount',
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
        $this->sale = factory(Sale::class)->make();
    }

    /** @test */
    public function get_sales(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('GET', $this->uri)->assertOk()->assertJsonStructure([$this->jsonStructure]);
    }

    /** @test */
    public function create_sale(): void
    {
        \Queue::fake();

        $products = [];
        foreach ($this->sale->products()->pluck('reference')->unique()->all() as $reference) {
            $products[] = [
                'reference' => $reference,
                'amount'    => $this->sale->products()->where('reference', $reference)->count(),
            ];
        }

        \Queue::assertNothingPushed();

        $response = $this->actingAs($this->user)->json('POST', $this->uri, [
            'visit'    => $this->sale->visit_id,
            'discount' => $this->sale->discount_float,
            'products' => $products,
        ]);

        \Queue::assertPushed(UpdateProductsStatus::class, function (UpdateProductsStatus $job) {
            $job->handle();

            return $job->status === ProductStatus::SOLD_STATUS;
        });

        $response
            ->assertStatus(201)
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function create_sale_fails(): void
    {
        \Queue::fake();

        $this->actingAs($this->user)->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();
    }

    /** @test */
    public function get_sale(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->sale->id)
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function get_sale_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->sale->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function get_sale_not_modified(): void
    {
        $this->persist();

        $response = $this->actingAs($this->user)->json('GET', $this->uri.$this->sale->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $this->actingAs($this->user)
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->sale->id)
            ->assertStatus(304);
    }

    /** @test */
    public function update_sale(): void
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

    /** @test */
    public function update_visit_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('PATCH', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('PATCH', $this->uri.$this->sale->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)
            ->json('PATCH', $this->uri.$this->sale->id, [])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function delete_sale(): void
    {
        $this->persist();

        \Queue::fake();

        \Queue::assertNothingPushed();
        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->sale->id)->assertStatus(204);

        \Queue::assertPushed(UpdateProductsStatus::class, function (UpdateProductsStatus $job) {
            $job->handle();

            return $job->status === ProductStatus::IN_TRANSIT_STATUS;
        });
    }

    /** @test */
    public function delete_sale_fails(): void
    {
        $this->persist();

        \Queue::fake();

        $this->actingAs($this->user)->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();

        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->sale->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Sale::truncate();
        Visit::truncate();
        Customer::truncate();
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
     * @return \Modules\Sales\Tests\Feature\Http\Controllers\SaleControllerTest
     */
    private function persist(): SaleControllerTest
    {
        $this->sale->save();

        $packing = Packing::where('seller_id', $this->sale->seller_id)->where(function ($query) {
            $query->where('checked_out_at', 'exists', FALSE)->orWhereNull('checked_out_at');
        })->first();

        UpdateProductsStatus::dispatchNow($packing, $this->sale->products->pluck('product_id')->all(), ProductStatus::SOLD_STATUS);

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update(): TestResponse
    {
        $products = [];
        foreach ($this->sale->products()->pluck('reference')->unique()->all() as $reference) {
            $products[] = [
                'reference' => $reference,
                'amount'    => $this->sale->products()->where('reference', $reference)->count(),
            ];
        }

        return $this->actingAs($this->user)->json('PUT', $this->uri.$this->sale->id, [
            'discount' => $this->faker->randomFloat(2, 0, $this->sale->total_price_float),
            'products' => $products,
        ]);
    }
}
