<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var Router $router */

use Laravel\Lumen\Routing\Router;

$router->get('/', function () {
    return [
        'build_number' => env('APP_VERSION', 'dev'),
        'api_home' => url('/'),
    ];
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('/', 'HomeController@index');
    $router->get('/genres', 'GenreController@index');

    $router->get('/search', 'HomeController@search');

    $router->get('/tv', 'TvController@index');
    $router->get('/tv/on-air', 'TvController@onAir');
    $router->get('/tv/popular', 'TvController@popular');
    $router->get('/tv/top-rated', 'TvController@topRated');

    $router->get('/tv/{id}', 'TvController@single');

    $router->get('/movie', 'MoviesController@index');
    $router->get('/movie/popular', 'MoviesController@popular');
    $router->get('/movie/top-rated', 'MoviesController@topRated');
    $router->get('/movie/upcoming', 'MoviesController@upcoming');

    $router->get('/movie/{id}', 'MoviesController@single');

    $router->get('/people', 'PeopleController@index');
    $router->get('/person/{id}', 'PeopleController@single');
    $router->get('/person/{id}/image', 'PeopleController@image');
});

