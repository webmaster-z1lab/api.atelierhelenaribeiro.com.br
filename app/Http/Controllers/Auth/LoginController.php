<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\LoginTrait;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Resources\UserResource;
use App\Exceptions\ErrorObject;
use Modules\User\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, LoginTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        event(new Attempting('api', $this->credentials($request), $request->filled('remember')));

        /** @var \Modules\User\Models\User $user */
        if (ctype_digit($request->get($this->username()))) {
            $user = User::where('document', $request->get($this->username()))->first();
        } else {
            $user = User::where('email', $request->get($this->username()))->first();
        }

        if (!is_null($user) && \Hash::check($request->get('password'), $user->password)) {
            $this->loginWithUser($user, $request);

            return  TRUE;
        }

        return FALSE;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        if (ctype_digit($request->get($this->username()))) {
            $request->validate([
                $this->username() => 'required|cpf',
                'password'        => 'required|string',
            ]);
        } else {
            $request->validate([
                $this->username() => 'required|email',
                'password'        => 'required|string',
            ]);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        /** @var \Modules\User\Models\User $user */
        $user = $this->guard()->user();

        if (! is_null($user)) {
            $this->logoutWithUser($user);
        }

        return response()->json(NULL, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw  ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);

//        $errors = new ErrorObject($validation->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
//
//        throw new HttpResponseException(response()->json($errors->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $user = $this->guard()->user();

        return UserResource::make($user);
    }


}
