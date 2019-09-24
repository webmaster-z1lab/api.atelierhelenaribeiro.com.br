<?php

namespace Modules\Sales\Tests\Feature\Http\Controllers;

use App\Models\Image;
use Faker\Provider\pt_BR\PhoneNumber;
use Illuminate\Foundation\Testing\TestResponse;
use Modules\Catalog\Models\Template;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\Size;
use Modules\User\Models\User;
use Tests\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class VisitControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @var string
     */
    private $uri = '/visits/';

    /**
     * @var \Modules\Sales\Models\Visit
     */
    private $visit;

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
        'amount',
        'discount',
        'total_price',
        'sale'         => [
            'amount',
            'price',
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
        $this->visit = factory(Visit::class)->make();
    }

    /** @test */
    public function get_visits(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('GET', $this->uri)->assertOk()->assertJsonStructure([$this->jsonStructure]);
    }

    /** @test */
    public function create_visit(): void
    {
        $response = $this->actingAs($this->user)->json('POST', $this->uri, [
            'seller'      => $this->visit->seller_id,
            'customer'    => $this->visit->customer_id,
            'date'        => $this->visit->date->format('d/m/Y'),
            'annotations' => $this->visit->annotations,
        ]);

        $response
            ->assertStatus(201)
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function create_visit_fails(): void
    {
        $this->actingAs($this->user)->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function get_visit(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->visit->id)
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function get_visit_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('GET', $this->uri.$this->visit->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function get_visit_not_modified(): void
    {
        $this->persist();

        $response = $this->actingAs($this->user)->json('GET', $this->uri.$this->visit->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $this->actingAs($this->user)
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->visit->id)
            ->assertStatus(304);
    }

    /** @test */
    public function update_visit(): void
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
    public function update_visit_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('PATCH', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('PATCH', $this->uri.$this->visit->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)
            ->json('PATCH', $this->uri.$this->visit->id, [])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function delete_visit(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->visit->id)->assertStatus(204);
    }

    /** @test */
    public function close_visit(): void
    {
        $this->persist();

        $response = $this->actingAs($this->user)->json('POST', $this->uri.$this->visit->id, [
            'payment_methods' => [],
        ]);

        $response->dump()
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /** @test */
    public function close_visit_fail(): void
    {
        $this->persist();

        $this->actingAs($this->user)
            ->json('POST', $this->uri.$this->visit->id, [
                'payment_methods' => [
                    'method' => 'money',
                    'value'  => 0,
                ],
            ])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /** @test */
    public function delete_visit_fails(): void
    {
        $this->persist();

        $this->actingAs($this->user)->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->actingAs($this->user)->json('DELETE', $this->uri.$this->visit->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Visit::truncate();
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
     * @return \Modules\Sales\Tests\Feature\Http\Controllers\VisitControllerTest
     */
    private function persist(): VisitControllerTest
    {
        $this->visit->save();

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update(): TestResponse
    {
        return $this->actingAs($this->user)->json('PUT', $this->uri.$this->visit->id, [
            'seller'      => $this->visit->seller_id,
            'customer'    => $this->visit->customer_id,
            'date'        => $this->visit->date->format('d/m/Y'),
            'annotations' => $this->faker->text,
        ]);
    }
}
