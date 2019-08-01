<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    /**
     * @test
     */
    public function login_no_get()
    {
        $response = $this->get('login');

        $response->assertStatus(405);

        $response = $this->delete('login');

        $response->assertStatus(405);

        $response = $this->put('login');

        $response->assertStatus(405);

        $response = $this->patch('login');

        $response->assertStatus(405);
    }

    /**
     * @test
     */
    public function login_with_document()
    {
        $response = $this->post('login', ['email' => '32489294059', 'password' => '12345678']);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'type',
                'name',
                'email',
                'avatar',
                'api_token',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * @test
     */
    public function login_with_document_not_registered()
    {
        $response = $this->post('login', ['email' => '06771783600', 'password' => '12345678']);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function login_with_invalid_document()
    {
        $response = $this->post('login', ['email' => '1111111111', 'password' => '12345678']);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function login_with_email()
    {
        $response = $this->post('login', ['email' => 'chr@z1lab.com.br', 'password' => '12345678']);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'type',
                'name',
                'email',
                'avatar',
                'api_token',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * @test
     */
    public function login_with_email_not_registered()
    {
        $response = $this->post('login', ['email' => 'z1lab@z1lab.com.br', 'password' => '12345678']);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function login_with_invalid_email()
    {
        $response = $this->post('login', ['email' => 'tdsshshah', 'password' => '12345678']);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function login_with_invalid_password()
    {
        $response = $this->post('login', ['email' => '32489294059', 'password' => '123456789']);

        $response->assertStatus(422);

        $response = $this->post('login', ['email' => 'chr@z1lab.com.br', 'password' => '123456789']);

        $response->assertStatus(422);
    }
}
