<?php

Route::group([],function () {
        Route::apiResource('users', 'UserController');

        Route::put('users/password', 'UserController@changePassword')->name('users.password');
    });

Route::get('notifications/{user}', 'NotificationController@index');
