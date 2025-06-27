<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('/api/coasters', 'Api\Coasters::add');
$routes->put('/api/coasters/(:segment)', 'Api\Coasters::update/$1');
$routes->post('/api/coasters/(:segment)/wagons', 'Api\Coasters::addWagon/$1');
$routes->delete('/api/coasters/(:segment)/wagons/(:segment)', 'Api\Coasters::deleteWagon/$1/$2');

