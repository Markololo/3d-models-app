<?php

use App\Helpers\ViewHelper;
use App\Controllers\DashboardController;
use App\Helpers\SessionManager;
use App\Middleware\ExceptionMiddleware;
use App\MiddleWare\SessionMiddleware;



ViewHelper::loadHeader($page_title); ?>

<br>
<a href="<?= '/' . APP_ROOT_DIR_NAME . '/user/products' ?>" class="btn btn-primary">Back to Products</a>

<div class="container my-4">
    <h1>My Order</h1>

    <?php if (empty($data['items'])): ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php else: ?>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($data['items'] as $item): ?>
                    <tr>
                        <td><?= hs($item['name']) ?></td>
                        <td>$<?= number_format($item['unit_price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td class="fw-bold">
                            $<?= number_format($item['unit_price'] * $item['quantity'], 2) ?>
                        </td>
                        <td>
                            <form method="post" action="<?= APP_BASE_URL ?>/user/cart/increase/<?= $item['product_id'] ?>" class="d-inline">
                                <button class="btn btn-sm btn-success">+</button>
                            </form>

                            <form method="post" action="<?= APP_BASE_URL ?>/user/cart/decrease/<?= $item['product_id'] ?>" class="d-inline">
                                <!-- btn-sm to have it small btn -->
                                <button class="btn btn-sm btn-danger">−</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- align in end bold fontweight fs5 -->
        <?php if (!empty($data['items'])): ?>
            <div class="text-end fw-bold fs-5">
                Total: $<?= number_format($data['order']['total'], 2) ?>
            </div>

            <!-- Checkout Button -->
            <div class="text-end mt-3">
                <a href="<?= APP_BASE_URL ?>/user/checkout" class="btn btn-primary btn-lg">
                    Proceed to Checkout
                </a>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>




<?php ViewHelper::loadFooter(); ?>
