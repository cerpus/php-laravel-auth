<?php

Route::group(['namespace' => 'Cerpus\LaravelAuth\Controllers'], function () {
    Route::post('/jwt/create', 'JWTController@getJwt')->name('create.jwt');
});
