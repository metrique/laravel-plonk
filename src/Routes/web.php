<?php
/*
 * Plonk
 */
Route::group(['prefix' => config('plonk.prefix.web'), 'middleware' => 'web'], function () {
    Route::resource('plonk', '\Metrique\Plonk\Http\Controllers\PlonkController');
});
