<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class ProductsModel extends BaseModel
{
    // private $products_table = "products";

    public function __construct(PDOService $pdoService)
    {
        parent::__construct($pdoService); //pass it to the parent class
    }

    // Now, need a public method that returns the list of shops
    public function fetchAllQuery($q): mixed
    {
        $sql = $q;
        $products = $this->selectAll($sql); //this = current object; this calls on this class and its parent
        return $products;
    }

    public function fetchProducts(): mixed
    {
        // $sql = "SELECT * FROM {$this->$products_table}";
        $sql = "SELECT * FROM products";
        $products = $this->selectAll($sql); //this = current object; this calls on this class and its parent
        return $products;
    }

    public function fetchProductById(int $id): mixed
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $product = $this->selectOne($sql, ["id" => $id]); //this = current object; this calls on this class and its parent

        return $product;
    }
    public function updateProductArray(int $id, array $product_info): int
    {
        //WRITE THE UPDATE QUERY
        return $this->execute(
            'UPDATE products
         SET name = :name, description = :description, price = :price, stock_quantity = :stock_quantity
         WHERE id = :id',
            [
                'id' => $id,
                'name' => $product_info['product_name'],
                'description' => $product_info['description'],
                'price' => $product_info['price'],
                'stock_quantity' => $product_info['quantity'],
                // 'category_id' => $product_info['category_id']

            ]
        );
    }

    public function deleteProduct(int $id): int
    {
        $sql = "DELETE FROM products WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }

    public function searchCafes($keyword, $filter): mixed
    {
        echo "searchCafes method in ProductsModel.php";
        return [];
    }

    public function createAndGetId(array $data): string
    {
        // TODO: 1. Execute INSERT query using $this->execute()
        //       - Insert: name, price, description, created_at
        //       - Use named parameters (:name, :price, etc.)

        //? Use $this->execute() for INSERT queries.
        //? Use named parameters for security: :name, :price, :description.
        //? Use date('Y-m-d H:i:s') for the created_at timestamp.
        //? Use $data['field_name'] ?? '' for optional fields.
        //? Use $this->lastInsertId() to get the ID of the inserted record.
        // TODO: 2. Return the last inserted ID using $this->lastInsertId()
        return '111';
    }
}
