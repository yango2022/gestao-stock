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

service('auth')->routes($routes);
