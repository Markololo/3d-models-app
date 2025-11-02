<?php

namespace App\Controllers;

use DI\Container;
use LDAP\Result;
use App\Domain\Models\ProductsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class ProductsController extends BaseController
{
    // public function __construct(Container $container)
    // {
    //     parent::__construct($container);
    // }

        public function __construct(
        Container $container,
        private ProductsModel $products_model
    ) {
        parent::__construct($container); //pass the container to the parent
    }

    public function index(Request $request, Response $response, array $args) : Response
    {
        $products = $this->products_model->fetchProducts();
        $data["page_title"] = "Browse Products";
        $data["products"] = $products;
        return $this->render($response, 'admin/products/productsIndexView.php', $data);

    }

    public function error(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'errorView.php');
    }
}

