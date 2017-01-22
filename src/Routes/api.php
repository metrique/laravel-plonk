<?php
/*
 * Plonk
 */
Route::group(['prefix' => config('plonk.prefix.api'), 'middleware' => 'api'], function () {
    Route::resource('plonk', '\Metrique\Plonk\Http\Controllers\PlonkController');
});
