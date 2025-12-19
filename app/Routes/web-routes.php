<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Controllers\AuthController;
use App\Controllers\CategoriesController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\OrdersController;
use App\Controllers\ProductsController;
use App\Controllers\UploadController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminAuthMiddleware;
use App\Controllers\TwoFactorController;
use App\Controllers\SettingsController;

use App\Controllers\UsersController;
use App\Middleware\TwoFactorMiddleware;

return static function (Slim\App $app): void {


    //* Name the routes (setName('')) to help with redirection later
    //? Admin routes group:
    //* Base URI: localhost/3d-models-app/admin
    $app->group(
        '/admin',
        function ($group) {

            $group->get(
                '/dashboard',
                [DashboardController::class, 'index']
            )->setName('dashboard.index')
                ->add(TwoFactorMiddleware::class)
                ->add(AuthMiddleware::class);


            $group->get(
                '/products',
                [ProductsController::class, 'index']
            )->setName('product.index');

            $group->get(
                '/products/create',
                [ProductsController::class, 'create']
            )->setName('products.create');

            // $group->get('/products/upload', [ProductsController::class, 'create'])->setName('product.upload.index');
            // $group->post('/products/upload', [ProductsController::class, 'upload'])->setName('product.upload.process');
            $group->post('/products/{product_id}/upload', [ProductsController::class, 'upload'])->setName('products.upload');


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
            )->setName('products.show');

            $group->post(
                '/products/update/{product_id}',
                [ProductsController::class, 'update']
            );

            $group->post(
                '/products',
                [ProductsController::class, 'store']
            );

            $group->get(
                '/categories',
                [CategoriesController::class, 'index']
            )->setName('categories.index');

            $group->get(
                '/categories/create',
                [CategoriesController::class, 'create']
            )->setName('categories.create');

            $group->get(
                '/categories/edit/{category_id}',
                [CategoriesController::class, 'edit']
            );

            $group->get(
                '/categories/delete/{category_id}',
                [CategoriesController::class, 'delete']
            );

            $group->post(
                '/categories/update/{category_id}',
                [CategoriesController::class, 'update']
            );

            $group->post(
                '/categories',
                [CategoriesController::class, 'store']
            );

            $group->get(
                '/customers',
                [UsersController::class, 'adminIndex']
            )->setName('categories.index');

            $group->get(
                '/orders',
                [OrdersController::class, 'adminIndex']
            )->setName('orders.index');
        }
    );


    // clients group
    $app->group(
        '/user',
        function ($group) {
            $group->get(
                '/',
                [UsersController::class, 'index']
            )->setName('user.index');


            $group->post('/cart/add/{product_id}', [OrdersController::class, 'addToCart'])->setName('user.cart.add');
            $group->get('/cart', [OrdersController::class, 'cartView'])->setName('user.cart');

            $group->get(
                '/products',
                [UsersController::class, 'products']
            )->setName('user.products');
            $group->get(
                '/products/{id}',
                [UsersController::class, 'show']
            )->setName('user.products.show');

            $group->get(
                '/settings',
                [SettingsController::class, 'index']
            )->setName('user.settings');
        }
    )
        ->add(TwoFactorMiddleware::class)
        ->add(AuthMiddleware::class);





    //* NOTE: Route naming pattern: [controller_name].[method_name]
    $app->get('/', [AuthController::class, 'login'])->setName('auth.login');


    // $app->get('/home', [HomeController::class, 'index'])
    //     ->setName('home.index');

    // $app->get('/dashboard', [DashboardController::class, 'index'])
    //     ->setName('dashboard.index');


    // A route to test runtime error handling and custom exceptions.
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });

    //TODO File Uploads:
    // File upload routes
    // $app->get('/upload', [UploadController::class, 'index'])->setName('upload.index');
    // $app->post('/upload', [UploadController::class, 'upload'])->setName('upload.process');

    // $app->group('/auth', function ($group) {
    //     $group->get('/register', [AuthController::class, 'register'])->setName('auth.register');
    //     $group->post('/register', [AuthController::class, 'store']);

    //     // TEMPORARY route for testing
    //     $group->post('/login', [HomeController::class, 'index'])->setName('auth.login');
    // });

    //*--------------Login and Authentication-------------------
    $app->get('/register', [AuthController::class, 'register'])->setName('auth.register');
    $app->post('/register', [AuthController::class, 'store'])->setName('auth.store');

    $app->get('/login', [AuthController::class, 'login'])->setName('auth.login');
    $app->post('/login', [AuthController::class, 'authenticate']);
    $app->post('/logout', [AuthController::class, 'logout'])->setName('auth.logout');

    // $app->get('/dashboard', [AuthController::class, 'dashboard'])
    //     ->setName('user.dashboard')
    //     ->add(AuthMiddleware::class);
    $app->get('/dashboard', [AuthController::class, 'dashboard'])
        ->setName('dashboard')
        ->add(TwoFactorMiddleware::class)
        ->add(AuthMiddleware::class);


    /*
    GET /login → Displays the login form
    POST /login → Processes the login form submission
    GET /logout → Logs out the user and destroys the session

    Example:
    $app->get('/dashboard', [AuthController::class, 'dashboard'])
    ->setName('user.dashboard')
    ->add(AuthMiddleware::class);
    / */

    $app->get('/api/products/search', [ProductsController::class, 'search'])
        ->setName('api.products.search');

    //---------------------------------------------------------------------------------------
    // 2FA Setup routes (requires auth, but not 2FA verification)
    // $app->get('/2fa/setup', [TwoFactorController::class, 'showSetup'])
    //     ->setName('2fa.setup')
    //     ->add(AuthMiddleware::class);

    // $app->post('/2fa/verify-and-enable', [TwoFactorController::class, 'verifyAndEnable'])
    //     ->setName('2fa.enable')
    //     ->add(AuthMiddleware::class);

    // // 2FA Verification during login
    // $app->get('/2fa/verify', [TwoFactorController::class, 'showVerify'])
    //     ->setName('2fa.verify')
    //     ->add(AuthMiddleware::class);

    // $app->post('/2fa/verify', [TwoFactorController::class, 'verify'])
    //     ->setName('2fa.verify.post')
    //     ->add(AuthMiddleware::class);

    // // 2FA Disable (requires full auth including 2FA)
    // $app->get('/2fa/disable', [TwoFactorController::class, 'showDisable'])
    //     ->setName('2fa.disable.show')
    //     ->add(TwoFactorMiddleware::class)
    //     ->add(AuthMiddleware::class);

    // $app->post('/2fa/disable', [TwoFactorController::class, 'disable'])
    //     ->setName('2fa.disable')
    //     ->add(TwoFactorMiddleware::class)
    //     ->add(AuthMiddleware::class);
    // 2FA Setup routes (requires auth, but not 2FA verification)
    $app->get('/2fa/setup', [TwoFactorController::class, 'showSetup'])
        ->setName('2fa.setup')
        ->add(AuthMiddleware::class);

    $app->post('/2fa/verify-and-enable', [TwoFactorController::class, 'verifyAndEnable'])
        ->setName('2fa.enable')
        ->add(AuthMiddleware::class);

    // 2FA Verification during login
    $app->get('/2fa/verify', [TwoFactorController::class, 'showVerify'])
        ->setName('2fa.verify')
        ->add(AuthMiddleware::class);

    $app->post('/2fa/verify', [TwoFactorController::class, 'verify'])
        ->setName('2fa.verify.post')
        ->add(AuthMiddleware::class);

    // 2FA Disable (requires full auth including 2FA)
    $app->get('/2fa/disable', [TwoFactorController::class, 'showDisable'])
        ->setName('2fa.disable.show')
        ->add(TwoFactorMiddleware::class)
        ->add(AuthMiddleware::class);

    $app->post('/2fa/disable', [TwoFactorController::class, 'disable'])
        ->setName('2fa.disable')
        ->add(TwoFactorMiddleware::class)
        ->add(AuthMiddleware::class);
};
