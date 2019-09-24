<?php

Route::get('packings/current', 'PackingController@current')->name('packings.current');

Route::apiResource('packings', 'PackingController');

Route::post('packings/{packing}', 'PackingController@checkOut')->name('packings.check_out');

Route::get('packings/{packing}/receive', 'PackingController@toReceive')->name('packings.receive');

Route::get('packings/{packing}/excel', 'PackingController@excel')->name('packings.excel');

Route::apiResource('visits', 'VisitController');

Route::post('visits/{visit}', 'VisitController@close')->name('visits.close');

Route::prefix('visits/{visit}')
    ->as('visits.')
    ->group(function () {
        Route::post('/', 'VisitController@close')->name('close');

        Route::prefix('sales')
            ->as('sales.')
            ->group(function () {
                Route::get('/', 'SaleController@show')->name('show');

                Route::post('/', 'SaleController@store')->name('store');

                Route::match(['PUT', 'PATCH'], '/', 'SaleController@update')->name('update');

                Route::delete('/', 'SaleController@destroy')->name('destroy');
            });

        Route::prefix('payrolls')
            ->as('payrolls.')
            ->group(function () {
                Route::get('/', 'PayrollController@show')->name('show');

                Route::post('/', 'PayrollController@store')->name('store');

                Route::match(['PUT', 'PATCH'], '/', 'PayrollController@update')->name('update');

                Route::delete('/', 'PayrollController@destroy')->name('destroy');
            });
    });

