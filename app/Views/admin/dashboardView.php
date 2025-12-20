<?php

use App\Helpers\ViewHelper;
$page_title = 'Admin Dashboard';

ViewHelper::loadAdminHeader($page_title);
$customers = $data['customers'];

?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>
    <canvas
        class="my-4 w-100"
        id="myChart"
        width="900"
        height="380"></canvas>
    <h2>Your Customers</h2>
    <div class="table-responsive small">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Since</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($data["customers"] as $key => $customer):?>
                <tr>
                        <td><?= htmlspecialchars($customer["id"]) ?></td>
                        <td><?= htmlspecialchars($customer["first_name"].' '.$customer["last_name"]) ?></td>
                        <td><?= htmlspecialchars($customer["email"]) ?></td>
                        <td><?= htmlspecialchars($customer["created_at"]) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php

ViewHelper::loadJsScripts();
//TODO: We need to load an admin-specific footer.
ViewHelper::loadAdminFooter();
?>
