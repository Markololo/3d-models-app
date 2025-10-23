<?php
namespace App\Controllers;

use DI\Container;
use LDAP\Result;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DashboardController extends BaseController
{
    public function __construct(Container $container) //then add model param
    {
        parent::__construct($container);
    }

    //* Step 1) Add a route handler/request handler (controller method : callback method)
    public function default(Request $request, Response $response, array $args) : Response
    {
        //! Process the request: we might need to interact with the model

        $data = [""];
        //* Render a view (OR we can redirect the request to another view)
        return $this->render($response, 'admin/dashboardView.php', $data);

    }


    public function error(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'errorView.php');
    }
}

