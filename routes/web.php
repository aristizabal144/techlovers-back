<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\CategoriasController;

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

$router->post('login', ['as' => 'login', 'uses' => 'UsuariosController@login']);
$router->post('user', ['as' => 'user.store', 'uses' => 'UsuariosController@store']);

$router->group(['middleware' => 'auth'], function () use ($router) {
    //AUTH
    $router->get('logout', ['as' => 'logout', 'uses' => 'UsuariosController@logout']);
    $router->get('auth', ['as' => 'auth', 'uses' => 'UsuariosController@showAuth']);
    
    //CATEGORIES
    $router->get('/categorie', 'CategoriasController@index');
    $router->get('/categorie/{id}', 'CategoriasController@show');
    $router->post('/categorie', 'CategoriasController@create');
    $router->put('/categorie/{id}', 'CategoriasController@update');
    $router->delete('/categorie/{id}', 'CategoriasController@delete');

    //PRODUCTS - ARTICULOS
    $router->get('/product', 'ArticulosController@index');
    $router->get('/product/{id}', 'ArticulosController@show');
    $router->post('/product', 'ArticulosController@create');
    $router->put('/product/{id}', 'ArticulosController@update');
    $router->delete('/product/{id}', 'ArticulosController@delete');
});
