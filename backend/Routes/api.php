<?php

use App\Products\Controllers\ProductController;
$router->get('/productos', ProductController::class, 'index');
$router->get('/productos/{id}', ProductController::class, 'show');
$router->post('/productos', ProductController::class, 'create');