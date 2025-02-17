<?php

use core\library\Router;
use app\controllers\HomeController;
use app\controllers\LoginController;
use app\controllers\ProductController;


try {
    $router = new Router();
    /**
     *? Home
     */

    $router->add('GET', '/', [HomeController::class, 'index']);

    /* *
     ? Product
     */
    $router->add('GET', '/product/([a-z]+)/', [ProductController::class, 'index']);
    $router->add('GET', '/product/([a-z]+)/category/([a-z]+)', [ProductController::class, 'index']);


    /**
     *? Login
     */
    $router->add('GET', '/login', [LoginController::class, 'index']);
    $router->add('POST', '/login', [LoginController::class, 'store']);


    $router->execute();

} catch (\Throwable $th) {
    //throw $th;
}