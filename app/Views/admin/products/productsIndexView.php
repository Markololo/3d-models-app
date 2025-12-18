<?php

use App\Helpers\ViewHelper;
use App\Controllers\DashboardController;
use App\Helpers\SessionManager;
use App\Middleware\ExceptionMiddleware;
use App\MiddleWare\SessionMiddleware;

//TODO: set the page title dynamically based on the view being rendered in the controller.
// $page_title = 'Products list';
$page_title = 'Products';
$products = $data["products"];

//TODO: We need to load an admin-specific header.
ViewHelper::loadAdminHeader($page_title);
?>



<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">


    <h1><?= hs(trans('products.listing'))  ?></h1>


    <!-- Search Container -->


    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search"></i> <!-- Bootstrap Icons -->
                </span>
                <input
                    type="text"
                    class="form-control"
                    id="searchInput"
                    placeholder="Search products by name or description..."
                    aria-label="Search products">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="categoryFilter" aria-label="Filter by category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= hs($category['id']) ?>">
                        <?= hs($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-1">
            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="spinner-border text-primary" role="status" style="display: none;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <!-- Search Results Container -->
    <div id="searchResults" class="row">
        <!-- Results will be dynamically inserted here by JavaScript -->
    </div>

    <div id="defaultProducts" class="table-responsive small">
        <table class="table">
            <thead>
                <th>Image</th>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
            </thead>
            <tbody id="productsTbody">
                <?php foreach ($data["products"] as $key => $prod):
                    if (!isset($prod['file_path']) || empty($prod['file_path']))
                        $filePath = "/3d-models-app/assets/imageAssets/imagePlaceholder.jpg";
                    else {
                        $filename = $prod['file_path'];
                        $filePath = "/3d-models-app/uploads/images/$filename";
                    }

                ?>
                    <tr>
                        <!-- <td><img src="/3d-models-app/assets/imageAssets/imagePlaceholder.jpg" class="img-fluid"></td> -->
                        <td><img src=<?= $filePath ?> class="img-fluid" style="max-width: 15vw"></td>
                        <td><?= htmlspecialchars($prod["id"]) ?></td>
                        <td><?= htmlspecialchars($prod["name"]) ?></td>
                        <td><?= htmlspecialchars($prod["description"]) ?></td>
                        <td><?= htmlspecialchars($prod["price"]) ?></td>
                        <td><?= htmlspecialchars($prod["stock_quantity"]) ?></td>
                        <td>
                            <a href="products/edit/<?php echo $prod["id"] ?>" class="btn btn-success">Edit</a>
                            <a href="products/delete/<?php echo $prod["id"] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            <a href="products/show/<?php echo $prod["id"] ?>" class="btn btn-info">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="products/create" class="btn btn-secondary"> Add New Product </a>
    </div>
</main>
<!-- Pass base URL to JavaScript -->
<script>
    // Make APP_BASE_URL available to JavaScript
    window.APP_BASE_URL = '<?= APP_BASE_URL ?>';
</script>

<!-- Load JavaScript for live search -->
<script src="<?= APP_ASSETS_DIR_URL ?>/js/product-search.js"></script>

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadAdminFooter();
?>
