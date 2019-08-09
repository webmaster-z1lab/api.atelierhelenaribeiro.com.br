<?php

Route::namespace('Auth')
    ->group(function () {
        Route::name('login')->post('login', 'LoginController@login');

        Route::name('logout')->post('logout', 'LoginController@logout');

        Route::name('password.email')->post('password/email', 'ForgotPasswordController@sendResetLinkEmail');

        Route::name('password.update')->post('password/reset', 'ResetPasswordController@reset');
    });

Route::delete('images/{image}/templates/{template}', '\Modules\Catalog\Http\Controllers\TemplateController@destroyImage')->name('templates.images.destroy');
Route::delete('images/{image}/products/{product}', '\Modules\Stock\Http\Controllers\ProductController@destroyImage')->name('products.images.destroy');
