<?php

namespace App\Controllers;

namespace App\Controllers;

use App\Helpers\FileUploadHelper;
use App\Helpers\FlashMessage;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Models\CategoriesModel;
use App\Domain\Models\ProductsModel;
use App\Helpers\SessionManager;

class UsersController extends BaseController
{
    public function __construct(Container $container, private ProductsModel $products_model,)
    {
        parent::__construct($container);
    }


    public function index(Request $request, Response $response, array $args): Response
    {
        $data = [
            'page_title' => 'User Home',
            'user_name' => SessionManager::get('user_name'),
            'user_email' => SessionManager::get('user_email'),
        ];


        return $this->render($response, 'user/userIndexView.php', $data);
    }
    public function products(Request $request, Response $response, array $args): Response
    {
        $products = $this->products_model->fetchProducts();

        $data = [
            'page_title' => 'List of products',
            'products' => $products
        ];


        return $this->render($response, 'user/userProductIndexView.php', $data);
    }


    public function show(Request $request, Response $response, array $args): Response
    {
        $product_id = $args["product_id"];
        // dd("Editing product: " . $product_id["id"]);

        // $product = $this->products_model->fetchProductById($product_id);
        // $categories = $this->categories_model->getAll();
        $data = [
            'page_title' => 'Details Page',
            // 'product' => $product,
            // 'categories' => $categories

        ];
        return $this->render($response, 'user/products/productsShowView.php', $data);
    }
}
