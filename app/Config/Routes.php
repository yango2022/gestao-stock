<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'LandingController::index');

$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'session']);
$routes->get('/acesso-negado', function() {
    return view('acesso_negado');
});


$routes->group('usuarios', ['filter' => 'group:admin'], function($routes) {
    $routes->get('/', 'Admin\Users::index');
    // AJAX
    $routes->post('store', 'Admin\Users::store');
    $routes->get('list', 'Admin\Users::listAjax');
    $routes->get('get/(:num)', 'Admin\Users::get/$1');
    $routes->post('update/(:num)', 'Admin\Users::update/$1');
    $routes->get('delete/(:num)', 'Admin\Users::delete/$1');
});

$routes->group('produtos', ['filter' => 'group:admin,gestor'], function($routes) {
    $routes->get('/', 'Admin\ProductsController::index');
    $routes->get('criar', 'Admin\ProductsController::create');
    $routes->post('store', 'Admin\ProductsController::store');
    $routes->get('list', 'Admin\ProductsController::list');
    $routes->get('get/(:num)', 'Admin\ProductsController::get/$1');
    $routes->get('editar/(:num)', 'Admin\ProductsController::edit/$1');
    $routes->post('update/(:num)', 'Admin\ProductsController::update/$1');
    $routes->get('delete/(:num)', 'Admin\ProductsController::delete/$1');
});

$routes->group('categorias', ['filter' => 'group:admin,gestor'], function($routes){
    $routes->get('/', 'CategoryController::index');
    $routes->post('store', 'CategoryController::store');
    $routes->get('get/(:num)', 'CategoryController::get/$1');
    $routes->post('update/(:num)', 'CategoryController::update/$1');
    $routes->get('delete/(:num)', 'CategoryController::delete/$1');
});

$routes->group('stock', ['filter' => 'group:admin,gestor'], function($routes){
    $routes->get('/', 'StockController::index');
    $routes->post('entrada', 'StockController::entrada');
    $routes->post('saida', 'StockController::saida');
});

$routes->group('vendas', ['filter' => 'group:admin,gestor,vendedor'], function ($routes) {
    $routes->get('/', 'SalesController::index');
    $routes->post('store', 'SalesController::store');
});

$routes->group('clientes', ['filter' => 'group:admin,gestor,vendedor'], function($routes){

    $routes->get('/', 'CustomerController::index');
    $routes->post('store', 'CustomerController::store');
    $routes->get('get/(:num)', 'CustomerController::get/$1');
    $routes->post('update/(:num)', 'CustomerController::update/$1');
    $routes->get('delete/(:num)', 'CustomerController::delete/$1');

});

$routes->group('fornecedores', ['filter' => 'session'], function($routes){
    $routes->get('/', 'Suppliers::index');
    $routes->post('store', 'Suppliers::store');
    $routes->get('get/(:num)', 'Suppliers::get/$1');
    $routes->post('update/(:num)', 'Suppliers::update/$1');
    $routes->get('delete/(:num)', 'Suppliers::delete/$1');
});

$routes->group('fluxo-caixa', function ($routes) {
    $routes->get('/', 'CashFlowController::index');
    $routes->post('store', 'CashFlowController::store');
    $routes->get('edit/(:num)', 'CashFlowController::edit/$1');
    $routes->post('update/(:num)', 'CashFlowController::update/$1');
    $routes->get('delete/(:num)', 'CashFlowController::delete/$1');
});

$routes->group('invoices', ['filter' => 'session'], function ($routes) {
    $routes->get('/', 'InvoicesController::index');
    $routes->get('generate/(:num)', 'InvoicesController::generateFromSale/$1');
    $routes->get('(:num)', 'InvoicesController::show/$1');
    $routes->post('cancel/(:num)', 'InvoicesController::cancel/$1');
});

$routes->get('register', 'Auth\\RegisterController::index');
$routes->post('registar', 'Auth\\RegisterController::store');


$routes->get('invoices/download/(:num)', 'InvoicesController::download/$1');
$routes->get('invoices/create-from-sale/(:num)', 'InvoicesController::generateFromSale/$1');


service('auth')->routes($routes);
