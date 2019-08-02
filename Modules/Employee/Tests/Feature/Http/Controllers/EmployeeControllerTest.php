<?php

namespace Modules\Employee\Tests\Feature\Http\Controllers;

use Modules\Employee\Models\Employee;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    /**
     * @test
     */
    public function create_employee()
    {
        $employee = factory(Employee::class)->create();

        $response = $this->json('POST', '/employees', $employee);

        $response->dump();

        $response->assertStatus(201)
            ->assertHeader('ETag')
            ->assertJson([
                'id',
                'name',
                'document',
                'email',
                'type',
                'created_at',
                'updated_at',
                'address',
                'phone',
            ]);
    }

    /**
     * @test
     */
    public function get_employees()
    {
        $this->assertTrue(TRUE);
    }

    /**
     * @test
     */
    public function get_employee()
    {
        $this->assertTrue(TRUE);
    }

    /**
     * @test
     */
    public function get_employee_with_etag()
    {
        $this->assertTrue(TRUE);
    }

    /**
     * @test
     */
    public function update_employee()
    {
        $this->assertTrue(TRUE);
    }

    /**
     * @test
     */
    public function delete_employee()
    {
        $this->assertTrue(TRUE);
    }
}
