<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Jenssegers\Mongodb\Auth\DatabaseTokenRepository;
use Modules\User\Models\User;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var \Modules\User\Models\User
     */
    public $user;

    /**
     * @var string
     */
    public $description = 'Bem-vindo';

    /**
     * @var string
     */
    public $subject = 'Seja bem-vindo(a) ao sistema administrativo da CHR';

    /**
     * @var array
     */
    public $button;

    /**
     * Create a new message instance.
     *
     * @param  string  $user_id
     */
    public function __construct(string $user_id)
    {
        $this->user = User::find($user_id);

        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) $key = base64_decode(substr($key, 7));

        $tokens = new DatabaseTokenRepository(
            \DB::connection(config('database.default')),
            \Hash::driver(),
            config('auth.passwords.users.table'),
            $key,
            config('auth.passwords.users.expire')
        );

        $token = $tokens->create($this->user);

        $this->button = [
            'text' => 'Cadastrar senha',
            'link' => route('password.reset', $token),
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.welcome');
    }
}
