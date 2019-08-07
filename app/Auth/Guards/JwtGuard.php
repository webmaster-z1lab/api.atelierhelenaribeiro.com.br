<?php

namespace App\Auth\Guards;

use App\Auth\Models\Token;
use App\Auth\Traits\TokenTrait;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;

class JwtGuard implements Guard
{
    use GuardHelpers, TokenTrait;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request                 $request
     *
     * @return void
     */
    public function __construct(
        UserProvider $provider,
        Request $request
    ) {
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
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = NULL;

        $token = $this->request->bearerToken();

        if (!is_null($token) && $token !== '') {
            $jwt = $this->validateToken($token);

            if (!is_null($jwt) && !$jwt->isExpired() && $jwt->hasClaim('sub') && !is_null($jwt->getClaim('sub'))) {
                $user = $this->provider->retrieveById($jwt->getClaim('sub'));
            }
        }

        return $this->user = $user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return FALSE;
    }

    /**
     * Set the current request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param  string  $token
     *
     * @return \Lcobucci\JWT\Token|null
     */
    private function validateToken(string $token)
    {
        $jwt = (new Parser())->parse($token);

        $token = Token::whereKey($jwt->getClaim('jti'))
            ->where('asked_by', $this->getAskedBy($this->request->server('HTTP_REFERER', '/')))
            ->first();

        if (is_null($token)) return NULL;

        $data = new ValidationData();
        $data->setCurrentTime(now()->timestamp);
        $data->setIssuer(\Str::finish(config('app.url'), '/'));
        $data->setAudience($token->asked_by);
        $data->setSubject($token->user_id);

        return $jwt->validate($data) && $jwt->verify(new Sha256(), 'file://'.storage_path('public.key')) ? $jwt : NULL;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool   $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false): bool
    {
        event(new Attempting('api', $credentials, $remember));

        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user, $remember);

            return TRUE;
        }

        event(new Failed('api', $user, $credentials));

        return FALSE;
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function login(AuthenticatableContract $user, $remember = false)
    {
        $token = $this->createToken($this->request, $user->getAuthIdentifier());

        $this->request->headers->add(['Authorization' => 'Bearer '. $token]);

        if ($remember) {
            if (empty($user->getRememberToken())) {
                $this->cycleRememberToken($user);
            }
        }

        event(new Login('api', $user, $remember));

        $this->setUser($user);
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $user = $this->user();

        if (! is_null($this->user)) {
            if (! empty($user->getRememberToken())) {
                $this->cycleRememberToken($user);
            }

            $jwt = $this->validateToken($this->request->bearerToken());

            $token = Token::whereKey($jwt->getClaim('jti'))->first();
            $token->update(['revoked_at' => now()]);
        }

        event(new Logout('api', $user));

        $this->request->headers->remove('Authorization');

        $this->user = null;

        $this->loggedOut = true;
    }


    /**
     * Refresh the "remember me" token for the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function cycleRememberToken(AuthenticatableContract $user)
    {
        $user->setRememberToken($token = \Str::random(60));

        $this->provider->updateRememberToken($user, $token);
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return ! is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }
}
