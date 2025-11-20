<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Controllers\AuthController;
use App\Controllers\CategoriesController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\ProductsController;
use App\Controllers\UploadController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminAuthMiddleware;


return static function (Slim\App $app): void {


    //* Name the routes (setName('')) to help with redirection later
    //? Admin routes group:
    //* Base URI: localhost/3d-models-app/admin
    $app->group(
        '/admin',
        function ($group) {
            //Add/register admin routes
            $group->get(
                '/dashboard',
                [DashboardController::class, 'index']
            )->setName('dashboard.index');


            $group->get(
                '/products',
                [ProductsController::class, 'index']
            )->setName('product.index');

            $group->get(
                '/products/create',
                [ProductsController::class, 'create']
            )->setName('products.create');

            $group->get(
                '/products/edit/{product_id}',
                [ProductsController::class, 'edit']
            );

            $group->get(
                '/products/delete/{product_id}',
                [ProductsController::class, 'delete']
            );

            $group->get(
                '/products/show/{product_id}',
                [ProductsController::class, 'show']
            );

            $group->post(
                '/products/update/{product_id}',
                [ProductsController::class, 'update']
            );

            $group->post(
                '/products',
                [ProductsController::class, 'store']
            );
            // handle saving edit product info
            // $group->post(
            //     '/products/update',
            //     [ProductsController::class, 'update']
            // );
            $group->get(
                '/categories',
                [CategoriesController::class, 'index']
            )->setName('categories.index');
        }
    );


    //* NOTE: Route naming pattern: [controller_name].[method_name]
    $app->get('/', [HomeController::class, 'index'])
        ->setName('home.index');

    $app->get('/home', [HomeController::class, 'index'])
        ->setName('home.index');

    // $app->get('/dashboard', [DashboardController::class, 'index'])
    //     ->setName('dashboard.index');


    // A route to test runtime error handling and custom exceptions.
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });

    //? File Uploads:
    // File upload routes
    $app->get('/upload', [UploadController::class, 'index'])->setName('upload.index');
    $app->post('/upload', [UploadController::class, 'upload'])->setName('upload.process');

    // $app->group('/auth', function ($group) {
    //     $group->get('/register', [AuthController::class, 'register'])->setName('auth.register');
    //     $group->post('/register', [AuthController::class, 'store']);

    //     // TEMPORARY route for testing
    //     $group->post('/login', [HomeController::class, 'index'])->setName('auth.login');
    // });

    //*----------------------------------Login and Authentication---------------------------------------------------
    $app->get('/register', [AuthController::class, 'register'])->setName('auth.register');
    $app->post('/register', [AuthController::class, 'store'])->setName('auth.store');

    //! TEMPORARY route for testing
    // $app->post('/login', [HomeController::class, 'index'])->setName('auth.login');
    $app->get('/login', [HomeController::class, 'index'])->setName('auth.login');

    // Public routes (no authentication required)
    // TODO: Create a GET route for '/login' that maps to AuthController::class 'login' method
    //       Set the route name to 'auth.login'

    // TODO: Create a POST route for '/login' that maps to AuthController::class 'authenticate' method

    // TODO: Create a GET route for '/logout' that maps to AuthController::class 'logout' method
    //       Set the route name to 'auth.logout'
    /*
    GET /login → Displays the login form
    POST /login → Processes the login form submission
    GET /logout → Logs out the user and destroys the session

    Example:
    $app->get('/dashboard', [AuthController::class, 'dashboard'])
    ->setName('user.dashboard')
    ->add(AuthMiddleware::class);
    / */
};
