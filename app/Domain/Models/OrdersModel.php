<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class OrdersModel extends BaseModel
{
    // private $products_table = "products";

    public function __construct(PDOService $pdoService)
    {
        parent::__construct($pdoService); //pass it to the parent class
    }

    public function getActiveOrder(int $userId): array|false
    {
        return $this->selectOne(
            "SELECT * FROM orders WHERE user_id = ? AND status = 'pending' LIMIT 1",
            [$userId]
        );
    }

    public function createOrder(int $userId): int
    {
        $this->execute(
            "INSERT INTO orders (user_id, total, status, created_at) VALUES (?, 0, 'pending', NOW())",
            [$userId]
        );
        return (int) $this->lastInsertId();
    }

    public function addOrderItem(int $orderId, int $productId, int $quantity, float $unitPrice): void
    {
        $this->execute(
            "INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)",
            [$orderId, $productId, $quantity, $unitPrice]
        );
    }

    public function updateOrderTotal(int $orderId): void
    {
        $this->execute(
            "UPDATE orders
             SET total = (SELECT SUM(quantity * unit_price) FROM order_items WHERE order_id = ?)
             WHERE id = ?",
            [$orderId, $orderId]
        );
    }

    public function reduceProductStock(int $productId, int $quantity): void
    {
        $this->execute(
            "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?",
            [$quantity, $productId]
        );
    }
   
    public function getAllCustomers() : array
    {
        $sql = "SELECT * FROM users WHERE role = :role";
        return $this->selectAll($sql, ["role" => 'customer']) ?? [];
    }

    public function getAllOrders() : array
    {
        //Order ID, Customer Name or ID, Total Amount, Status, and Date Created
        $sql = "SELECT o.id, total, status, o.created_at, CONCAT(first_name,' ', last_name) AS username
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";
        return $this->selectAll($sql) ?? [];
    }

}
