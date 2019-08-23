<?php

namespace Modules\Employee\Tests\Feature\Http\Controllers;

use Faker\Provider\pt_BR\PhoneNumber;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Models\User;
use Tests\RefreshDatabase;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @var string
     */
    private $uri = '/employees/';
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
        $this->employee = factory(User::class)->state('fake')->make();
    }

    /**
     * @test
     */
    public function get_employees(): void
    {
        $this->persist();

        $this->json('GET', $this->uri)->assertOk()->assertJsonStructure([$this->jsonStructure]);
    }

    /**
     * @test
     */
    public function get_sellers(): void
    {
        $this->persist();

        $this->json('GET', $this->uri.'?search=seller')->assertOk()->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function create_employee(): void
    {
        $response = $this->json('POST', $this->uri, [
            'name'           => $this->employee->name,
            'email'          => $this->employee->email,
            'document'       => $this->employee->document,
            'identity'       => $this->employee->identity,
            'work_card'      => $this->employee->work_card,
            'type'           => $this->employee->type,
            'remuneration'   => $this->employee->remuneration_float,
            'phone'          => [
                'number'      => $this->employee->phone->full_number,
                'is_whatsapp' => $this->employee->phone->is_whatsapp,
            ],
            'address'        => $this->employee->address->toArray(),
            'birth_date'     => $this->employee->birth_date->format('d/m/Y'),
            'admission_date' => $this->employee->admission_date->format('d/m/Y'),
        ]);

        $response
            ->assertStatus(201)
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function create_employee_fails(): void
    {
        $this->json('POST', $this->uri, [])->assertStatus(422)->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_employee(): void
    {
        $this->persist();

        $this
            ->json('GET', $this->uri.$this->employee->id)
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function get_employee_fails(): void
    {
        $this->persist();

        $this
            ->json('GET', $this->uri.$this->employee->id.'a')
            ->assertNotFound()
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_employee_not_modified(): void
    {
        $this->persist();

        $response = $this->json('GET', $this->uri.$this->employee->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        $this
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->employee->id)
            ->assertStatus(304);
    }

    /**
     * @test
     */
    public function get_employee_modified(): void
    {
        $this->persist();

        $response = $this->json('GET', $this->uri.$this->employee->id);

        $response
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);

        sleep(1);

        $this->update();

        $this
            ->withHeaders(['If-None-Match' => $response->getEtag()])
            ->json('GET', $this->uri.$this->employee->id)
            ->assertOk()
            ->assertHeader('ETag')
            //->assertHeader('Content-Length')
            //->assertHeader('Cache-Control')
            ->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function update_employee(): void
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

    /**
     * @test
     */
    public function update_employee_fails(): void
    {
        $this->persist();

        $this->json('PATCH', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('PATCH', $this->uri.$this->employee->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);

        $this
            ->json('PATCH', $this->uri.$this->employee->id, [
                'document' => '00000000000',
            ])
            ->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function delete_employee(): void
    {
        $this->persist();

        $this->json('DELETE', $this->uri.$this->employee->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function delete_employee_fails(): void
    {
        $this->persist();

        $this->json('DELETE', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);

        $this->json('DELETE', $this->uri.$this->employee->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
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
    private function update(): TestResponse
    {
        return $this->json('PUT', $this->uri.$this->employee->id, [
            'name'           => $this->faker->name,
            'email'          => $this->faker->safeEmail,
            'document'       => $this->employee->document,
            'identity'       => \Str::random(),
            'work_card'      => \Str::random(),
            'type'           => $this->employee->type,
            'remuneration'   => $this->faker->randomFloat(2, 0.01, 9999999999),
            'phone'          => [
                'number'      => $this->faker->phoneNumberCleared,
                'is_whatsapp' => $this->employee->phone->is_whatsapp,
            ],
            'address'        => [
                'street'      => $this->faker->streetName,
                'number'      => $this->employee->address->number,
                'complement'  => $this->employee->address->complement,
                'district'    => $this->employee->address->district,
                'postal_code' => $this->employee->address->postal_code,
                'city'        => $this->faker->city,
                'state'       => $this->employee->address->state,
            ],
            'birth_date'     => $this->faker->dateTime('today - 18 years')->format('d/m/Y'),
            'admission_date' => $this->faker->dateTime('today')->format('d/m/Y'),
        ]);
    }

    /**
     * Save employee to Database
     */
    private function persist(): void
    {
        $this->employee->save();
    }
}
