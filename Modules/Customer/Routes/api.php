<?php

Route::apiResource('customers', 'CustomerController');

Route::prefix('customers/{customer}')->group(function () {
    Route::apiResource('owners', 'OwnerController');
});
