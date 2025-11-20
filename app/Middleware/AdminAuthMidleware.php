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
        //? Get authentication status and role from session
        $isAuthenticated = SessionManager::get('is_authenticated');
        $role = SessionManager::get('user_role');

        //? Check authentication:
        if(!$isAuthenticated) {
            //? show error:
            FlashMessage::error("Please log in to access the admin panel.");

            //? redirect to 'auth.login':

        } else {
            if($role != 'admin') {
                //? show error:
                FlashMessage::error("Access denied. Admin privileges required.");

                 //? redirect to 'user.dashboard' route:

            } else {
                return $handler->handle($request);
            }
        }
    }
}
