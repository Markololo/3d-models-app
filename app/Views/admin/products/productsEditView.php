<?php

use App\Helpers\ViewHelper;


//TODO: set the page title dynamically based on the view being rendered in the controller.
// $page_title = 'Products list';
$page_title = $data["page_title"];
$product = $data["product"];
$images = $data["product_images"];
$categories = $data["categories"];

$options = ViewHelper::renderSelectOptions($categories, (string)$product["category_id"], 'id', 'name');

//TODO: We need to load an admin-specific header.
ViewHelper::loadAdminHeader($page_title);
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h2><?= hs(trans('products.editing'))  ?></h2>
    <form method="POST" action="<?= APP_ADMIN_URL ?>/products/update/<?= $product["id"] ?>">
        <div>
            <input type="hidden" name="id" value="<?= $product["id"] ?>">


            <label for="inputName" class="form-label">Name</label>
            <input type="text" name="product_name" class="form-control" id="inputName" value="<?= $product["name"] ?>">
        </div>
        <div>
            <label for="inputDescription" class="form-label">Description</label>
            <input type="text" name="description" class="form-control" id="inputDescription" value="<?= $product["description"] ?>">
        </div>

        <div>
            <label for="inputState" class="form-label">Category</label>

            <select id="inputState" name="category_id" class="form-select">

                <option value="">Choose</option>
                <?= $options ?>
            </select>

            <div>
                <label for="inputPrice" class="form-label">Price</label>
                <input type="number" name="price" class="form-control" id="inputPrice" step="0.01"
                    value="<?= $product["price"] ?>">
            </div>
        </div>
        <div>
            <label for="inputQuantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" id="InputQuantity"
                value="<?= $product["stock_quantity"] ?>">
        </div>

        <br>
        <div><button type="submit" class="btn btn-success">Save</button>
            <a href="<?= APP_ADMIN_URL ?>/products" class="btn btn-danger">Cancel</a>
        </div>

    </form>

    <br><br>
    <div class="card mb-4">
        <div class="card-header">
            <h5>Upload Product Image</h5>
        </div>
        <div class="card-body">
            <!-- <form method="POST" action="upload" enctype="multipart/form-data"> -->
            <form method="POST" action="<?= APP_ADMIN_URL ?>/products/<?= $product['id'] ?>/upload" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="myfile" class="form-label">Choose a file:</label>
                    <input
                        type="file"
                        class="form-control"
                        id="myfile"
                        name="myfile"
                        accept="image/*"
                        required>
                </div>
                <button type="submit" class="btn btn-primary">Upload File</button>
            </form>
        </div>
    </div>

    <div>


        <?php foreach ($images as $key => $prod):
            if (!isset($prod['file_path']) || empty($prod['file_path']))
                $filePath = "/3d-models-app/assets/imageAssets/imagePlaceholder.jpg";
            else {
                $filename = $prod['file_path'];
                $filePath = "/3d-models-app/uploads/images/$filename";
            }

        ?>
            <img src=<?= $filePath ?> class="img-fluid" style="max-width: 15vw">
         <?php endforeach; ?>
    </div>
    <br>
    <br>
</main>

<?php

ViewHelper::loadJsScripts();
//TODO: We need to load an admin-specific footer.
ViewHelper::loadAdminFooter();
?>
