<?php

use App\Helpers\ViewHelper;
use App\Controllers\DashboardController;
use App\Helpers\SessionManager;
use App\Middleware\ExceptionMiddleware;
use App\MiddleWare\SessionMiddleware;


$page_title = 'Products';
ViewHelper::loadHeader($page_title);
?>
<div class="container my-4">
    <h1>Order Confirmed!</h1>
    <div class="alert alert-success">
        Your order has been successfully placed. Thank you for your purchase!
    </div>
    <a href="<?= APP_BASE_URL ?>/user/products" class="btn btn-primary">Continue Shopping</a>
</div>
