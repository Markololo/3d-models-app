<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Home';
ViewHelper::loadHeader($page_title);
?>
<nav>
    <a href="/"><?= hs(trans('nav.home')) ?></a>
    <a href="/products"><?= hs(trans('nav.products')) ?></a>
    <a href="/cart"><?= hs(trans('nav.cart')) ?></a>
</nav>

<h1><?= hs(trans('home.welcome')) ?></h1>
<p><?= hs(trans('home.description')) ?></p>

<p>This app uses a simple and effective way to pass the container to the controller given the small scope of the application and the fact that this application is to be used in a classroom setting where students are not yet familiar with the Dependency Inversion Principle.</p>

<p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. </p>
<p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. </p>
<button type="submit"><?= hs(trans('common.save')) ?></button>
<button type="button"><?= hs(trans('common.cancel')) ?></button>

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
