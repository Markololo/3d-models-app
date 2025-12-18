<?php

use App\Helpers\ViewHelper;
use App\Controllers\DashboardController;
use App\Helpers\SessionManager;
use App\Middleware\ExceptionMiddleware;
use App\MiddleWare\SessionMiddleware;


$page_title = 'Products';
ViewHelper::loadHeader($page_title);
?>

<!-- Search Container -->
<div class="container my-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Browse Products</h1>
        </div>
    </div>

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
                    <option value="<?= hs((string)$category['id']) ?>">
                        <?= hs((string)$category['name']) ?>
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

    <!-- Default Products Display (shown when no search active) -->
    <div id="defaultProducts" class="row">
        <?php foreach ($data["products"] as $key => $product):
            if (!isset($product['file_path']) || empty($product['file_path']))
                $filePath = "/3d-models-app/assets/imageAssets/imagePlaceholder.jpg";
            else {
                $filename = $product['file_path'];
                $filePath = "/3d-models-app/uploads/images/$filename";
            }

        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">

                    <img src=<?= $filePath ?> class="img-fluid" style="max-width: 15vw">

                    <div class="card-body">
                        <h5 class="card-title"><?= hs($product['name']) ?></h5>
                        <p class="card-text"><?= hs(substr($product['description'], 0, 100)) ?>...</p>
                        <p class="fw-bold text-success">$<?= hs(number_format($product['price'], 2)) ?></p>
                        <span class="badge bg-secondary"><?= hs($product['category_name'] ?? 'Uncategorized') ?></span>
                    </div>
                    <div class="card-footer">
                        <a href="/products/<?= hs((string)$product['id']) ?>" class="btn btn-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Pass base URL to JavaScript -->
<script>
    // Make APP_BASE_URL available to JavaScript
    window.APP_BASE_URL = '<?= APP_BASE_URL ?>';
</script>

<!-- Load JavaScript for live search -->
<script src="<?= APP_ASSETS_DIR_URL ?>/js/user-product-search.js"></script>

<?php ViewHelper::loadFooter(); ?>