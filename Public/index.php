<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Debuger;
use App\Core\Bootstrap;
use App\Core\Application;
use App\Core\Database\Database;
(new Bootstrap());
(new Debuger());

$app = new Application(dirname(__DIR__));

Database::connect();


$app->router->get('/', [\App\Controller\SiteController::class, 'Home']);

//Uživatelé
$app->router->get('/user/{id}', [\App\Controller\UserController::class, 'user']);
$app->router->get('/user/add', [\App\Controller\UserController::class, 'addUser']);
$app->router->post('/user/add', [\App\Controller\UserController::class, 'addUser']);
$app->router->get('/user/{id}/edit', [\App\Controller\UserController::class, 'editUser']);
$app->router->post('/user/{id}/edit', [\App\Controller\UserController::class, 'editUser']);
$app->router->get('/user/{id}/delete', [\App\Controller\UserController::class, 'deleteUser']);
$app->router->get('/users', [\App\Controller\UserController::class, 'users']);
//Pojištěnci
$app->router->get('/insureds', [\App\Controller\UserController::class, 'insureds']);

//Pojištění
$app->router->get('/insurances', [\App\Controller\InsuranceController::class, 'insurances']);
$app->router->get('/insurance/{id}', [\App\Controller\InsuranceController::class, 'insurance']);
$app->router->get('/insurance/{id}/edit', [\App\Controller\InsuranceController::class, 'editInsurance']);
$app->router->post('/insurance/{id}/edit', [\App\Controller\InsuranceController::class, 'editInsurance']);
$app->router->get('/insurance/add', [\App\Controller\InsuranceController::class, 'addInsurance']);
$app->router->post('/insurance/add', [\App\Controller\InsuranceController::class, 'addInsurance']);
$app->router->get('/insurance/{id}/delete', [\App\Controller\InsuranceController::class, 'deleteInsurance']);


//Produkty
$app->router->get('/products', [\App\Controller\ProductController::class, 'products']);
$app->router->get('/product/add', [\App\Controller\ProductController::class, 'editProduct']);
$app->router->post('/product/add', [\App\Controller\ProductController::class, 'editProduct']);
$app->router->get('/product/{id}/edit', [\App\Controller\ProductController::class, 'editProduct']);
$app->router->post('/product/{id}/edit', [\App\Controller\ProductController::class, 'editProduct']);
$app->router->get('/product/{id}/delete', [\App\Controller\ProductController::class, 'deleteProduct']);

//Login & logout
$app->router->get('/login', [\App\Controller\AuthController::class, 'login']);
$app->router->post('/login', [\App\Controller\AuthController::class, 'login']);
$app->router->get('/logout', [\App\Controller\AuthController::class, 'logout']);

//Register
$app->router->get('/register', [\App\Controller\AuthController::class, 'register']);
$app->router->post('/register', [\App\Controller\AuthController::class, 'register']);


$app->run();

bdump($_SESSION);