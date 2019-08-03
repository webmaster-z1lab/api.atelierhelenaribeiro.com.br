<?php

namespace App\Auth\Guards;

use App\Auth\Models\Token;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;

class JwtGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(
        UserProvider $provider,
        Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->request->bearerToken();

        if (! empty($token)) {
            $jwt = $this->validateToken($token);

            if (is_null($jwt) || $jwt->isExpired() || !$jwt->hasClaim('sub') || empty($jwt->getClaim('sub'))) {
                return  NULL;
            }

            $user = $this->provider->retrieveById($jwt->getClaim('sub'));
        }

        return $this->user = $user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return false;
    }

    /**
     * Set the current request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param string  $token
     *
     * @return \Lcobucci\JWT\Token|null
     */
    private function validateToken(string $token)
    {
        $jwt = (new Parser())->parse($token);

        $token = Token::find($jwt->getClaim('jti'));

        $data = new ValidationData();
        $data->setCurrentTime(now()->timestamp);
        $data->setIssuer(\Str::finish(config('app.url'), '/'));
        $data->setSubject($token->user_id);

        return $jwt->validate($data) && $jwt->verify(new Sha256(), 'file://' . storage_path('public.key')) ? $jwt : NULL;
    }
}
