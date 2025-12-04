<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'DashboardController::index', ['filter' => 'session']);
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'session']);
$routes->get('/acesso-negado', function() {
    return view('acesso_negado');
});

$routes->group('admin/users', ['filter' => 'group:admin'], function($routes) {
    $routes->get('/', 'Admin\Users::index');
    // AJAX
    $routes->post('store', 'Admin\Users::store');
    $routes->get('list', 'Admin\Users::listAjax');
    $routes->get('get/(:num)', 'Admin\Users::get/$1');
    $routes->post('create', 'Admin\Users::create');
    $routes->post('update/(:num)', 'Admin\Users::update/$1');
    $routes->get('delete/(:num)', 'Admin\Users::delete/$1');
});


service('auth')->routes($routes);
