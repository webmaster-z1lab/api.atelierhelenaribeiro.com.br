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
     * @return string
     */
    private function uri(): string
    {
        return "visits/{$this->sale->visit_id}/sales/";
    }

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
        'status',
        'date',
        'annotations',
        'seller_id',
        'seller'       => [
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
        'customer'     => [
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
            'created_at',
            'updated_at',
        ],
        'customer_credit',
        'total_amount',
        'discount',
        'total_price',
        'sale'         => [
            'amount',
            'price',
        ],
        'sales'        => [
            [
                'reference',
                'thumbnail',
                'size',
                'color',
                'price',
                'amount',
            ],
        ],
        'payroll'      => [
            'amount',
            'price',
        ],
        'payroll_sale' => [
            'amount',
            'price',
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

        $this->actingAs($this->user)->json('GET', $this->uri())->assertOk()->assertJsonStructure($this->jsonStructure['sales']);
    }

    /** @test */
    public function create_sale(): void
    {
        \Queue::fake();

        \Queue::assertNothingPushed();

        $response = $this->actingAs($this->user)->json('POST', $this->uri(), [
            'products' => [
                [
                    'reference' => $this->sale->reference,
                    'amount'    => 1,
                ],
            ],
        ]);

        \Queue::assertPushed(UpdateProductsStatus::class, function (UpdateProductsStatus $job) {
            $job->handle();

            return $job->status === ProductStatus::SOLD_STATUS;
        });

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function create_sale_fails(): void
    {
        \Queue::fake();

        $this->actingAs($this->user)->json('POST', $this->uri(), [])->assertStatus(422)->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();
    }

    /** @test */
    public function get_sale(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri())
            ->assertOk()
            ->assertJsonStructure($this->jsonStructure['sales']);
    }

    /** @test */
    public function get_sale_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri().$this->sale->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
//    public function get_sale_not_modified(): void
//    {
//        $this->persist();
//
//        $response = $this->actingAs($this->user)->json('GET', $this->uri().$this->sale->id);
//
//        $response
//            ->assertOk()
//            ->assertHeader('ETag')
//            //->assertHeader('Content-Length')
//            //->assertHeader('Cache-Control')
//            ->assertJsonStructure($this->jsonStructure);
//
//        $this->actingAs($this->user)
//            ->withHeaders(['If-None-Match' => $response->getEtag()])
//            ->json('GET', $this->uri.$this->sale->id)
//            ->assertStatus(304);
//    }

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
    public function update_sale_fails(): void
    {
        $this->persist();

        //$this->actingAs($this->user)->json('PATCH', $this->uri())->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('PATCH', $this->uri().$this->sale->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)
            ->json('PATCH', $this->uri(), [])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function delete_sale(): void
    {
        $this->persist();

        \Queue::fake();

        \Queue::assertNothingPushed();
        $this->actingAs($this->user)->json('DELETE', $this->uri())->assertStatus(204);

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

//        $this->actingAs($this->user)->json('DELETE', $this->uri())->assertStatus(405)->assertJsonStructure($this->errorStructure);
//
//        \Queue::assertNothingPushed();

        $this->actingAs($this->user)->json('DELETE', $this->uri().$this->sale->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

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

        $visit = $this->sale->visit;

        $visit->sale->fill([
            'amount' => 1,
            'price'  => $this->sale->price,
        ]);

        $visit->sale->save();

        $visit->fill([
            'total_price' => $this->sale->price,
            'amount'      => 1,
        ]);

        $visit->save();

        UpdateProductsStatus::dispatchNow($packing, [$this->sale->product_id], ProductStatus::SOLD_STATUS);

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update(): TestResponse
    {
        $product = $this->sale->visit->packing->products()->last();

        return $this->actingAs($this->user)->json('PUT', $this->uri(), [
            'products' => [
                [
                    'reference' => $product->reference,
                    'amount'    => 1,
                ],
            ],
        ]);
    }
}
