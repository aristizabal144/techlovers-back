<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\AlmacenesController;

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
    $router->get('/categorie/categorie-search', 'CategoriasController@searchByParams');
    $router->get('/categorie', 'CategoriasController@index');
    $router->get('/categorie/{id}', 'CategoriasController@show');
    $router->post('/categorie', 'CategoriasController@create');
    $router->put('/categorie/{id}', 'CategoriasController@update');
    $router->delete('/categorie/{id}', 'CategoriasController@delete');


    //PRODUCTS - ARTICULOS

    $router->get('/product/product-search', 'ArticulosController@searchByParams');
    $router->get('/product', 'ArticulosController@index');
    $router->get('/product/{id}', 'ArticulosController@show');
    $router->post('/product', 'ArticulosController@create');
    $router->put('/product/{id}', 'ArticulosController@update');
    $router->delete('/product/{id}', 'ArticulosController@destroy');

    //CIUDADES
    $router->get('/ciudades-search', 'CitiesController@searchByParams');


    //CLIENTES
    $router->get('/client/client-search', 'ClientesController@searchByParams');
    $router->get('/client', 'ClientesController@index');
    $router->get('/client/{id}', 'ClientesController@show');
    $router->post('/client', 'ClientesController@create');
    $router->put('/client/{id}', 'ClientesController@update');
    $router->delete('/client/{id}', 'ClientesController@destroy');


    //ALMACENES

    $router->get('/store/store-search', 'AlmacenesController@searchByParams');
    $router->get('/store', 'AlmacenesController@index');
    $router->get('/store/{id}', 'AlmacenesController@show');
    $router->post('/store', 'AlmacenesController@create');
    $router->put('/store/{id}', 'AlmacenesController@update');
    $router->delete('/store/{id}', 'AlmacenesController@destroy');
    $router->get('/search/store', 'AlmacenesController@search');


    // COTIZACIONES
    $router->get('/quote', 'CotizacionController@index');
    $router->post('/quote', 'CotizacionController@create');
    $router->get('/quote/{id}', 'CotizacionController@show');
    $router->delete('/quote/{id}', 'CotizacionController@destroy');

    // FACTURAS
    $router->get('/invoice', 'FacturaController@index');
    $router->post('/invoice', 'FacturaController@create');
    $router->get('/invoice/{id}', 'FacturaController@show');
    $router->delete('/invoice/{id}', 'FacturaController@destroy');

     // DEVOLUCIONES
     $router->get('/return', 'DevolucionesController@index');
     $router->post('/return', 'DevolucionesController@create');
     $router->get('/return/{id}', 'DevolucionesController@show');
     $router->delete('/return/{id}', 'DevolucionesController@destroy');
     $router->get('/return/return-search', 'DevolucionesController@searchByParams');
});
