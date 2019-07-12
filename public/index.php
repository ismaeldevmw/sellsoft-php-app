<?php
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
require '../config/database.php';
require '../config/request_config.php';

session_start();

use Aura\Router\RouterContainer;

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

$map->get('index', '/', [
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction',
    'auth' => true
]);

$map->get('products', '/products', [
    'controller' => 'App\Controllers\ProductsController',
    'action' => 'getProductsAction',
    'auth' => true
]);

$map->get('addProduct', '/products/add', [
    'controller' => 'App\Controllers\ProductsController',
    'action' => 'getAddProductAction',
    'auth' => true
]);

$map->post('saveProduct', '/products/add', [
    'controller' => 'App\Controllers\ProductsController',
    'action' => 'getAddProductAction'
]);

$map->get('users', '/users', [
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getUsersAction',
    'auth' => true
]);

$map->get('addUser', '/users/add', [
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getAddUserAction',
    'auth' => true
]);

$map->post('saveUser', '/users/add', [
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getAddUserAction'
]);

$map->get('loginForm', '/login', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogin'
]);

$map->post('auth', '/auth', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'postLogin'
]);

$map->get('admin', '/admin', [
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getIndex',
    'auth' => true
]);

$map->get('logout', '/logout', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogout'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if (!$route) {
    echo 'No route';
} else {
    
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth = $handlerData['auth'] ?? false;

    $controller = new $controllerName;

    $sessionUserId = $_SESSION['userId'] ?? null;
    if($needsAuth && !$sessionUserId) {
        $response = new Zend\Diactoros\Response\RedirectResponse('/login');
    }else{
        $response = $controller->$actionName($request);
    }

    foreach ($response->getHeaders() as $name => $values) {
       foreach ($values as $value) {
           header(sprintf('%s: %s', $name, $value), false);
       }
    }

    http_response_code($response->getStatusCode());
    echo $response->getBody();
} 
