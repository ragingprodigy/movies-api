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

$router->get('/', 'Home@index');
$router->get('/search', 'Home@search');
$router->get('/tv', 'TvController@index');
$router->get('/movie', 'MoviesController@index');
$router->get('/movie/{id}', 'MoviesController@single');
$router->get('/tv/{id}', 'TvController@single');
