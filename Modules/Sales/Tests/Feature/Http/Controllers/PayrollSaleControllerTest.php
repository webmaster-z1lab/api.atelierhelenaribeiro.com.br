<?php

namespace Modules\Sales\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Faker\Provider\pt_BR\PhoneNumber;
use Illuminate\Foundation\Testing\TestResponse;
use Modules\Catalog\Models\Template;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Jobs\UpdateProductsStatus;
use Modules\Sales\Models\Information;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Payroll;
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

class PayrollSaleControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @return string
     */
    private function uri(): string
    {
        return "visits/{$this->payroll->visit_id}/payrolls/sales/";
    }

    /**
     * @var \Modules\Sales\Models\Payroll
     */
    private $payroll;

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
        'seller'        => [
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
        'customer'      => [
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
        'sale'          => [
            'amount',
            'price',
        ],
        'payroll'       => [
            'amount',
            'price',
        ],
        'payrolls',
        'payroll_sale'  => [
            'amount',
            'price',
        ],
        'payroll_sales' => [
            [
                'reference',
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
        $this->payroll = factory(Payroll::class)->make();
    }

    /** @test */
    public function create_payroll_sale(): void
    {
        \Queue::fake();

        \Queue::assertNothingPushed();

        $this->payroll->save();

        $response = $this->actingAs($this->user)->json('POST', $this->uri(), [
            'products' => [
                [
                    'reference' => $this->payroll->reference,
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
    public function create_payroll_sale_fails(): void
    {
        \Queue::fake();

        $this->payroll->save();

        $this->actingAs($this->user)->json('POST', $this->uri(), [])->assertStatus(422)->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();
    }

    /** @test */
    public function get_payroll_sale(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri())
            ->assertOk()
            ->assertJsonStructure($this->jsonStructure['payroll_sales']);
    }

    /** @test */
    public function get_payroll_sale_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri().$this->payroll->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
//    public function get_payroll_not_modified(): void
//    {
//        $this->persist();
//
//        $response = $this->actingAs($this->user)->json('GET', $this->uri.$this->payroll->id);
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
//            ->json('GET', $this->uri.$this->payroll->id)
//            ->assertStatus(304);
//    }

    /** @test */
    public function update_payroll_sale(): void
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
    public function update_payroll_sale_fails(): void
    {
        $this->persist();

//        $this->actingAs($this->user)->json('PATCH', $this->uri())->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('PATCH', $this->uri().$this->payroll->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)
            ->json('PATCH', $this->uri(), [])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function delete_payroll_sale(): void
    {
        $this->persist();

        \Queue::fake();

        $this->actingAs($this->user)->json('DELETE', $this->uri())->assertStatus(204);

        \Queue::assertPushed(UpdateProductsStatus::class, function (UpdateProductsStatus $job) {
            $job->handle();

            return $job->status === ProductStatus::ON_CONSIGNMENT_STATUS;
        });
    }

    /** @test */
    public function delete_payroll_sale_fails(): void
    {
        $this->persist();

        \Queue::fake();

//        $this->actingAs($this->user)->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);
//
//        \Queue::assertNothingPushed();

        $this->actingAs($this->user)->json('DELETE', $this->uri().$this->payroll->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Payroll::truncate();
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
     * @return \Modules\Sales\Tests\Feature\Http\Controllers\PayrollSaleControllerTest
     */
    private function persist(): PayrollSaleControllerTest
    {
        $this->payroll->completion_visit()->associate($this->payroll->visit_id);
        $this->payroll->fill([
            'status' => ProductStatus::SOLD_STATUS,
            'completion_date' => $this->payroll->visit->date,
        ]);

        $this->payroll->save();
        $visit = $this->payroll->visit;

        $visit->payroll_sale()->associate(new Information([
            'amount' => 1,
            'price'  => $this->payroll->price,
        ]));

        $visit->save();

        UpdateProductsStatus::dispatchNow($visit->packing, [$this->payroll->product_id], ProductStatus::SOLD_STATUS, FALSE);

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update(): TestResponse
    {
        $product = $this->payroll->visit->packing->products()->last();

        $payroll = new Payroll([
            'date' => $this->payroll->visit->date,
            'reference' => $product->reference,
            'thumbnail' => $product->thumbnail,
            'size' => $product->size,
            'color' => $product->color,
            'price'  => $product->price
        ]);

        $payroll->product()->associate($product->product_id);
        $payroll->visit()->associate($this->payroll->visit_id);
        $payroll->customer()->associate($this->payroll->customer_id);
        $payroll->seller()->associate($this->payroll->seller_id);

        $payroll->save();

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
