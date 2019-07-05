<?php

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/products', function () {
    return view('products.index');
})->name('products.index');

Route::get('/products/show', function () {
    return view('products.show');
})->name('products.show');

Route::get('/products/save', function () {
    return view('products.save');
})->name('products.save');
