<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Modules\User\Models\User;
use Modules\User\Notifications\ResetPasswordNotification;
use Tests\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ForgotPasswordControllerTest extends TestCase
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
        'message'
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
        'meta' => [
            'email'
        ],
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
     * @test
     */
    public function send_forgot_password_email(): void
    {
        \Notification::fake();

        \Notification::assertNothingSent();

        $response = $this->json('POST', 'password/email', ['email' => $this->user->email]);

        $response->assertOk()->assertJsonStructure($this->jsonStructure);

        \Notification::assertSentTo($this->user, ResetPasswordNotification::class);
    }

    /**
     * @test
     */
    public function fail_to_send_forgot_password_email(): void
    {
        \Notification::fake();

        \Notification::assertNothingSent();

        $response = $this->json('POST', 'password/email', ['email' => $this->faker->email]);

        $response->assertStatus(422)->assertJsonStructure($this->errorStructure);

        \Notification::assertNothingSent();

        $response = $this->json('POST', 'password/email', ['email' => $this->faker->name]);

        $response->assertStatus(422)->assertJsonStructure($this->errorStructure);

        \Notification::assertNothingSent();

        $response = $this->json('POST', 'password/email', ['email' => '']);

        $response->assertStatus(422)->assertJsonStructure($this->errorStructure);

        \Notification::assertNothingSent();

        $response = $this->json('POST', 'password/email');

        $response->assertStatus(422)->assertJsonStructure($this->errorStructure);

        \Notification::assertNothingSent();
    }
}
