<?php

Route::prefix('templates')
    ->as('templates.')
    ->group(function () {
        Route::get('reference', 'TemplateController@reference')->name('reference');
    });

Route::apiResource('templates', 'TemplateController');
