<?php

use App\Helpers\ViewHelper;

// $page_title = 'Products list';
$page_title = $data["page_title"];
$product = $data["product"];
// $categories = $data["categories"];

// $options = ViewHelper::renderSelectOptions($categories, (string)$product["category_id"], 'id', 'name');

ViewHelper::loadAdminHeader($page_title);
?>

<main class="col-md-9 px-md-4">

<h1>Product View:</h1>
<br>

<dl
<p><dt>Product Name:</dt> <dd> <?= $product["name"] ?></dd></p>
<p><dt>Product Category:</dt> <?= $product["category_id"] ?></p>
<p><dt>Product Price:</dt> $<?= $product["price"] ?></p>
<p><dt>Product Stock:</dt> <?= $product["stock_quantity"] ?> units</p>
<p><dt>Product Description:</dt> <?= $product["description"] ?></p>
</dl>

</main>

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadAdminFooter();
?>
