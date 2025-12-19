<?php

use App\Helpers\ViewHelper;

$page_title = $data["page_title"];
$category = $data['category'];

ViewHelper::loadAdminHeader($page_title);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h2>Edit Category:</h2>

    <form method="POST" action="<?= APP_ADMIN_URL ?>/categories/update/<?= $category["id"] ?>">
        <br>
        <div>
            <label for="inputName" class="form-label">*Name:</label>
            <input type="text" name="category_name" class="form-control" id="inputName" value="<?= $category["name"] ?>">
        </div>
        <br>
        <div>
            <label for="inputDescription" class="form-label">Description:</label>
            <input type="text" name="category_description" class="form-control" id="inputDescription">
        </div>

        <br>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="<?= APP_ADMIN_URL ?>/categories" class="btn btn-danger">Cancel</a>

    </form>
</main>

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadAdminFooter();

?>
