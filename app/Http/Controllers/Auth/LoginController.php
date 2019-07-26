<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Resources\v1\User;
use Z1lab\JsonApi\Exceptions\ErrorObject;

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

    use AuthenticatesUsers;

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
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        /** @var \Modules\User\Models\User $user */
        $user = \Auth::guard('api')->user();
        if (! is_null($user)) {
            if (!empty($user->getRememberToken())) {
                $user->setRememberToken($token = \Str::random(60));

                $timestamps = $user->timestamps;

                $user->timestamps = FALSE;

                $user->save();

                $user->timestamps = $timestamps;

                $user->save();
            }

            event(new Logout('web', $user));
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
        $validation =  ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);

        $errors = new ErrorObject($validation->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException(response()->json($errors->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY));
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

        /** @var \Modules\User\Models\User $user */
        $user = $this->guard()->user();

        $request->headers->add(['Authorization' => 'Bearer ' . $user->api_token]);

        return User::make($user);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return \Auth::guard('web');
    }
}
