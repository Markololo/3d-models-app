<?php

use App\Helpers\ViewHelper;

$page_title = 'Admin Dashboard';

ViewHelper::loadAdminHeader($page_title);
$orders = $data['orders'];
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h2>Customer Orders</h2>
    <div class="table-responsive small">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col">When</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data["orders"] as $key => $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order["user_id"]) ?></td>
                        <td><?= htmlspecialchars($order["username"]) ?></td>
                        <td><?= htmlspecialchars($order["total"]) ?></td>
                        <td><?= htmlspecialchars($order["status"]) ?></td>
                        <td><?= htmlspecialchars($order["created_at"]) ?></td>
                        <td>
                            <a href="customers/<?php echo $order["user_id"] ?>" class="btn btn-info">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadAdminFooter();
?>
