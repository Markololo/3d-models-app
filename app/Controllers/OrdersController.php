<?php

namespace App\Controllers;

use App\Domain\Models\OrdersModel;
use App\Domain\Models\ProductsModel;
use App\Domain\Models\UserModel;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use LDAP\Result;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OrdersController extends BaseController
{
    public function __construct(Container $container, private UserModel $user_model, private ProductsModel $products_model, private OrdersModel $orders_model) //then add model param
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $customers = $this->user_model->getAllCustomers();
        $orders;
        $data = [
            'page_title' => 'Admin Dashboard',
            'customers' => $customers
        ];

        return $this->render($response, '#', $data);
    }


    public function error(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'errorView.php');
    }





    public function addToCart(Request $request, Response $response, array $args): Response
    {
        $userId = SessionManager::get('user_id'); // logged-in user
        $productId = (int)$args['product_id'];
        $quantity = 1; // default

        // fetch product details
        $product = $this->products_model->fetchProductById($productId);
        if (!$product || $product['stock_quantity'] < $quantity) {

            FlashMessage::error("Product out of stock.");
            return $this->redirect($request, $response, '/user/products');
        }

        // get or create active order
        $order = $this->orders_model->getActiveOrder($userId);
        $orderId = $order ? (int)$order['id'] : $this->orders_model->createOrder($userId);

        // add item and reduce stock
        $this->orders_model->addOrderItem($orderId, $productId, $quantity, $product['price']);
        $this->orders_model->reduceProductStock($productId, $quantity);

        // update order total
        $this->orders_model->updateOrderTotal($orderId);

        return $response
            ->withHeader('Location', APP_BASE_URL . '/user/products')
            ->withStatus(302);
    }
}
