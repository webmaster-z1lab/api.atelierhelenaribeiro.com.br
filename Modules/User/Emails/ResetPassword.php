<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $user;

    /**
     * @var array
     */
    public $button;

    /**
     * @var string
     */
    public $subject = 'Recuperação de senha';

    public $description = 'Recuperação de senha. Para criar uma nova senha acesse o link do email.';

    /**
     * ResetPassword constructor.
     *
     * @param $user
     * @param $resetLink
     */
    public function __construct($user, $resetLink)
    {
        $this->user = $user;
        $this->button = [
            'link' => $resetLink,
            'text' => 'Trocar senha',
        ];
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reset-password');
    }
}
