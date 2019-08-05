<?php

Route::prefix('users')
    ->as('users.')
    ->group(function () {
        Route::put('password', 'UserController@changePassword')->name('change-password');

        Route::get('notifications', 'NotificationController@index')->name('notifications.index');
        Route::patch('notifications/{notification}', 'NotificationController@update')->name('notifications.update');
    });
