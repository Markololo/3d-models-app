<?php

use App\Helpers\ViewHelper;
$page_title = 'Admin Dashboard';

ViewHelper::loadAdminHeader($page_title);
$customers = $data['customers'];
$orders = $data['orders'];
//Order ID, Customer Name or ID, Total Amount, Status, and Date Created
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
            <?php foreach ($data["customers"] as $key => $customer):?>
                <tr>
                        <td><?= htmlspecialchars($customer["id"]) ?></td>
                        <td><?= htmlspecialchars($customer["first_name"].' '.$customer["last_name"]) ?></td>
                        <td><?= htmlspecialchars($customer["email"]) ?></td>
                        <td>
                            <a href="#" class="btn btn-info">View</a>
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
