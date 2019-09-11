<?php

Route::get('packings/current', 'PackingController@current')->name('packings.current');

Route::apiResource('packings', 'PackingController');

Route::post('packings/{packing}', 'PackingController@checkOut')->name('packings.check_out');

Route::apiResource('visits', 'VisitController');

Route::apiResource('sales', 'SaleController');

Route::apiResource('payrolls', 'PayrollController');
