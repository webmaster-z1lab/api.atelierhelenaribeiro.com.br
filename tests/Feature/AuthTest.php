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
    public function login_is_post()
    {
        $response = $this->get('login');

        $response->assertStatus(405);

        $response = $this->delete('login');

        $response->assertStatus(405);

        $response = $this->put('login');

        $response->assertStatus(405);

        $response = $this->patch('login');

        $response->assertStatus(405);

        $response = $this->post('login');

        $this->assertTrue($response->getStatusCode() !== 405);
    }

    /**
     * @param  array  $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function loginSuccess(array $data)
    {
        $response = $this->post('login', $data);

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

        return $response;
    }

    /**
     * @param  array  $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function loginFail(array $data)
    {
        $response = $this->post('login', $data);

        $response->assertStatus(422);

        return $response;
    }

    /**
     * @test
     */
    public function login_with_document()
    {
        $this->loginSuccess(['email' => '32489294059', 'password' => '12345678']);
    }

    /**
     * @test
     */
    public function login_with_document_not_registered()
    {
       $this->loginFail(['email' => '06771783600', 'password' => '12345678']);
    }

    /**
     * @test
     */
    public function login_with_invalid_document()
    {
        $this->loginFail(['email' => '1111111111', 'password' => '12345678']);
    }

    /**
     * @test
     */
    public function login_with_email()
    {
        $this->loginSuccess(['email' => 'chr@z1lab.com.br', 'password' => '12345678']);
    }

    /**
     * @test
     */
    public function login_with_email_not_registered()
    {
        $this->loginFail(['email' => 'z1lab@z1lab.com.br', 'password' => '12345678']);
    }

    /**
     * @test
     */
    public function login_with_invalid_email()
    {
        $this->loginFail(['email' => 'tdsshshah', 'password' => '12345678']);
    }

    /**
     * @test
     */
    public function login_with_invalid_password()
    {
        $this->loginFail(['email' => '32489294059', 'password' => '123456789']);

        $this->loginFail(['email' => 'chr@z1lab.com.br', 'password' => '123456789']);
    }
}
