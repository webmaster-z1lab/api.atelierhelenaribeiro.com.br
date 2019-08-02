<?php

namespace Modules\Employee\Tests\Feature\Http\Controllers;

use Faker\Provider\pt_BR\PhoneNumber;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Models\User;
use Tests\RefreshDatabase;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @var \Modules\User\Models\User
     */
    private $employee;
    /**
     * @var array
     */
    private $jsonStructure = [
        'id',
        'name',
        'document',
        'email',
        'type',
        'created_at',
        'updated_at',
        'address',
        'phone',
        'is_whatsapp',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->faker->addProvider(new PhoneNumber($this->faker));
        $this->employee = factory(User::class)->state('fake')->make();
    }

    /**
     * @test
     */
    public function get_employees()
    {
        $this->persist();

        $response = $this->json('GET', '/employees');

        $response->assertOk()->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function create_employee()
    {
        $response = $this->json('POST', '/employees', [
            'name'        => $this->employee->name,
            'email'       => $this->employee->email,
            'document'    => $this->employee->document,
            'type'        => $this->employee->type,
            'phone'       => $this->employee->phone->full_number,
            'is_whatsapp' => $this->employee->phone->is_whatsapp,
            'address'     => $this->employee->address->toArray(),
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
    public function create_employee_fails()
    {
        $this->json('POST', '/employees', [])->assertStatus(422);
    }

    /**
     * @test
     */
    public function get_employee()
    {
        $this->persist();

        $response = $this->json('GET', '/employees/'.$this->employee->id);

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
    public function get_employee_not_modified()
    {
        $this->persist();

        $employee = $this->json('GET', '/employees/'.$this->employee->id);

        $employee
            ->assertOk()
            ->assertHeader('ETag')
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $response = $this
            ->withHeaders(['If-None-Match' => $employee->getEtag()])
            ->json('GET', '/employees/'.$this->employee->id);

        $response->assertStatus(304);
    }

    /**
     * @test
     */
    public function get_employee_modified()
    {
        $this->persist();

        $employee = $this->json('GET', '/employees/'.$this->employee->id);

        $employee
            ->assertOk()
            ->assertHeader('ETag')
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        sleep(1);

        $this->update();

        $response = $this
            ->withHeaders(['If-None-Match' => $employee->getEtag()])
            ->json('GET', '/employees/'.$this->employee->id);

        $response->assertOk()->assertOk()
            ->assertHeader('ETag')
            ->assertHeader('Content-Length')
            ->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function update_employee()
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
    public function delete_employee()
    {
        $this->persist();

        $response = $this->json('DELETE', '/employees/'.$this->employee->id);

        $response->assertStatus(204);
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        User::truncate();

        parent::tearDown();
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function update()
    {
        return $this->json('PATCH', '/employees/'.$this->employee->id, [
            'name'        => $this->faker->name,
            'email'       => $this->faker->safeEmail,
            'document'    => $this->employee->document,
            'type'        => $this->employee->type,
            'phone'       => $this->faker->phoneNumberCleared,
            'is_whatsapp' => $this->employee->phone->is_whatsapp,
            'address'     => [
                'street'      => $this->faker->streetName,
                'number'      => $this->employee->address->number,
                'complement'  => $this->employee->address->complement,
                'district'    => $this->employee->address->district,
                'postal_code' => $this->employee->address->postal_code,
                'city'        => $this->faker->city,
                'state'       => $this->employee->address->state,
            ],
        ]);
    }

    /**
     * Save employee to Database
     */
    private function persist()
    {
        $this->employee->save();
    }
}
