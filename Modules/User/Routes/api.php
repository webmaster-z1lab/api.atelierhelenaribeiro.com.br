<?php

Route::middleware('api.v:1,user')
    ->prefix('v1')
    ->group(function () {
        Route::put('users/password', 'UserController@changePassword')->name('api.users.password');
    });

Route::get('notifications/{user}', 'NotificationController@index');
