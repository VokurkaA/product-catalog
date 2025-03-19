<?php

use App\Controllers\AdminController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\ProductController;
use App\Controllers\ProfileController;
use App\Controllers\RegisterController;

$routes = [
    '' => HomeController::class,
    'product' => ProductController::class,
    'profile' => ProfileController::class,
    'login' => LoginController::class,
    'register' => RegisterController::class,
    'cart' => CartController::class,
    'checkout' => CheckoutController::class,
    'admin' => AdminController::class,
];

$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = explode('?', $requestUri)[0];
$requestUri = explode('/', $requestUri)[2];
if (array_key_exists($requestUri, $routes)) {
    $controllerName = $routes[$requestUri];
    $controller = new $controllerName();
    $controller->handle();
} else {
    http_response_code(404);
    echo "404 Not Found";
}