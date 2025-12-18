<?php

use App\Helpers\ViewHelper;
use App\Controllers\DashboardController;
use App\Helpers\SessionManager;
use App\Middleware\ExceptionMiddleware;
use App\MiddleWare\SessionMiddleware;

//TODO: set the page title dynamically based on the view being rendered in the controller.
// $page_title = 'Products list';


$page_title = $data["page_title"];
$categories = $data["categories"];

//TODO: We need to load an admin-specific header.
ViewHelper::loadAdminHeader($page_title);
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">


    <h2>Categories Listing</h2>
    <div class="table-responsive small">
        <h4> <?php echo SessionManager::get('username') ?> </h4>
        <table class="table">
            <thead>
                <th>Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Created At</th>
            </thead>
            <tbody>
                <?php foreach ($data["categories"] as $key => $categorie): ?>
                    <tr>
                        <td><?= ($categorie['id']) ?></td>

                        <td><?= $categorie["name"] ?></td>
                        <td><?= $categorie["description"] ?></td>
                        <td><?= $categorie["created_at"] ?></td>

                        <td>
                            <a href="categories/edit/<?php echo $categorie["id"] ?>" class="btn btn-success">Edit</a>


                            <a href="categories/delete/<?php echo $categorie["id"] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>

                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="categories/create" class="btn btn-secondary"> Add New Category </a>

    </div>
</main>

<?php

ViewHelper::loadJsScripts();
//TODO: We need to load an admin-specific footer.
ViewHelper::loadAdminFooter();
?>
