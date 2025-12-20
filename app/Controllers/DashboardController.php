<?php

namespace App\Controllers;

use App\Domain\Models\UserModel;
use App\Helpers\SessionManager;
use DI\Container;
use LDAP\Result;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DashboardController extends BaseController
{
    public function __construct(Container $container, private UserModel $user_model) //then add model param
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $customers = $this->user_model->getAllCustomers();
        $data = [
            'page_title' => 'Admin Dashboard',
            'customers' => $customers
        ];

        return $this->render($response, 'admin/dashboardView.php', $data);
    }

     public function customerShow(Request $request, Response $response, array $args): Response
    {
        $customerId = (int)$args['customer_id'];
        $customer = $this->user_model->getUserById($customerId);
        $orders = $this->user_model->getCustomerOrderHistory($customerId);

        $data = [
            'page_title' => 'Admin Dashboard',
            'customer' => $customer,
            'orders' => $orders
        ];

        return $this->render($response, 'admin/customerShow.php', $data);
    }

    public function error(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'errorView.php');
    }
}
