<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Admin Dashboard';

//TODO: We need to load an admin-specific header.
ViewHelper::loadAdminHeader($page_title);
$customers = $data['customers'];

?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    Share
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    Export
                </button>
            </div>
            <button
                type="button"
                class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1">
                <svg class="bi" aria-hidden="true">
                    <use xlink:href="#calendar3"></use>
                </svg>
                This week
            </button>
        </div>
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
//TODO: We need to load an admin-specific footer.
ViewHelper::loadAdminFooter();
?>
