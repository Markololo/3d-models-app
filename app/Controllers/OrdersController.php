<?php

namespace App\Controllers;

use App\Domain\Models\OrdersModel;
use App\Domain\Models\UserModel;
use App\Helpers\SessionManager;
use DI\Container;
use LDAP\Result;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OrdersController extends BaseController
{
    public function __construct(Container $container, private OrdersModel $ordersModel) //then add model param
    {
        parent::__construct($container);
    }

    public function adminIndex(Request $request, Response $response, array $args): Response
    {
        //Order ID, Customer Name or ID, Total Amount, Status, and Date Created
        $customers = $this->ordersModel->getAllCustomers();
        $orders = $this->ordersModel->getAllOrders();
        $data = [
            'page_title' => 'Admin Dashboard',
            'customers' => $customers,
            'orders' => $orders
        ];

        return $this->render($response, 'admin/orders/orderIndexView.php', $data);
    }


    public function error(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'errorView.php');
    }
}
