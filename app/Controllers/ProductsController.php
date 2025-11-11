<?php

namespace App\Controllers;

use App\Domain\Models\CategoriesModel;
use App\Domain\Models\ProductsModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class ProductsController extends BaseController
{
    public function __construct(
        Container $container,
        private ProductsModel $products_model,
        private CategoriesModel $categories_model
    ) {

        parent::__construct($container); //pass the container to the parent
    }

    //*GET admin/products
    /**
     *  Display a list of items.
     * @param \Psr\Http\Message\ServerRequestInterface $request HTTP request
     * @param \Psr\Http\Message\ResponseInterface $response HTTP response
     * @param array $args
     * @return Response
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $products = $this->products_model->fetchProducts();
        $data['data'] = [
            'page_title' => 'List of products',
            'message' => 'Welcome to the home page',
            'products' => $products
        ];
        // $data["page_title"] = "Browse Products";
        // $data["products"] = $products;
        return $this->render($response, 'admin/products/productsIndexView.php', $data);
    }

    /**
     * Show details of a single item.
     * @return void
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        return $response;
    }

    /**
     *  Display a form to create a new item.
     * @return void
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        return $response;
    }

    /**
     * Save a new item to the database.
     * @return void
     */
    public function store(Request $request, Response $response, array $args): Response
    {
        return $response;
    }

    /**
     * Display a form to edit an item.
     * @return void
     */
    public function edit(Request $request, Response $response, array $args): Response
    {
        //* 1) Get the id of the product from the query string params of the URI
        $product_id = $args["product_id"];
        // dd("Editing product: " . $product_id["id"]);

        //* 2)  Pull the existing item identified by the received ID from the db.
        $product = $this->products_model->fetchProductById($product_id);
        // dd($product);
        $categories = $this->categories_model->getAll();        //* 3) Pass it to the view where the update/editing form filled with the item info will be rendered
        $data = [
            'page_title' => 'Edit Page',
            'product' => $product,
            'categories' => $categories

        ];
        return $this->render($response, 'admin/products/productsEditView.php', $data);
    }

    /**
     * Save changes to an item.
     * @return void
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        //  dd($args);

        $productId = (int) $args['product_id'];
        // $product_info = $request->getParsedBody();
        // dd($product_info);
        $this->products_model->updateProductArray($productId, $request->getParsedBody());

        // add flash messages to be shown to the user in master list
        // <?= App\Helpers\FlashMessage::render()
        // return $this->redirect($request, $response, 'products.index');

        return $this->redirect($request, $response, 'product.index', ['id' => $productId]);
    }

    /**
     * Remove an item.
     * @return void
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $response;
    }


    public function error(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'errorView.php');
    }
}
