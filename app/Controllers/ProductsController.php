<?php
//?     Administrators should be able to:
//?     ▪ View lists of all existing products and categories with relevant details.
//?     ▪ Create new products and categories, assigning each product to a category.
//?     ▪ Edit existing items to update their information, such as product prices or descriptions, etc.
//?     ▪ Delete products or categories that are no longer needed.
//?     ▪ Each product must belong to a category to ensure that the store’s inventory remains organized.
//?     ▪ Note: Manage products and categories separately, each with its own controllers, models, and views

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
        $product_id = $args["product_id"];
        // dd("Editing product: " . $product_id["id"]);

        $product = $this->products_model->fetchProductById($product_id);
        $categories = $this->categories_model->getAll();
        $data = [
            'page_title' => 'Edit Page',
            'product' => $product,
            'categories' => $categories

        ];
        return $this->render($response, 'admin/products/productsShowView.php', $data);
    }
    /**
     *  Display a form to create a new item.
     * @return void
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        $categories = $this->categories_model->getAll();
        $data = [
            'page_title' => 'Product Creation Page',
            'categories' => $categories
        ];
        return $this->render($response, 'admin/products/productsCreateView.php', $data);
    }

    /**
     * Save a new item to the database.
     * @return void
     */
    public function store(Request $request, Response $response, array $args): Response
    {
        //? Extract the form data from the request.
        //? Validate the required fields (e.g., name, price).
        //? If validation fails → redirect back to the creation form.
        //? If validation passes → save the data to database using the model.
        //? Get the ID of the newly created item.
        //? Redirect to the newly created item’s detail page (PRG pattern).
        $data = $request->getParsedBody();
        $name = $data["product_name"];
        $price = $data["product_price"];
        $category_id = $data["category_id"];

        $this->products_model->createAndGetId($name, $price, $category_id);

        // echo dd($data);
        return $this->redirect($request, $response, 'product.index');
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
        $productId = (int) $args['product_id'];
        $this->products_model->updateProductArray($productId, $request->getParsedBody());

        return $this->redirect($request, $response, 'product.index', ['id' => $productId]);
    }

    /**
     * Remove an item.
     * @return void
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        // TODO: Validate the ID (optional but recommended)

        $productId = (int) $args['product_id'];
        // $product_info = $request->getParsedBody();
        // dd($product_info);
        $this->products_model->deleteProduct($productId);

        // add flash messages to be shown to the user in master list
        // <?= App\Helpers\FlashMessage::render()
        // return $this->redirect($request, $response, 'products.index');

        return $this->redirect($request, $response, 'product.index', ['id' => $productId]);
    }

    public function error(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'errorView.php');
    }


    public function search(Request $request, Response $response, array $args): Response
    {
        // TODO: Extract query parameters using $request->getQueryParams()
        // - Get 'q' parameter, trim it, default to empty string if not set
        // - Get 'category' parameter, convert to int if set, otherwise null


        $queryParams = $request->getQueryParams();

        $searchTerm = trim($queryParams['q'] ?? '');

        $categoryId =  isset($queryParams['category']) ? (int)$queryParams['category'] : null;


        if (strlen($searchTerm) > 100) {
            $searchTerm = substr($searchTerm, 0, 100);
        }

        // TODO: Call $this->model->searchProducts() with search term and category ID
        $products =  $this->products_model->searchProducts($searchTerm, $categoryId);

        // TODO: Create response data array with these keys:
        // - 'success' => true
        // - 'count' => count of products
        // - 'query' => the search term
        // - 'category_id' => the category ID
        // - 'products' => the products array

        $responseData = [
            'success' =>  true,
            'count' => count($products),
            'query' => $searchTerm,
            'category_id' => $categoryId,
            'products' => $products
        ];

        // TODO: Convert response data to JSON and write to response body
        // @see: https://www.slimframework.com/docs/v4/objects/response.html#returning-json
        // - Use json_encode()
        // - Use $response->getBody()->write()

        $payload = json_encode($responseData);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);

        // TODO: Return response with proper headers
        // - Set Content-Type: application/json
        // - Set status code 200


    }
}
