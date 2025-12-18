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
         SET
         name = :name,
          description = :description,
          price = :price,
          stock_quantity = :stock_quantity,
         category_id = :category_id
         WHERE id = :id',
            [
                'id' => $id,
                'name' => $product_info['product_name'],
                'description' => $product_info['description'],
                'price' => $product_info['price'],
                'stock_quantity' => $product_info['quantity'],
                'category_id' => $product_info['category_id']

            ]
        );
    }

    //TODO rename paras and do ID
    public function insertProduct($a, $b, $c, $d)
    {
        // $sql = "INSERT INTO products(id, name, price, description) VALUES (:a, :b, :c, :d)";
        // return $this->execute($sql, ['id'=> $id, 'a'=>$a, 'b'=>$b, 'c'=>$c, 'd'=>$d]);
    }

    public function deleteProduct(int $id): int
    {
        $this->execute("DELETE FROM product_images WHERE product_id = :id", ['id' => $id]);
        return $this->execute("DELETE FROM products WHERE id = :id", ['id' => $id]);
    }

    public function createAndGetId(array $data): string
    {
        // TODO: 1. Execute INSERT query using $this->execute()
        //       - Insert: name, price, description, created_at
        //       - Use named parameters (:name, :price, etc.)
        $name = $data['product_name'];
        $category_id = $data['category_id'];
        $description = $data['product_description'] ?? "";
        $price = $data['product_price'];
        $stock_quantity = $data['stock_quantity'] ?? 0;

        $sql = "INSERT INTO `products` (category_id, name, description, price, stock_quantity, created_at)
         VALUES (:category_id, :name, :description, :price, :stock_quantity, current_timestamp())";

        $insert = $this->execute($sql, [
            "category_id" => $category_id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "stock_quantity" => $stock_quantity
        ]);
        //? 2. Return the last inserted ID using $this->lastInsertId()
        return $this->lastInsertId();
    }


    public function searchProducts(string $searchTerm = '', ?int $categoryId = null): array
    {
        // TODO: Create base SQL query with LEFT JOINs
        // - Join products (p) with categories (c) on category_id
        // - Join with product_images (pi) where is_primary = 1
        // - Select: p.id, p.name, p.description, p.price, p.stock_quantity,
        //          c.name AS category_name, c.id AS category_id, pi.file_path AS image_path
        // - Start with WHERE 1=1 to make adding conditions easier
        // $sql = "SELECT p.id,p.name, p.description,p.price,p.stock_quantity,c.name AS category_name, c.id AS category_id, pi.file_path AS image_path FROM products p LEFT JOIN categories c ON p.category_id = c.id
        // LEFT JOIN product_images pi ON p.id = pi.product_id
        // WHERE is_primary = 1";

        $sql = "SELECT p.id,p.name, p.description,p.price,p.stock_quantity,
        c.name AS category_name, c.id AS category_id, pi.file_path AS image_path FROM products p LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN product_images pi ON p.id = pi.product_id
    WHERE 1 = 1
    ";

        // TODO: Initialize empty params array
        $params = [];

        // TODO: If searchTerm is not empty:
        // - Add condition: (p.name LIKE :search OR p.description LIKE :search)
        // - Add to params: 'search' => '%' . $searchTerm . '%'

        if (!empty($searchTerm)) {
            $sql .= " AND (p.name LIKE :searchName OR p.description LIKE :searchDesc)";

            // $params = array('search' => '%' . $searchTerm . '%');

            // $sql .= " AND (p.name LIKE CONCAT ('%', :search,'%')
            //            OR p.description LIKE CONCAT ('%', :search,'%'))";

            $params['searchName'] = "%$searchTerm%";
            $params['searchDesc'] = "%$searchTerm%";
        }

        // TODO: If categoryId is provided and > 0:
        // - Add condition: p.category_id = :category_id
        // - Add to params: 'category_id' => $categoryId

        if (!empty($categoryId) && $categoryId > 0) {
            $sql .= " AND p.category_id = :category_id ";

            $params['category_id'] = $categoryId;
        }

        // TODO: Add GROUP BY p.id and ORDER BY p.name ASC
        $sql .= " GROUP BY p.id ORDER BY p.name ASC";

        // TODO: Return results using $this->selectAll($sql, $params)

        return $this->selectAll($sql, $params);
    }




    public function getAllProducts(): array
    {
        $sql = "SELECT id, name FROM products ORDER BY name ASC";
        return $this->selectAll($sql);
    }


    public function saveProductImage($filePath, $productId, $isPrimary = 1) //default is_primary true
    {

        $sql = "INSERT INTO product_images (product_id, file_path, is_primary) VALUES (:productId, :filePath, :isPrimary)";

        return $this->execute($sql, ["productId" => $productId, "filePath" => $filePath, "isPrimary" => $isPrimary]);
    }

    // public function getAllProductInfo($product_id)
    // {
    //     $sql = "SELECT p.*, pi.file_path, pi.is_primary, c.name
    //     FROM products p
    //     JOIN categories c ON c.id = p.category_id
    //     JOIN product_images pi ON pi.product_id = p.id
    //     WHERE p.id = :pId";

    //     return $this->selectOne($sql, ["pId"=>$product_id]);
    // }

    public function getFullProducts()
    {
        $sql = "SELECT
            p.*,
            c.name AS category_name,
            pi.file_path,
            pi.is_primary
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        LEFT JOIN product_images pi
            ON pi.product_id = p.id AND pi.is_primary = 1
        ORDER BY p.name ASC
    ";

        return $this->selectAll($sql);
    }

    public function getProductImages($product_id)
    {
        $sql = "SELECT *
        FROM product_images
        WHERE product_id = :pId";

        return $this->selectAll($sql, ["pId" => $product_id]);
    }

    public function reduceStock($productId, $quantity)
    {
        $this->execute(
            "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?",
            [$quantity, $productId]
        );
    }
}
