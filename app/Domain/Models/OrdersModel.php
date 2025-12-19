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
        // calculate total from order_items
        $total = $this->selectOne(
            "SELECT SUM(quantity * unit_price) AS total FROM order_items WHERE order_id = ?",
            [$orderId]
        );

        // if no items, set 0
        $orderTotal = $total['total'] ?? 0;

        // update the orders table
        $this->execute(
            "UPDATE orders SET total = ? WHERE id = ?",
            [$orderTotal, $orderId]
        );
    }

    public function reduceProductStock(int $productId, int $quantity): void
    {
        $this->execute(
            "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?",
            [$quantity, $productId]
        );
    }

    public function getAllCustomers(): array
    {
        $sql = "SELECT * FROM users WHERE role = :role";
        return $this->selectAll($sql, ["role" => 'customer']) ?? [];
    }

    public function getAllOrders(): array
    {
        //order ID, Customer Name or ID, Total Amount, Status, and Date Created
        $sql = "SELECT o.id, total, status, o.created_at, CONCAT(first_name,' ', last_name) AS username
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";
        return $this->selectAll($sql) ?? [];
    }


    //get active order
    public function getActiveOrderItems(int $userId): array
    {
        return $this->selectAll(
            "SELECT
            oi.product_id,
            oi.quantity,
            oi.unit_price,
            p.name,
            p.stock_quantity
         FROM orders o
         JOIN order_items oi ON oi.order_id = o.id
         JOIN products p ON p.id = oi.product_id
         WHERE o.user_id = ?
           AND o.status = 'pending'",
            [$userId]
        );
    }


    //check if product already exists in order
    public function getOrderItem(int $orderId, int $productId): array|false
    {
        return $this->selectOne(
            "SELECT * FROM order_items
         WHERE order_id = ? AND product_id = ?",
            [$orderId, $productId]
        );
    }

    // increase qty without creating same rows
    public function increaseItemQty(int $orderId, int $productId): void
    {
        $this->execute(
            "UPDATE order_items
         SET quantity = quantity + 1
         WHERE order_id = ? AND product_id = ?",
            [$orderId, $productId]
        );
    }

    //decrease qty never below 1
    public function decreaseItemQty(int $orderId, int $productId): void
    {
        $this->execute(
            "UPDATE order_items
         SET quantity = quantity - 1
         WHERE order_id = ?
           AND product_id = ?
           AND quantity > 1",
            [$orderId, $productId]
        );
    }

    //remove item if 0
    public function removeOrderItem(int $orderId, int $productId): void
    {
        $this->execute(
            "DELETE FROM order_items
         WHERE order_id = ? AND product_id = ?",
            [$orderId, $productId]
        );
    }

    public function getOrderItemsWithProduct(int $orderId): array
    {
        return $this->selectAll(
            "SELECT
             oi.product_id,
            oi.quantity,
            oi.unit_price,
            p.name,
            p.stock_quantity,
            pi.file_path AS image_path
         FROM order_items oi
         JOIN products p ON oi.product_id = p.id
         LEFT JOIN product_images pi
            ON pi.product_id = p.id AND pi.is_primary = 1
         WHERE oi.order_id = ?",
            [$orderId]
        ) ?? [];
    }


    //restock





}
