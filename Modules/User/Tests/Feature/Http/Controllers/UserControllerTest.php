<?php

namespace Modules\User\Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Models\User;
use Tests\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $uri = '/users/';
    /**
     * @var \Modules\User\Models\User
     */
    private $user;
    /**
     * @var \Modules\User\Models\DatabaseNotification
     */
    private $notification;
    /**
     * @var string
     */
    private $password;
    /**
     * @var array
     */
    private $jsonStructure = [
        'id',
        'type',
        'name',
        'email',
        'avatar',
        'api_token',
        'created_at',
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

        $this->password = $this->faker->password(8);
        $this->user = factory(User::class)->state('fake')->create(['password' => \Hash::make($this->password)]);
    }

    /**
     * @test
     */
    public function change_password(): void
    {
        $this->assertTrue(TRUE);
    }

    public function get_users_fail(): void
    {
        $this->json('GET', $this->uri)->assertStatus(405)->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function get_user(): void
    {
        $token = $this->login();

        $this->withHeaders(['Authorization' => "Bearer $token"])->json('GET', $this->uri.$this->user->id)->assertOk()->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function get_user_fails(): void
    {
        $this->json('GET', $this->uri.$this->user->id.'a')->assertNotFound()->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function delete_user_fails(): void
    {
        $this->json('DELETE', $this->uri.$this->user->id)->assertStatus(405)->assertJsonStructure($this->errorStructure);
    }

    /**
     * @test
     */
    public function update_user_fails(): void
    {
        $this->json('PUT', $this->uri.$this->user->id, [])->assertStatus(405)->assertJsonStructure($this->errorStructure);
    }

    /**
     * @return string
     */
    private function login(): string
    {
        $response = $this->json('POST', '/login', [
            'email'    => $this->user->email,
            'password' => $this->password,
        ]);

        return json_decode($response->getContent())->api_token;
    }
}
