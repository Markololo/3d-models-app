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

    public function adminIndex(Request $request, Response $response, array $args): Response
    {
        //Order ID, Customer Name or ID, Total Amount, Status, and Date Created
        $customers = $this->orders_model->getAllCustomers();
        $orders = $this->orders_model->getAllOrders();
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





    public function addToCart(Request $request, Response $response, array $args): Response
    {
        $userId = SessionManager::get('user_id'); // logged-in user
        $productId = (int)$args['product_id'];
        $quantity = 1; // default

        $product = $this->products_model->fetchProductById($productId);

        // fetch product details
        if (!$product || $product['stock_quantity'] < 1) {
            FlashMessage::error("Out of stock.");
            return $response
                ->withHeader('Location', APP_BASE_URL . '/user/products/' . $productId)
                ->withStatus(302);
        }


        // get or create active order
        $order = $this->orders_model->getActiveOrder($userId);
        $orderId = $order ? (int)$order['id'] : $this->orders_model->createOrder($userId);

        //get item ]

        $items = $order
            ? $this->orders_model->getOrderItemsWithProduct($order['id'])
            : [];
        if ($items) {
            $this->orders_model->increaseItemQty($orderId, $productId);
        } else {
            $this->orders_model->addOrderItem($orderId, $productId, 1, $product['price']);
        }
        // add item and reduce stock
        $this->orders_model->reduceProductStock($productId, 1);
        $this->orders_model->updateOrderTotal($orderId);

        // update order total
        $this->orders_model->updateOrderTotal($orderId);

        return $response
            ->withHeader('Location', APP_BASE_URL . '/user/products' . '/' . $productId)
            ->withStatus(302);
    }

    public function cartView(Request $request, Response $response): Response
    {
        $userId = SessionManager::get('user_id');

        $order = $this->orders_model->getActiveOrderItems($userId);

        return $this->render($response, 'user/userMyOrderView.php', [
            'page_title' => 'My Order',
            'order' => $order
        ]);
    }


    public function increaseQty(Request $request, Response $response, array $args): Response
    {
        $userId = SessionManager::get('user_id');
        $productId = (int)$args['product_id'];

        $product = $this->products_model->fetchProductById($productId);

        if ($product['stock_quantity'] <= 0) {
            FlashMessage::error("No more stock available.");
            return $this->redirect($request, $response, '/user/cart');
        }

        $order = $this->orders_model->getActiveOrder($userId);

        $this->orders_model->increaseItemQty($order['id'], $productId);
        $this->orders_model->reduceProductStock($productId, 1);
        $this->orders_model->updateOrderTotal($order['id']);

        return $this->redirect($request, $response, '/user/cart');
    }

    public function decreaseQty(Request $request, Response $response, array $args): Response
    {
        $userId = SessionManager::get('user_id');
        $productId = (int)$args['product_id'];

        $order = $this->orders_model->getActiveOrder($userId);

        $this->orders_model->decreaseItemQty($order['id'], $productId);
        $this->products_model->increaseProductStock($productId, 1);
        $this->orders_model->updateOrderTotal($order['id']);

        return $this->redirect($request, $response, '/user/cart');
    }
}
