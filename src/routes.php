<?php

Route::group(['namespace' => 'Cerpus\LaravelAuth\Controllers', 'middleware' => 'cerpusauth'], function () {
    Route::post('/jwt/create', 'JWTController@getJwt')->name('create.jwt');
    Route::get('/oauth2/return', 'Oauth2ReturnController@returnEndpoint')->name('oauth2.return');
    Route::post('/oauth2/code/jwt', 'Oauth2ReturnController@codeJwtEndpoint')->name('oauth2.code.jwt');
    Route::get('/loginbox/data', 'LoginInvokeController@getLoginData')->name('loginbox.data');
});
