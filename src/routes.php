<?php

Route::group(['namespace' => 'Cerpus\LaravelAuth\Controllers', 'middleware' => 'cerpusauth'], function () {
    Route::post('/jwt/create', 'JWTController@getJwt')->name('create.jwt');
});
