<?php

namespace Modules\User\Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Models\User;
use Tests\RefreshDatabase;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $uri = '/notifications/';
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

    public function setUp(): void
    {
        parent::setUp();

        $this->password = $this->faker->password(8);
        $this->user = factory(User::class)->state('fake')->create(['password' => \Hash::make($this->password)]);
    }

    /**
     * @test
     */
    public function get_notifications()
    {
        $this
            ->withHeaders(['Authorization' => "Bearer {$this->login()}"])
            ->json('GET', $this->uri)
            ->assertOk()
            ->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function get_latest_notifications()
    {
        $this
            ->withHeaders(['Authorization' => "Bearer {$this->login()}"])
            ->json('GET', $this->uri."?filter=latest")
            ->assertOk()
            ->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function update_notification()
    {
        $this->assertTrue(TRUE);
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
     * @return string
     */
    private function login()
    {
        $response = $this->json('POST', '/login', [
            'email'    => $this->user->email,
            'password' => $this->password,
        ]);

        return json_decode($response->getContent())->api_token;
    }
}
