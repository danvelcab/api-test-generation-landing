<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index_prov');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::prefix('repositories')->group(function () {
    Route::get('/create', 'RepositoryController@create');
    Route::post('/save', 'RepositoryController@save');
    Route::middleware(['myRepository'])->group(function () {
        Route::get('/edit/{repository_id}', 'RepositoryController@edit');
        Route::post('/update/{repository_id}', 'RepositoryController@update');
        Route::get('/delete/{repository_id}', 'RepositoryController@delete');
        Route::get('/generateParamsFile/{repository_id}', 'RepositoryController@generateParamsFile');
        Route::get('/generateTests/{repository_id}', 'RepositoryController@generateTests');
        Route::post('/upload-params-file/{repository_id}', 'RepositoryController@uploadParamsFile');
    });
});