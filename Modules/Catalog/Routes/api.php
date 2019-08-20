<?php

Route::prefix('templates')
    ->as('templates.')
    ->group(function () {
        Route::get('reference', 'TemplateController@reference')->name('reference');
        Route::get('{template}', 'TemplateController@show')->name('show')->where('template', '\b[0-9a-fA-F]{24}\b');
        Route::get('{template}/gallery', 'TemplateController@gallery')->name('gallery')->where('template', '\b[0-9a-fA-F]{24}\b');
    });

Route::apiResource('templates', 'TemplateController');

