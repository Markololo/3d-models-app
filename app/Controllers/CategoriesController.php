<?php

namespace App\Controllers;

use App\Domain\Models\CategoriesModel;

use DI\Container;
use LDAP\Result;
use App\Domain\Models\ProductsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoriesController extends BaseController
{
    public function __construct(Container $container, private CategoriesModel $categories_model)
    {
        parent::__construct($container); //pass the container to the parent

    }

    // Signatures of controller methods: (callback methods)


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

        return $response;
    }
    public function edit(Request $request, Response $response, array $args): Response
    {

        return $response;
    }
    public function update(Request $request, Response $response, array $args): Response
    {

        return $response;
    }
    public function delete(Request $request, Response $response, array $args): Response
    {
        $category_id = (int) $args['category_id'];

        $this->categories_model->deleteCategory($category_id);

        return $this->redirect($request, $response, 'categories.index', ['id' => $category_id]);
    }
}
