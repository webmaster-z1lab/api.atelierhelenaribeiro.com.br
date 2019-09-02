<?php

Route::apiResource('packings', 'PackingController');

Route::post('packings/{packing}', 'PackingController@checkOut')->name('packings.check_out');

Route::apiResource('visits', 'VisitController');
