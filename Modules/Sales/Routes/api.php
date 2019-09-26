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

        Route::prefix('refunds')
            ->as('refunds.')
            ->group(function () {
                Route::get('/', 'RefundController@show')->name('show');

                Route::post('/', 'RefundController@store')->name('store');

                Route::match(['PUT', 'PATCH'], '/', 'RefundController@update')->name('update');

                Route::delete('/', 'RefundController@destroy')->name('destroy');
            });

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

                Route::get('{status}', 'PayrollController@getByStatus')
                    ->where('status', 'available|sold|returned')->name('status');

                Route::prefix('sales')
                    ->as('sales.')
                    ->group(function () {
                        Route::get('/', 'PayrollSaleController@show')->name('show');

                        Route::post('/', 'PayrollSaleController@store')->name('store');

                        Route::match(['PUT', 'PATCH'], '/', 'PayrollSaleController@update')->name('update');

                        Route::delete('/', 'PayrollSaleController@destroy')->name('destroy');
                    });

                Route::prefix('refunds')
                    ->as('refunds.')
                    ->group(function () {
                        Route::get('/', 'PayrollRefundController@show')->name('show');

                        Route::post('/', 'PayrollRefundController@store')->name('store');

                        Route::match(['PUT', 'PATCH'], '/', 'PayrollSaleController@update')->name('update');

                        Route::delete('/', 'PayrollRefundController@destroy')->name('destroy');
                    });
            });
    });

