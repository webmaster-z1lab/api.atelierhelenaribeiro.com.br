<?php

namespace App\Auth\Traits;

use App\Auth\Models\Token;
use Illuminate\Http\Request;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

trait TokenTrait
{
    use AskedByTrait;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $user_id
     *
     * @return string
     */
    public function createToken(Request $request, string $user_id): string
    {
        $token = Token::create([
            'user_id'    => $user_id,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'asked_by'   => $this->getAskedBy($request->server('HTTP_REFERER', '/')),
            'expires_at' => $request->filled('remember')
                ? now()->addDecade()
                : now()->addMinutes(intval(config('session.lifetime'))),
        ]);

        $jwt = (new Builder())
            ->issuedBy(\Str::finish(config('app.url'), '/'))
            ->permittedFor($token->asked_by)
            ->identifiedBy($token->id, TRUE)
            ->issuedAt($token->created_at->timestamp)
            ->canOnlyBeUsedAfter($token->created_at->timestamp)
            ->expiresAt($token->expires_at->timestamp)
            ->relatedTo($user_id)
            ->getToken(new Sha256(), new Key('file://'.storage_path('private.key')));

        return (string) $jwt;
    }
}
