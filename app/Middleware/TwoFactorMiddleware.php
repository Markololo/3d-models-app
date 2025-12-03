<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Domain\Models\TwoFactorAuth;
use App\Helpers\SessionManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Routing\RouteContext;

/**
 * Middleware to check if user needs to verify 2FA.
 *
 * This middleware runs AFTER AuthMiddleware. It checks:
 * 1. Is the user authenticated?
 * 2. Does the user have 2FA enabled?
 * 3. Has the user already verified 2FA in this session?
 *
 * If 2FA is required but not verified, redirect to /2fa/verify
 */
class TwoFactorMiddleware implements MiddlewareInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Check if user is authenticated
        if (!SessionManager::get('is_authenticated')) {
            // Not authenticated, let AuthMiddleware handle it
            return $handler->handle($request);
        }

        $userId = SessionManager::get('user_id');

        // TODO: Check if user has 2FA enabled
        // HINT: Use TwoFactorAuth model's isEnabled() method
        $twoFactorModel = $this->container->get(TwoFactorAuth::class);
        $has2FAEnabled = false; // Replace with: $twoFactorModel->isEnabled($userId);

        // If 2FA is not enabled, proceed normally
        if (!$has2FAEnabled) {
            return $handler->handle($request);
        }

        // TODO: Check if 2FA has already been verified in this session
        // HINT: Check SessionManager::get('two_factor_verified')
        $isVerified = false; // Replace with: SessionManager::get('two_factor_verified')

        if ($isVerified) {
            // 2FA already verified, proceed
            return $handler->handle($request);
        }

        // 2FA required but not verified - redirect to verification page
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $verifyUrl = $routeParser->urlFor('2fa.verify');

        $response = new \Nyholm\Psr7\Response();
        return $response
            ->withStatus(302)
            ->withHeader('Location', $verifyUrl);
    }
}
