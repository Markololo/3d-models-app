<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

/**
 * Base model class for all models.
 *
 * This class provides a base implementation for all models with PDO wrapper methods.
 * It is intended to be extended by specific model classes.
 *
 * @example
 * class UserModel extends BaseModel {
 *     public function findById(int $id): array|false {
 *         return $this->selectOne('SELECT * FROM users WHERE id = ?', [$id]);
 *     }
 * }
 */
class CategoriesModel extends BaseModel
{
    private $categories_table = " categories";
    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo); //pass it to the parent class
    }


    //fetches the list of categories
    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->categories_table}";

        return  $this->selectAll($sql);
    }

    public function fetchCategoryById(int $id): mixed
    {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $category = $this->selectOne($sql, ["id" => $id]);

        return $category;
    }

    public function updateCategoryArray(int $id, array $category_info): int
    {
        return $this->execute(
            'UPDATE categories
         SET name = :name, description = :description
         WHERE id = :id',
            [
                'id' => $id,
                'name' => $category_info['category_name'],
                'description' => $category_info['description'],
            ]
        );
    }

    public function deleteCategory(int $id): int
    {
        $sql1 = "UPDATE products SET category_id = NULL
        WHERE category_id = :id";
        $sql2 = "DELETE FROM categories WHERE id = :id";
        // DELETE FROM categories WHERE id = :id
        $this->execute($sql1, ['id' => $id]);
        return $this->execute($sql2, ['id' => $id]);
    }

    public function createAndGetId(array $data): string
    {
        $name = $data['product_name'];
        $description = $data['product_description'] ?? "";

        $sql = "INSERT INTO categories (name, description, created_at)
         VALUES (:name, :description, current_timestamp())";

        $insert = $this->execute($sql, [
            "name"=>$name,
            "description"=>$description,
            ]);
        return $this->lastInsertId();
    }


    public function searchCategories(string $searchTerm = ''): array
    {
        $sql = "SELECT c.name, c.description
        FROM categories c WHERE 1 = 1";

        $params = [];

        if (!empty($searchTerm)) {
            $sql .= " AND (c.name LIKE :searchName OR p.description LIKE :searchDesc)";
            $params['searchName'] = "%$searchTerm%";
            $params['searchDesc'] = "%$searchTerm%";
        }

        $sql .= " GROUP BY c.id ORDER BY c.name ASC";
        return $this->selectAll($sql, $params);
    }



}
