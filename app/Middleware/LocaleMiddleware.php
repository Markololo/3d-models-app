<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\TranslationHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Locale Middleware
 *
 * Detects and sets the application locale based on:
 * 1. Query parameter (?lang=fr)
 * 2. Default locale (fallback)
 */
class LocaleMiddleware implements MiddlewareInterface
{
    private TranslationHelper $translator;

    /**
     * Initialize the Locale Middleware
     *
     * @param TranslationHelper $translate Translation helper service
     */


    public function __construct(TranslationHelper $translator)
    {
        // TODO: Store the translator parameter in the class property
         $this->$translator = $translator;
    }

    /**
     * Process an incoming server request.
     *
     * Detects the user's preferred locale from query parameters and sets it in the translation helper.
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {

        $categoryId =  isset($queryParams['category']) ? (int)$queryParams['category'] : null;


        // TODO: Get the query parameters from the request
        // Hint: Use $request->getQueryParams()
         $queryParams= $request->getQueryParams();
        // TODO: Extract the 'lang' parameter from query params
        // Hint: Use null coalescing operator (??) to default to null if not present
        $langParam =$queryParams['lang'] ?? '';


        // TODO: If a locale was provided and it's valid, set it in the translator
        // Hint: Check both that locale exists AND that it's available before setting
                $this->products_model->searchProducts($searchTerm, $categoryId);

         TranslationHelper.isLocaleAvailable();
        if($queryParams['locale'])
          {
            $queryParams['locale'] = $translator;
          }

        // TODO: Store the current locale in the request as an attribute named 'locale'
        // Hint: Use $request->withAttribute() to add the attribute
        // Remember: withAttribute() returns a new request object, so reassign it
         $request->withAttribute();
        // TODO: Pass the request to the next middleware/handler and return the response
        return $handler->handle($request);

    }

}
