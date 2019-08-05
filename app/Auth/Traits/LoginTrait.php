<?php

namespace App\Auth\Traits;

use App\Auth\Models\Token;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Modules\User\Models\User;

trait LoginTrait
{
    use TokenTrait;

    /**
     * @param  \Modules\User\Models\User  $user
     * @param  \Illuminate\Http\Request   $request
     */
    protected function loginWithUser(User $user, Request $request)
    {
        $token = $this->createToken($request, $user->id);

        $this->cycleRememberToken($user);

        $request->headers->add(['Authorization' => 'Bearer '.$token]);

        event(new Login('api', $user, $request->filled('remember')));
    }

    /**
     * @param  \Illuminate\Http\Request   $request
     * @param  \Modules\User\Models\User  $user
     */
    protected function logoutWithUser(Request $request, User $user)
    {
        $jwt = (new Parser())->parse($request->bearerToken());

        Token::whereKey($jwt->getClaim('jti'))->update(['revoked_at' => now()]);

        if (!empty($user->getRememberToken())) {
            $this->cycleRememberToken($user);
        }

        event(new Logout('api', $user));
    }

    /**
     * @param  \Modules\User\Models\User  $user
     */
    protected function cycleRememberToken(User $user)
    {
        $user->setRememberToken($token = \Str::random(60));

        $timestamps = $user->timestamps;

        $user->timestamps = FALSE;

        $user->save();

        $user->timestamps = $timestamps;

        $user->save();
    }
}
