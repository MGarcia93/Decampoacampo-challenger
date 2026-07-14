<?php

use App\Products\Controllers\ProductController;
$router->get('/products', ProductController::class, 'index');