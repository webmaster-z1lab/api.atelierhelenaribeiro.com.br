<?php

Route::namespace('Auth')
    ->group(function () {
        Route::name('login')->post('login', 'LoginController@login');

        Route::name('logout')->post('logout', 'LoginController@logout');

        Route::name('password.email')->post('password/email', 'ForgotPasswordController@sendResetLinkEmail');

        Route::name('password.update')->post('password/reset', 'ResetPasswordController@reset');

//        Route::prefix('email')
//            ->as('verification.')
//            ->group(function () {
//                Route::name('verify')->get('verify/{id}', 'VerificationController@verify');
//
//                Route::name('resend')->get('resend', 'VerificationController@resend');
//            });
    });

Route::get('test', function (\Illuminate\Http\Request $request) {
    dd($request->user());
});
