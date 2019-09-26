<?php

namespace Modules\Sales\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Faker\Provider\pt_BR\PhoneNumber;
use Illuminate\Foundation\Testing\TestResponse;
use Modules\Catalog\Models\Template;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Jobs\AddProductsToPacking;
use Modules\Sales\Jobs\RemoveProductsFromPacking;
use Modules\Sales\Jobs\UpdateProductsStatus;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Refund;
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

class RefundControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @return string
     */
    private function uri(): string
    {
        return "visits/{$this->sale->visit_id}/refunds/";
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
        'refund'         => [
            'amount',
            'price',
        ],
        'sales',
        'refunds'        => [
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
    public function create_refund(): void
    {
        \Queue::fake();

        \Queue::assertNothingPushed();

        $this->persistSale();

        $response = $this->actingAs($this->user)->json('POST', $this->uri(), [
            'products' => [
                [
                    'reference' => $this->sale->reference,
                    'amount'    => 1,
                ],
            ],
        ]);

        $visit_id = $this->sale->visit_id;

        \Queue::assertPushed(AddProductsToPacking::class, function (AddProductsToPacking $job) use ($visit_id) {
            $job->handle();

            return $job->visit->id === $visit_id;
        });

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function create_refund_fails(): void
    {
        \Queue::fake();

        $this->persistSale();

        $this->actingAs($this->user)->json('POST', $this->uri(), [])->assertStatus(422)->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();
    }

    /** @test */
    public function get_refund(): void
    {
        $this->persistRefund();

        $this->actingAs($this->user)
            ->json('GET', $this->uri())
            ->assertOk()
            ->assertJsonStructure($this->jsonStructure['refunds']);
    }

    /** @test */
    public function get_refund_fails(): void
    {
        $this->persistRefund();

        $this->actingAs($this->user)
            ->json('GET', $this->uri().$this->sale->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function update_refund(): void
    {
        $this->persistRefund();

        \Queue::fake();

        \Queue::assertNothingPushed();

        $response = $this->update();

        $visit_id = $this->sale->visit_id;

        \Queue::assertPushed(AddProductsToPacking::class, function (AddProductsToPacking $job) use ($visit_id) {
            $job->handle();

            return $job->visit->id === $visit_id;
        });

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function update_refund_fails(): void
    {
        $this->persistRefund();

        $this->actingAs($this->user)->json('PATCH', $this->uri().$this->sale->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)
            ->json('PATCH', $this->uri(), [])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function delete_refund(): void
    {
        $this->persistRefund();

        \Queue::fake();

        \Queue::assertNothingPushed();
        $this->actingAs($this->user)->json('DELETE', $this->uri())->assertStatus(204);

        $packing_id = $this->sale->visit->packing_id;

        \Queue::assertPushed(RemoveProductsFromPacking::class, function (RemoveProductsFromPacking $job) use ($packing_id) {
            $job->handle();

            return $job->packing->id === $packing_id;
        });
    }

    /** @test */
    public function delete_refund_fails(): void
    {
        $this->persistRefund();

        \Queue::fake();

        $this->actingAs($this->user)->json('DELETE', $this->uri().$this->sale->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        \Queue::assertNothingPushed();
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Refund::truncate();
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
     * @return \Modules\Sales\Tests\Feature\Http\Controllers\RefundControllerTest
     */
    private function persistSale(): RefundControllerTest
    {
        $this->sale->save();

        UpdateProductsStatus::dispatchNow($this->sale->visit->packing, [$this->sale->product_id], ProductStatus::SOLD_STATUS);

        return $this;
    }

    private function persistRefund(): RefundControllerTest
    {
        $this->persistSale();

        $visit = $this->sale->visit;

        $refund = new Refund([
            'date' => $visit->date,
            'reference' => $this->sale->reference,
            'thumbnail' => $this->sale->thumbnail ,
            'size' => $this->sale->size,
            'color' => $this->sale->color,
            'price' => $this->sale->price,
        ]);

        $refund->visit()->associate($visit);
        $refund->seller()->associate($visit->seller_id);
        $refund->customer()->associate($visit->customer_id);
        $refund->product()->associate($this->sale->product_id);

        $refund->save();

        UpdateProductsStatus::dispatchNow($visit->packing, [$this->sale->product_id], ProductStatus::RETURNED_STATUS, FALSE);

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
