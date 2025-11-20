<?php

namespace App\Middleware;

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Process the request - check if user is authenticated.
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        // TODO: Check if user is authenticated using SessionManager::get('is_authenticated')
        //       Store the result in $isAuthenticated variable

        // TODO: If NOT authenticated:
        //       - Use FlashMessage::error() with message: "Please log in to access this page."
        //       - Get RouteParser: $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        //       - Generate login URL: $loginUrl = $routeParser->urlFor('auth.login');
        //       - Create redirect response:
        //         $response = new \Slim\Psr7\Response();
        //         return $response->withHeader('Location', $loginUrl)->withStatus(302);

        // If authenticated, continue to the next middleware/route handler
        // TODO: Return $handler->handle($request);
    }
}
