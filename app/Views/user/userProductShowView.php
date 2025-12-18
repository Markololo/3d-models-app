<?php

use App\Helpers\ViewHelper;

// $page_title = 'Products list';
$page_title = $data["page_title"];
$product = $data["product"];
$images = $data['product_images'];
// $categories = $data["categories"];

// $options = ViewHelper::renderSelectOptions($categories, (string)$product["category_id"], 'id', 'name');
ViewHelper::loadHeader($page_title);

?>
<br>
<a href="<?= '/' . APP_ROOT_DIR_NAME . '/user/products' ?>" class="btn btn-primary">Back to products view</a>
<main class="col-md-9 px-md-4">
    <br>
    <h1>Product View:</h1>
    <br>


    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($images as $key => $prod):
                if (!isset($prod['file_path']) || empty($prod['file_path']))
                    $filePath = "/3d-models-app/assets/imageAssets/imagePlaceholder.jpg";
                else {
                    $filename = $prod['file_path'];
                    $filePath = "/3d-models-app/uploads/images/$filename";
                }

            ?>
                <div class="carousel-item <?= $key == 0 ? 'active' : '' ?>"><!-- since cannot have multiple active -->
                    <img src=<?= $filePath ?> class="d-block mx-auto img-fluid" style="max-width: 15vw; align-items: center;" class="d-block w-100">
                    <!-- the only ai is to get "class="d-block mx-auto img-fluid"" to align in center of carousel -->
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <dl
        <p>
        <dt>Product Name:</dt>
        <dd> <?= $product["name"] ?></dd>
        </p>
        <p>
            <dt>Product Category:</dt> <?= $product["category_id"] ?>
        </p>
        <p>
            <dt>Product Price:</dt> $<?= $product["price"] ?>
        </p>
        <p>
            <dt>Product Stock:</dt> <?= $product["stock_quantity"] ?> units
        </p>
        <p>
            <dt>Product Description:</dt> <?= $product["description"] ?>
        </p>
    </dl>

    <form method="POST" action="<?= APP_BASE_URL ?>/user/cart/add/<?= $product['id'] ?>">
        <button class="btn btn-primary">Add To Cart</button>
    </form>


</main>

<?php

ViewHelper::loadJsScripts();
?>