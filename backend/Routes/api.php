<?php

use App\Products\Controllers\ProductController;
$router->get('/productos', ProductController::class, 'index');
$router->post('/productos', ProductController::class, 'create');
$router->get('/productos/{id}', ProductController::class, 'show');
$router->put('/productos/{id}', ProductController::class, 'update');
$router->delete('/productos/{id}', ProductController::class, 'delete');