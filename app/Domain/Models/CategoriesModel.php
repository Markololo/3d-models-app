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
        $category = $this->selectOne($sql, ["id" => $id]); //this = current object; this calls on this class and its parent

        return $category;
    }
}
