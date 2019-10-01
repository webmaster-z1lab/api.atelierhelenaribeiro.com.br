<?php

Route::apiResource('orders', 'OrderController');

Route::post('orders/{order}', 'OrderController@ship')->name('orders.ship');
