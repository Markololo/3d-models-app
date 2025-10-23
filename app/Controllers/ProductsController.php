<?php

namespace App\Controllers;

use DI\Container;
use LDAP\Result;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class ProductsController extends BaseController
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args) : Response
    {
        //! Process the request: we might need to interact with the model

        $data = [""];
        //* Render a view (OR we can redirect the request to another view)
        return $this->render($response, 'admin/products/productsIndexView.php', $data);

    }


    public function error(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'errorView.php');
    }
}

