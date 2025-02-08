<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// $routes->resource('user');

$routes->get('test-db', 'User::testDB');

$routes->options('(:any)', function() {
    return service('response')
        ->setStatusCode(200)
        ->setHeader('Access-Control-Allow-Origin', '*')
        ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
        ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
});

$routes->post('login', 'Auth::login');

$routes->group('user', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'User::showAll');
    $routes->post('/', 'User::create');
    $routes->get('(:segment)', 'User::show/$1');
    $routes->put('(:segment)', 'User::update/$1');
    $routes->delete('(:segment)', 'User::delete/$1');
});

$routes->group('product', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Product::showAll');
    $routes->post('/', 'Product::create');
    $routes->get('(:segment)', 'Product::show/$1');
    $routes->put('(:segment)', 'Product::update/$1');
    $routes->delete('(:segment)', 'Product::delete/$1');
});
