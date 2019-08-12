<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Modules\User\Models\User;
use Modules\User\Notifications\ResetPasswordNotification;
use Tests\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var \Modules\User\Models\User
     */
    private $user;

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
        'meta'
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->state('fake')->create(['password' => \Hash::make($this->faker->password(8))]);
    }

    protected function tearDown(): void
    {
        User::truncate();
        \DB::collection(config('auth.passwords.users.table'))->truncate();

        parent::tearDown();
    }

    /**
     * @return string
     */
    protected function sendForgotPassword(): string
    {
        \Notification::fake();

        $response = $this->json('POST', 'password/email', ['email' => $this->user->email]);

        $response->assertStatus(200)->assertJsonStructure(['message']);

        $token = '';

        \Notification::assertSentTo($this->user, ResetPasswordNotification::class, function ($notification, $channels) use (&$token) {
            $token = $notification->token;

            return TRUE;
        });

        return $token;
    }

    /** @test */
    public function reset_password()
    {
        $token = $this->sendForgotPassword();
        $password = $this->faker->password(8);

        $response = $this->json('POST', 'password/reset', ['email' => $this->user->email, 'password' => $password, 'password_confirmation' => $password, 'token' => $token]);

        $response->assertStatus(200)
            ->assertJsonStructure($this->jsonStructure);

        $this->user = $this->user->fresh();

        $this->assertTrue(\Hash::check($password, $this->user->password));
    }

    /** @test */
    public function fail_to_reset_password()
    {
        $token = $this->sendForgotPassword();
        $password = $this->faker->password(8);

        $response = $this->json('POST', 'password/reset', ['email' => $this->user, 'password' => $password, 'password_confirmation' => $password]);

        $response->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);

        $response = $this->json('POST', 'password/reset', ['email' => $this->user, 'password' => $password,
                                                           'password_confirmation' => $password, 'token' => $this->faker->password()]);

        $response->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);

        $response = $this->json('POST', 'password/reset', ['email' => $this->faker->email, 'password' => $password, 'password_confirmation' => $password, 'token' => $token]);

        $response->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);

        $response = $this->json('POST', 'password/reset', ['email' => $this->faker->email, 'password' => $password,
                                                           'password_confirmation' => $this->faker->password(8), 'token' => $token]);

        $response->assertStatus(422)
            ->assertJsonStructure($this->errorStructure);
    }
}
