<?php

use App\Products\Controllers\ProductController;
$router->get('/products', ProductController::class, 'index');
$router->get('/products/{id}', ProductController::class, 'show');