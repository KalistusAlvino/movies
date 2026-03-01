<?php

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', 'AuthController@showLoginForm')->name('login');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout')->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/movies', 'MovieController@index')->name('movies.index');
    Route::get('/movies/{id}', 'MovieController@show')->name('movies.show');

    Route::get('/favorites', 'FavoriteController@index')->name('favorites.index');

    Route::post('/language/{locale}', 'LanguageController@switch')->name('language.switch');

    Route::prefix('api')->group(function () {
        Route::get('/movies/search', 'MovieController@search');
        Route::get('/movies/imdb', 'MovieController@getByImdb');
        Route::get('/movies/episode', 'MovieController@getEpisode');

        Route::post('/favorites/add', 'FavoriteController@add');
        Route::delete('/favorites/{id}', 'FavoriteController@remove');
        Route::get('/favorites/check', 'FavoriteController@check');
    });
});

