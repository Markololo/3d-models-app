<?php

namespace App\Middleware;

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class AdminAuthMiddleware implements MiddlewareInterface
{
    /**
     * Process the request - check if user is authenticated AND is an admin.
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        // TODO: Get authentication status using SessionManager::get('is_authenticated')
        // TODO: Get user role using SessionManager::get('user_role')

        // TODO: If NOT authenticated:
        //       - Use FlashMessage::error() with message: "Please log in to access the admin panel."
        //       - Redirect to 'auth.login' (same pattern as AuthMiddleware)

        // TODO: If authenticated but role is NOT 'admin':
        //       - Use FlashMessage::error() with message: "Access denied. Admin privileges required."
        //       - Redirect to 'user.dashboard' route

        // If authenticated AND admin, continue to admin route
        // TODO: Return $handler->handle($request);
    }
}
