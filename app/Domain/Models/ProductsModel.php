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

    public function searchCafes($keyword, $filter): mixed
    {
        echo "searchCafes method in ProductsModel.php";
        return [];
    }
}
