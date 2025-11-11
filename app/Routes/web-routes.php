<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\ProductsController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return static function (Slim\App $app): void {

    //* Name the routes (setName('')) to help with redirection later
    //? Admin routes group:
    //* Base URI: localhost/3d-models-app/admin
    $app->group('/admin', function ($group) {
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
            '/products/edit/{product_id}',
            [ProductsController::class, 'edit']
        );

        $group->get(
            '/products/delete/{product_id}',
            [ProductsController::class, 'delete']
        );

        $group->post(
            '/products/update/{product_id}',
            [ProductsController::class, 'update']
        );
        // handle saving edit product info
        // $group->post(
        //     '/products/update',
        //     [ProductsController::class, 'update']
        // );
        $group->get(
            '/categories',
            [ProductsController::class, 'index']
        )->setName('categories.index');
    });


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
};
