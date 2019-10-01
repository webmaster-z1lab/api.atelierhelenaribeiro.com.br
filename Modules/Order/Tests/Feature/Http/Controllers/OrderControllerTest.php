<?php

namespace Modules\Order\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Faker\Provider\pt_BR\PhoneNumber;
use Illuminate\Foundation\Testing\TestResponse;
use Modules\Catalog\Models\Template;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Order\Models\Order;
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

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $uri = '/orders/';

    /**
     * @var \Modules\Order\Models\Order
     */
    private $order;

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
        'annotations',
        'tracking_code',
        'freight',
        'total_price',
        'event_date',
        'ship_until',
        'customer',
        'customer_id',
        'products',
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
        $this->order = factory(Order::class)->make();
    }

    /** @test */
    public function get_orders(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('GET', $this->uri)->assertOk()->assertJsonStructure([$this->jsonStructure]);
    }

    /** @test */
    public function create_order(): void
    {
        $this->getProduct();

        $response = $this->actingAs($this->user)->json('POST', $this->uri, [
            'annotations' => $this->order->annotations,
            'event_date'  => $this->order->event_date->format('d/m/Y'),
            'ship_until'  => $this->order->ship_until->format('d/m/Y'),
            'customer'    => $this->order->customer_id,
            'products'    => [ $this->getProductArray() ],
        ]);

        $response
            ->assertStatus(201)
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function create_order_fails(): void
    {
        $this->actingAs($this->user)->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function get_order(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->order->id)
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function get_order_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->order->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function get_order_not_modified(): void
    {
        $this->persist();

        $response = $this->actingAs($this->user)->json('GET', $this->uri.$this->order->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $this->actingAs($this->user)
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->order->id)
            ->assertStatus(304);
    }

    /** @test */
    public function update_order(): void
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
    public function update_order_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('PATCH', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('PATCH', $this->uri.$this->order->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)
            ->json('PATCH', $this->uri.$this->order->id, [])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function delete_order(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->order->id)->assertStatus(204);
    }

    /** @test */
    public function delete_order_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->order->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function ship_order(): void
    {
        $this->persist();

        $response = $this->actingAs($this->user)->json('POST', $this->uri.$this->order->id, [
            'tracking_code' => $this->faker->ean8,
            'freight'       => $this->faker->randomFloat(2),
            'shipped_at'    => $this->faker->date('d/m/Y'),
        ]);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function ship_order_fail(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('POST', $this->uri.$this->order->id, [])->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('POST', $this->uri.$this->order->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Order::truncate();
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
     * @return \Modules\Order\Tests\Feature\Http\Controllers\OrderControllerTest
     */
    private function persist(): OrderControllerTest
    {
        $this->order->fill(['status' => Order::AWAITING_STATUS]);
        $this->order->unset(['tracking_code', 'freight', 'shipped_at']);

        $this->getProduct();

        $this->product->status = ProductStatus::AWAITING_STATUS;

        $this->product->save();

        $this->order->products()->save($this->product);

        $this->order->total_price = $this->product->price->price;

        $this->order->save();

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update(): TestResponse
    {
        $this->getProduct();

        $event_date = $this->faker->dateTimeBetween('today', '+6months');

        return $this->actingAs($this->user)->json('PUT', $this->uri.$this->order->id, [
            'annotations' => $this->faker->sentence,
            'event_date'  => $event_date->format('d/m/Y'),
            'ship_until'  => $this->faker->dateTimeBetween('today', $event_date)->format('d/m/Y'),
            'customer'    => $this->order->customer_id,
            'products'    => [ $this->getProductArray() ],
        ]);
    }

    private function getProduct(): void
    {
        do {
            $mannequin = rand(34, 62);
        } while ($mannequin % 2 !== 0);

        $this->product = factory(Product::class)->make([
            'mannequin'        => $mannequin,
            'removable_sleeve' => $this->faker->boolean,
            'has_lace'         => $this->faker->boolean,
            'bust'             => rand(70, 180),
            'armhole'          => rand(10, 50),
            'hip'              => rand(70, 130),
            'waist'            => rand(70, 130),
            'slit'             => rand(0, 30),
            'body'             => rand(30, 80),
            'skirt'            => rand(60, 120),
            'tail'             => rand(0, 40),
            'shoulders'        => rand(40, 80),
            'cleavage'         => rand(10, 20),
            'skirt_type'       => $this->faker->word,
            'sleeve_model'     => $this->faker->word,
            'annotations'      => $this->faker->sentence,
        ]);
    }

    private function getProductArray(): array
    {
        return [
            'template'         => $this->product->template_id,
            'color'            => $this->product->color,
            'mannequin'        => $this->product->mannequin,
            'removable_sleeve' => $this->product->removable_sleeve,
            'has_lace'         => $this->product->has_lace,
            'bust'             => $this->product->bust,
            'armhole'          => $this->product->armhole,
            'hip'              => $this->product->hip,
            'waist'            => $this->product->waist,
            'slit'             => $this->product->slit,
            'body'             => $this->product->body,
            'skirt'            => $this->product->skirt,
            'tail'             => $this->product->tail,
            'shoulders'        => $this->product->shoulders,
            'cleavage'         => $this->product->cleavage,
            'skirt_type'       => $this->product->skirt_type,
            'sleeve_model'     => $this->product->sleeve_model,
            'annotations'      => $this->product->annotations,
        ];
    }
}
