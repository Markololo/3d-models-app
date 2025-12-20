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
    <h1>Checkout</h1>

    <?php if (empty($data['items'])): ?>
        <div class="alert alert-info">Your cart is empty.</div>
        <a href="<?= APP_BASE_URL ?>/user/products" class="btn btn-primary">Back to Products</a>
    <?php else: ?>
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['items'] as $item): ?>
                    <tr>
                        <td><?= hs($item['name']) ?></td>
                        <td>$<?= number_format($item['unit_price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end fw-bold fs-5">
            Total: $<?= number_format($data['order']['total'], 2) ?>
        </div>

        <form method="post" action="<?= APP_BASE_URL ?>/user/checkout/confirm">
            <button type="submit" class="btn btn-success btn-lg mt-3">
                Confirm Purchase
            </button>
        </form>
    <?php endif; ?>
</div>
