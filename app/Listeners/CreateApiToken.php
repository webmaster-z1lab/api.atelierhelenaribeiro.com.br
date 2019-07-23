<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\User\Models\User;

class CreateApiToken
{

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle($event)
    {
        do {
            $token = \Str::random(60);
        } while (User::where('api_token', $token)->exists());

        /** @var \Modules\User\Models\User $user */
        $user = $event->user;

        $user->forceFill(['api_token' => $token]);

        $user->save();
    }
}
