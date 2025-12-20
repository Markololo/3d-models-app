<?php

use App\Helpers\ViewHelper;
use App\Controllers\DashboardController;
use App\Helpers\SessionManager;
use App\Middleware\ExceptionMiddleware;
use App\MiddleWare\SessionMiddleware;

$page_title = 'Products';
$orders = $data["orders"];
$customer=$data["customer"];

ViewHelper::loadAdminHeader($page_title);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h3>Customer Name: <?= htmlspecialchars($customer["first_name"])." ".htmlspecialchars($customer["last_name"]) ?></h3>
    <h4>Email: <?= htmlspecialchars($customer["first_name"]) ?></h4>
    <h5>Since: <?= htmlspecialchars($customer["created_at"]) ?></h5>

    <br>
    <h3>Order History</h3>
    <div id="defaultCategories" class="table-responsive small">
        <table class="table">
            <thead>
                <th>ID</th>
                <th>Total</th>
                <th>Status</th>
                <th>Time</th>
            </thead>
            <tbody id="categoriesTbody">
                <?php foreach ($orders as $key => $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order["id"]) ?></td>
                        <td><?= htmlspecialchars($order["total"]) ?></td>
                        <td><?= htmlspecialchars($order["status"]) ?></td>
                        <td><?= htmlspecialchars($order["created_at"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
<script>
    window.APP_BASE_URL = '<?= APP_BASE_URL ?>';
</script>

<script src="<?= APP_ASSETS_DIR_URL ?>/js/product-search.js"></script>

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadAdminFooter();
?>
