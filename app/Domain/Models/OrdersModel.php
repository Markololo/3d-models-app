<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class OrdersModel extends BaseModel
{
    public function __construct(PDOService $pdoService)
    {
        parent::__construct($pdoService);
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
