<?php

namespace App\Auth\Traits;

use App\Auth\Models\Token;
use Illuminate\Http\Request;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

trait TokenTrait
{
    public function createToken(Request $request, string $user_id)
    {
        $url = \Str::finish($request->server('HTTP_REFERER'), '/');
        $data = parse_url($url);
        if (config('app.env') !== 'testing') {
            $asked_by = "{$data['scheme']}://{$data['host']}";

            if (isset($data['port']) && $data['port'] !== 80) $asked_by .= ":{$data['port']}";
        } else {
            $asked_by = 'testing';
        }

        $token = Token::create([
            'user_id'    => $user_id,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'asked_by'   => \Str::finish($asked_by, '/'),
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
