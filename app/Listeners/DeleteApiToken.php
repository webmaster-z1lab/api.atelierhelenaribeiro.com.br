<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteApiToken
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Logout  $event
     * @return void
     */
    public function handle($event)
    {
        /** @var \Modules\User\Models\User $user */
        $user = $event->user;

        $user->unset('api_token');

        $user->save();
    }
}
