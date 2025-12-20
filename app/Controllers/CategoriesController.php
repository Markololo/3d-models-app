<?php

namespace App\Controllers;

use App\Domain\Models\CategoriesModel;

use DI\Container;
use LDAP\Result;
use App\Domain\Models\ProductsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\FlashMessage;
use Exception;

class CategoriesController extends BaseController
{
    public function __construct(Container $container, private CategoriesModel $categories_model)
    {
        parent::__construct($container);
    }



    public function index(Request $request, Response $response, array $args): Response
    {

        $categories = $this->categories_model->getAll();
        $data['data'] = [
            'page_title' => 'List of categories',

            'title' => 'List of Categories',
            'message' => 'Welcome to the home page',
            'categories' => $categories
        ];
        //     return $this->render($response, '<admin>
        // <categories>categoriesIndexView.php', $data);
        return $this->render($response, 'admin/categories/categoriesIndexView.php', $data);
    }


    public function show(Request $request, Response $response, array $args): Response
    {

        $category_id = $args["category_id"];
        // dd("Editing product: " . $product_id["id"]);

        $product = $this->categories_model->fetchCategoryById($category_id);
        $categories = $this->categories_model->getAll();
        $data = [
            'page_title' => 'Edit Page',
            'product' => $product,
            'categories' => $categories

        ];
        return $this->render($response, 'admin/categories/categoriesIndexView.php', $data);
    }
    public function create(Request $request, Response $response, array $args): Response
    {
        $data = [
            'page_title' => 'Category Creation Page',
        ];
        return $this->render($response, 'admin/categories/categoriesCreateView.php', $data);
    }

    /* For category creation */
    public function store(Request $request, Response $response, array $args): Response
    {

        $data = $request->getParsedBody();
        $errors = [];

        $name = $data["category_name"];
        $description = $data['category_description'];

        if (empty($name)) {
            $errors[] = "Please enter the category name.";
        }

        if (empty($description)) {
            $errors[] = "Please enter the category description.";
        }

        if (empty($errors)) {
            try {
                $category_id = $this->categories_model->createAndGetId($data);
            } catch (Exception $e) {
                FlashMessage::error("This category already exists. Choose another name.");
                return $this->redirect($request, $response, 'categories.create');
            }
            FlashMessage::success("Category Created Successfully!");
            // return $this->redirect($request, $response, 'products/show'.$product_id);
            return $this->redirect($request, $response, 'categories.index', ['category_id' => $category_id]);
        } else {
            FlashMessage::error($errors[0]);
            return $this->redirect($request, $response, 'categories.create');
        }
    }


    public function edit(Request $request, Response $response, array $args): Response
    {
        $category_id = $args["category_id"];
        $category = $this->categories_model->fetchCategoryById($category_id);

        $data = [
            'page_title' => 'Edit Page',
            'category' => $category,
        ];
        return $this->render($response, 'admin/categories/categoriesEditView.php', $data);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $category_id = (int) $args['category_id'];
        $data = $request->getParsedBody();
        $data['category_id'] = $category_id;

        $category = $this->categories_model->fetchCategoryById($category_id);

        if (empty($data["category_name"])) {
            FlashMessage::error("Enter category name!");
            return $this->redirect($request, $response, 'categories.edit', ['category_id' => $category_id]);
        }

        if (empty($data["category_description"])) {
            // FlashMessage::error("Enter category description!");
            // return $this->redirect($request, $response, 'categories.edit', ['category_id' => $category_id]);
            $data["category_description"] = $category["description"];
        }

        $this->categories_model->updateCategory($data);

        return $this->redirect($request, $response, 'categories.index', ['id' => $category_id]);
    }


    // public function update(Request $request, Response $response, array $args): Response
    // {
    //     $category_id = (int) $args['category_id'];
    //     $data = $request->getParsedBody();
    //     $data['category_id'] = $category_id;

    //     if (empty($name)) {
    //         $errors[] = "Please enter the category name.";
    //     }

    //     if (empty($description)) {
    //         $errors[] = "Please enter the category description.";
    //     }

    //     if (empty($errors)) {
    //         try {
    //         $this->categories_model->updateCategory($data);
    //     } catch (Exception $e) {
    //         FlashMessage::error("Db error try again later.");
    //         return $this->redirect($request, $response, 'categories.edit');
    //     }
    //         FlashMessage::success("Category Created Successfully!");
    //         // return $this->redirect($request, $response, 'products/show'.$product_id);
    //         return $this->redirect($request, $response, 'categories.index', ['category_id' => $category_id]);
    //     } else {
    //         FlashMessage::error($errors[0]);
    //         return $this->redirect($request, $response, 'categories.edit');
    //     }
    //     // return $this->redirect($request, $response, 'categories.index', ['id' => $category_id]);
    // }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $category_id = (int) $args['category_id'];

        $this->categories_model->deleteCategory($category_id);

        return $this->redirect($request, $response, 'categories.index', ['id' => $category_id]);
    }
}
