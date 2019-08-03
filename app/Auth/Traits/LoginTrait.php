<?php

namespace App\Auth\Traits;

use App\Auth\Models\Token;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Modules\User\Models\User;

trait LoginTrait
{
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
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $user_id
     *
     * @return string
     */
    protected function createToken(Request $request, string $user_id): string
    {
        if ($request->filled('remember')) {
            $token = Token::create([
                'user_id'    => $user_id,
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
                'asked_by' => $request->getHttpHost(),
            ]);
        } else {
            $token = Token::create([
                'user_id'    => $user_id,
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
                'asked_by'   => $request->getHttpHost(),
                'expires_at' => now()->addMinutes(intval(config('session.lifetime'))),
            ]);
        }

        $jwt = (new Builder())
            ->issuedBy(\Str::finish(config('app.url'), '/'))
            ->permittedFor($token->asked_by)
            ->identifiedBy($token->id, TRUE)
            ->issuedAt($token->created_at->timestamp)
            ->canOnlyBeUsedAfter($token->created_at->timestamp)
            ->expiresAt(optional($token->expires_at)->timestamp)
            ->relatedTo($user_id)
            ->getToken(new Sha256(), new Key('file://' . storage_path('private.key')));

        return (string) $jwt;
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
