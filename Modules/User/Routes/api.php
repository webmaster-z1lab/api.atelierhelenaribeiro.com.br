<?php

Route::prefix('users')
    ->as('users.')
    ->group(function () {
        Route::get('{user}', 'UserController@show')->name('show');
        Route::put('password', 'UserController@changePassword')->name('change-password');
    });

Route::get('notifications', 'NotificationController@index')->name('notifications.index');
Route::patch('notifications/{notification}', 'NotificationController@update')->name('notifications.update');
