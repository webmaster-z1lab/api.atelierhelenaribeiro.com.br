<?php

namespace Modules\Customer\Tests\Feature\Http\Controllers;

use Faker\Provider\pt_BR\PhoneNumber;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use Tests\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $uri = '/customers/';
    /**
     * @var \Modules\Customer\Models\Customer
     */
    private $customer;

    private $jsonStructure = [
        'id',
        'company_name',
        'trading_name',
        'document',
        'state_registration',
        'municipal_registration',
        'email',
        'address',
        'phones',
        'owners',
        'created_at',
    ];

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

        $this->customer = factory(Customer::class)->make();
        $this->faker->addProvider(new PhoneNumber($this->faker));
    }

    /**
     * @test
     */
    public function get_customers()
    {
        $this->persist();

        $this->json('GET', $this->uri)->assertOk()->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function create_customer()
    {
        $response = $this->json('POST', $this->uri, [
            'company_name'           => $this->customer->company_name,
            'trading_name'           => $this->customer->trading_name,
            'state_registration'     => $this->customer->state_registration,
            'municipal_registration' => $this->customer->municipal_registration,
            'annotation'             => $this->customer->annotation,
            'contact'                => $this->customer->contact,
            'document'               => $this->customer->document,
            'email'                  => $this->customer->email,
            'seller'                 => $this->createSeller()->id,
            'status'                 => $this->customer->status,
            'address'                => $this->customer->address->toArray(),
            'owners'                 => $this->getOwners(),
            'phones'                 => $this->getPhones(),
        ]);

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
    public function create_customer_fails()
    {
        $this->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_customer()
    {
        $this->persist();

        $this
            ->json('GET', $this->uri.$this->customer->id)
            ->assertOk()
            ->assertHeader('ETag')
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function get_customer_fails()
    {
        $this->persist();

        $this
            ->json('GET', $this->uri.$this->customer->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_customer_not_modified()
    {
        $this->persist();

        $response = $this->json('GET', $this->uri.$this->customer->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $this
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->customer->id)
            ->assertStatus(304);
    }

    /**
     * @test
     */
    public function update_customer()
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
    public function update_customer_fails()
    {
        $this->persist();

        $this->json('PATCH', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('PATCH', $this->uri.$this->customer->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this
            ->json('PATCH', $this->uri.$this->customer->id, [
                'document' => '0000000000000',
            ])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function delete_customer()
    {
        $this->persist();

        $this->json('DELETE', $this->uri.$this->customer->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function delete_customer_fails()
    {
        $this->persist();

        $this->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('DELETE', $this->uri.$this->customer->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Customer::truncate();
        parent::tearDown();
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update()
    {
        return $this->json('PUT', $this->uri.$this->customer->id, [
            'company_name'           => $this->faker->company,
            'trading_name'           => $this->faker->companySuffix,
            'state_registration'     => $this->customer->state_registration,
            'municipal_registration' => $this->customer->municipal_registration,
            'annotation'             => $this->customer->annotation,
            'contact'                => $this->customer->contact,
            'document'               => $this->customer->document,
            'email'                  => $this->faker->email,
            'seller'                 => $this->createSeller()->id,
            'status'                 => $this->customer->status,
            'address'                => [
                'street'      => $this->faker->streetName,
                'number'      => $this->customer->address->number,
                'complement'  => $this->customer->address->complement,
                'district'    => $this->customer->address->district,
                'postal_code' => $this->customer->address->postal_code,
                'city'        => $this->faker->city,
                'state'       => $this->customer->address->state,
            ],
            'owners'                 => $this->getOwners(TRUE),
            'phones'                 => $this->getPhones(TRUE),
        ]);
    }

    /**
     * @return $this
     */
    private function persist()
    {
        $this->customer->save();

        return $this;
    }

    /**
     * @param  bool  $update
     *
     * @return array
     */
    private function getOwners(bool $update = FALSE): array
    {
        $owners = [];

        foreach ($this->customer->owners as $key => $owner) {
            $owners[$key] = $owner->toArray();

            $owners[$key]['birth_date'] = $owner->birth_date->format('d/m/Y');
            $owners[$key]['phone'] = [
                'number'      => $update ? $this->faker->phoneNumberCleared : $owner->phone->full_number,
                'is_whatsapp' => $owner->phone->is_whatsapp,
            ];
        }

        return $owners;
    }

    /**
     * @param  bool  $update
     *
     * @return array
     */
    private function getPhones(bool $update = FALSE): array
    {
        $phones = [];

        foreach ($this->customer->phones as $key => $phone) {
            $phones[$key]['number'] = $update ? $this->faker->phoneNumberCleared : $phone->full_number;
            $phones[$key]['is_whatsapp'] = $phone->is_whatsapp;
        }

        return $phones;
    }

    /**
     * @return \Modules\User\Models\User
     */
    private function createSeller(): User
    {
        return factory(User::class)->create(['type' => 'seller']);
    }
}
