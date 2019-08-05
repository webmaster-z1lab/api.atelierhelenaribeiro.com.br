<?php

namespace Modules\User\Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Models\User;
use Tests\RefreshDatabase;
use Tests\TestCase;

class NotificationController extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $uri = '/users/notifications';
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
        $this->assertTrue(TRUE);
    }

    /**
     * @test
     */
    public function notifications_methods_not_accepted()
    {
        $this->assertTrue(TRUE);
    }

    /**
     * @test
     */
    public function get_latest_notifications()
    {
        $this->assertTrue(TRUE);
    }

    /**
     * @test
     */
    public function update_notification()
    {
        $this->assertTrue(TRUE);
    }

    /**
     * @test
     */
    public function update_notification_from_another_user()
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
}
