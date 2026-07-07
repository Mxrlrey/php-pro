<?php

use app\controllers\HomeController;
use app\controllers\LoginController;
use app\controllers\UserController;
use app\routing\Router;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/user/create', [UserController::class, 'create']);
$router->get('/user/{id}', [UserController::class, 'show']);
$router->get('/login', [LoginController::class, 'index']);

$router->post('/login', [LoginController::class, 'store']);

return $router;
