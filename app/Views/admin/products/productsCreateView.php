<?php

use App\Helpers\ViewHelper;

$page_title = $data["page_title"];
$options = ViewHelper::renderSelectOptions($categories, '', 'id', 'name');

ViewHelper::loadAdminHeader($page_title);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h2>Create New Products:</h2>

    <form method="POST" action="<?= APP_ADMIN_URL ?>/products">
        <div>
            <label for="inputName" class="form-label">*Name:</label>
            <input type="text" name="product_name" class="form-control" id="inputName">
        </div>
        <div>
            <label for="inputPrice" class="form-label">*Price:</label>
            <input type="text" name="product_price" class="form-control" id="inputPrice">
        </div>
        <div>
            <label for="inputDescription" class="form-label">Description:</label>
            <input type="text" name="product_description" class="form-control" id="inputDescription">
        </div>
        <div>
            <label for="inputState" class="form-label">Category:</label>
            <select id="inputState" name="category_name" class="form-select">
                <?= $options ?>
            </select>
        </div>

        <br>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="<?= APP_ADMIN_URL ?>/products" class="btn btn-danger">Cancel</a>

    </form>
</main>

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadAdminFooter();

?>
