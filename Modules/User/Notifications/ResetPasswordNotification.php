<?php

namespace Modules\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\User\Emails\ResetPassword;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * @var array
     */
    private $user;

    /**
     * ResetPasswordNotification constructor.
     *
     * @param  array  $user
     * @param         $token
     */
    public function __construct(array $user, $token)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @param $notifiable
     *
     * @return \Modules\User\Emails\ResetPassword
     */
    public function toMail($notifiable)
    {
        return (new ResetPassword($this->user, $this->resetUrl()))->to($this->user['email']);
    }

    /**
     * @param $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user' => $this->user,
        ];
    }

    /**
     * @return string
     */
    private function resetUrl()
    {
        return route('password.reset', $this->token).'?'.http_build_query(['email' => $this->user['email']]);
    }
}
