<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Modules\User\Models\User;
use Tests\TestCase as parentAlias;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\RefreshDatabase;

class LoginControllerTest extends parentAlias
{
    use RefreshDatabase, WithFaker;
    /**
     * @var \Modules\User\Models\User
     */
    private $user;
    /**
     * @var string
     */
    private $password;

    protected function setUp(): void
    {
        parent::setUp();

        $this->password = $this->faker->password(8);
        $this->user = factory(User::class)->state('fake')->create(['password' => \Hash::make($this->password)]);
    }

    protected function tearDown(): void
    {
        User::truncate();

        parent::tearDown();
    }

    /**
     * @param  array   $data
     * @param  string  $method
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function login(array $data, string $method = 'POST')
    {
        return $this->json($method, 'login', $data);
    }

    /** @test */
    public function login_is_post()
    {
        $response = $this->login(['email' => $this->user->email, 'password' => $this->password], 'GET');

        $response->assertStatus(405);

        $response = $this->login(['email' => $this->user->email, 'password' => $this->password], 'DELETE');

        $response->assertStatus(405);

        $response = $this->login(['email' => $this->user->email, 'password' => $this->password], 'PUT');

        $response->assertStatus(405);

        $response = $this->login(['email' => $this->user->email, 'password' => $this->password], 'PATCH');

        $response->assertStatus(405);

        $response = $this->login(['email' => $this->user->email, 'password' => $this->password]);

        $response->assertStatus(200);
    }

    /**
     * @param  array  $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function loginSuccess(array $data)
    {
        $response = $this->login($data);

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
     * @param  array   $data
     * @param  string  $error
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function loginFail(array $data, string $error = 'email')
    {
        $response = $this->login($data);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'id',
            'status',
            'code',
            'title',
            'message',
            'links',
            'meta' => [
                $error
            ],
        ]);

        return $response;
    }

    /**
     * @test
     */
    public function login_with_email()
    {
        $this->loginSuccess(['email' => $this->user->email, 'password' => $this->password]);
    }

    /** @test */
    public function login_with_document()
    {
        $this->loginSuccess(['email' => $this->user->document, 'password' => $this->password]);
    }

    /** @test */
    public function login_returns_validation_error()
    {
        $this->loginFail(['email' => '06771783600', 'password' => $this->password]);

        $this->loginFail(['email' => '1111111111', 'password' => $this->password]);

        $this->loginFail(['email' => $this->faker->email, 'password' => $this->password]);

        $this->loginFail(['email' => $this->faker->word, 'password' => $this->password]);

        $this->loginFail(['email' => $this->user->document, 'password' => $this->faker->password(8)]);

        $this->loginFail(['email' => $this->user->email, 'password' => $this->faker->password(8)]);

        $this->loginFail(['email' => '', 'password' => $this->faker->password(8)]);

        $this->loginFail(['password' => $this->faker->password(8)]);

        $this->loginFail(['email' => $this->user->email, 'password' => ''], 'password');

        $this->loginFail(['email' => $this->user->email], 'password');
    }

    /**
     * @param  string|NULL  $token
     * @param  string       $method
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function logout(string $token = NULL, string $method = 'POST')
    {
        if ($token) {
            return $this->json($method, 'logout', [], ['Authorization' => "Bearer $token"]);
        }

        return $this->json($method, 'logout');
    }

    /**
     * @return string
     */
    private function createToken()
    {
        $response = $this->login(['email' => $this->user->email, 'password' => $this->password]);

        return json_decode($response->getContent())->api_token;
    }

    /** @test */
    public function logout_is_post()
    {
        $token = $this->createToken();

        $response = $this->logout($token, 'GET');

        $response->assertStatus(405);

        $response = $this->logout($token, 'DELETE');

        $response->assertStatus(405);

        $response = $this->logout($token, 'PUT');

        $response->assertStatus(405);

        $response = $this->logout($token, 'PATCH');

        $response->assertStatus(405);

        $response = $this->logout($token);

        $response->assertStatus(204);
    }

}
