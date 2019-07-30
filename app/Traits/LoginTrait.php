<?php

namespace App\Traits;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Modules\User\Models\User;

trait LoginTrait
{
    /**
     * @param  \Modules\User\Models\User  $user
     */
    protected function loginWithUser(User $user, Request $request)
    {
        $user->forceFill(['api_token' => \Str::random(60)]);

        $this->cycleRememberToken($user);

        $request->headers->add(['Authorization' => 'Bearer ' . $user->api_token]);

        event(new Login('api', $user, $request->filled('remember')));
    }

    /**
     * @param  \Modules\User\Models\User  $user
     */
    protected function cycleRememberToken(User &$user)
    {
        $user->setRememberToken($token = \Str::random(60));

        $timestamps = $user->timestamps;

        $user->timestamps = FALSE;

        $user->save();

        $user->timestamps = $timestamps;

        $user->save();
    }

    protected function logoutWithUser(User $user)
    {
        $user->unset('api_token');

        if (!empty($user->getRememberToken())) {
            $this->cycleRememberToken($user);
        }

        event(new Logout('api', $user));
    }
}
