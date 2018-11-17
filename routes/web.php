<?php
Route::get('/', 'RouteController@index');
Route::post('/', 'RouteController@manageTags');
Route::get('download/{name}', 'RouteController@download');
