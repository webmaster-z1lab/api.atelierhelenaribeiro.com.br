<?php

Route::middleware('api.v:1,user')
    ->prefix('v1')
    ->group(function () {
        Route::apiResource('users', 'UserController');

        Route::put('users/password', 'UserController@changePassword')->name('users.password');
    });

Route::get('notifications/{user}', 'NotificationController@index');
