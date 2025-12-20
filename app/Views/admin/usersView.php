<?php

use App\Helpers\ViewHelper;

$page_title = 'Products';
$customers = $data["customers"];

ViewHelper::loadAdminHeader($page_title);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

    <div id="defaultCategories" class="table-responsive small">
        <table class="table">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </thead>
            <tbody id="categoriesTbody">
                 <?php foreach ($customers as $key => $customer):?>
                    <tr>
                        <td><?= htmlspecialchars($customer["id"]) ?></td>
                        <td><?= htmlspecialchars($customer["first_name"].' '.$customer["last_name"]) ?></td>
                        <td><?= htmlspecialchars($customer["email"]) ?></td>
                        <td>
                            <a href="customers/<?php echo $customer["id"] ?>" class="btn btn-info">View</a>
                        </td>
                    </tr>
                    <?php endforeach;?>
            </tbody>
        </table>
        <!-- <a href="#" class="btn btn-secondary"> View Orders </a> -->
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
