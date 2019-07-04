<?php

Route::middleware(['web', 'auth'])->prefix('users')->as('users.')->group(function () {
    Route::get('change-password', 'UserController@changePassword')->name('change-password');
    Route::get('send-password-recovery', 'UserController@sendPasswordRecovery')->name('send-password-recovery');
});
