<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\AlmacenesController;
use App\Http\Controllers\GastosController;


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
    $router->get('/search/store', 'AlmacenesController@search');
    $router->post('/store', 'AlmacenesController@create');
    $router->put('/store/{id}', 'AlmacenesController@update');
    $router->delete('/store/{id}', 'AlmacenesController@destroy');
    


    // COTIZACIONES
    $router->get('/quote/quote-search', 'CotizacionController@searchByParams');
    $router->get('/quote/{id}', 'CotizacionController@show');
    $router->get('/quote', 'CotizacionController@index');
    $router->put('/quote/{id}', 'CotizacionController@update');
    $router->post('/quote', 'CotizacionController@create');
    $router->put('/quote', 'CotizacionController@check');
    $router->delete('/quote/{id}', 'CotizacionController@destroy');

    // FACTURAS
    $router->get('/invoice/invoice-date', 'FacturaController@searchByDate');
    $router->get('/invoice/invoice-search', 'FacturaController@searchByParams');
    $router->get('/invoice', 'FacturaController@index');
    $router->get('/invoice/{id}', 'FacturaController@show');
    $router->post('/invoice', 'FacturaController@create');
    $router->post('/invoice/pay', 'FacturaController@pagarTotalidadFactura');
    $router->delete('/invoice/{id}', 'FacturaController@destroy');
    $router->put('/invoice', 'FacturaController@statusChange');
    $router->post('/invoice/xlsx', 'FacturaController@downloadFacturaXLSX');
    

     // DEVOLUCIONES
     $router->get('/return', 'DevolucionesController@index');
     $router->get('/return/return-search', 'DevolucionesController@searchByParams');
     $router->post('/return', 'DevolucionesController@create');
     $router->get('/return/{id}', 'DevolucionesController@show');
     $router->delete('/return/{id}', 'DevolucionesController@destroy');

    // ABONOS
    $router->get('/abonos', 'AbonosController@index');
    $router->post('/abonos', 'AbonosController@create');
    $router->delete('/abonos/{id}', 'AbonosController@delete');
    $router->put('/abonos/{id}', 'AbonosController@update');

    // DASHBOARD
    $router->get('/dashboard/return-bullet', 'FacturaController@searchBulletInformation');
    $router->get('/dashboard/return-invoice-payments', 'FacturaController@searchInvoicePayments');

    // CONTABLE
    $router->get('/factura-contable', 'FacturaContabilidadController@index');
    $router->get('/factura-contable-byId/{id}', 'FacturaContabilidadController@show');
    $router->get('/factura-contable/{id}', 'FacturaContabilidadController@getFacturasArticulo');
    $router->put('/factura-contable/{id}', 'FacturaContabilidadController@update');
    $router->post('/factura-contable', 'FacturaContabilidadController@create');
    $router->delete('/factura-contable/{id}', 'FacturaContabilidadController@destroy');


    // GASTOS
    $router->get('/gastos/{id}', 'GastosController@show');
    $router->get('/gastos', 'GastosController@index');
    $router->get('/gastos-date', 'GastosController@searchByDate');
    $router->put('/gastos/{id}', 'GastosController@update');
    $router->post('/gastos', 'GastosController@create');
    $router->delete('/gastos/{id}', 'GastosController@delete');

    //STORAGE
    $router->get('/storage', 'FacturaContabilidadController@storageGet');
    $router->post('/storage', 'FacturaContabilidadController@storageSave');
    
    
});
