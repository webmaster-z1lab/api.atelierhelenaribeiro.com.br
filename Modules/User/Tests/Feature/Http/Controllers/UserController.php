<?php

namespace Modules\User\Tests\Feature\Http\Controllers;

use Modules\User\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\RefreshDatabase;

class UserController extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $uri = '';
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
    public function change_password()
    {
        $this->assertTrue(TRUE);
    }
}
