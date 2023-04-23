<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Framework
{
    private const ROUTES = [
        '/' => [
            'HomeController',
            'index'
        ],
        '/policy' => [
            'PolicyController',
            'index'
        ],
        '/connect/google' => [
            'SecurityController',
            'googleConnectAction', ['token']
        ],
        '/connect/apple' => [
            'SecurityController',
            'appleConnectAction', ['token', 'platform', 'firstName', 'lastName']
        ],
        '/notifications' => [
            'NotificationController',
            'index'
        ],
        '/notifications/success' => [
            'NotificationController',
            'success'
        ]
    ];

    public function handle(Request $request): Response
    {
        // the URI being requested (e.g. /about) minus any query parameters
        $route = $request->getPathInfo();

        if (!key_exists($route, self::ROUTES)) {
            return new Response('Not Found', Response::HTTP_NOT_FOUND);
        }

        // Get the matching route
        $matchingRoute = self::ROUTES[$route];

        // Get the FQCN of controller associated to the matching route.
        $controller = 'App\\Controller\\' . $matchingRoute[0];

        // Get the method associated to the matching route.
        $method = $matchingRoute[1];

        // Get the queryString values configured for the matching route.
        $parameters = [];
        foreach ($matchingRoute[2] ?? [] as $parameter) {
            $parameters[] = $request->get($parameter);
        }

        // instance the controller, call the method with given parameters.
        try {
            return (new $controller())->$method(...$parameters);
        } catch (\Exception $e) {
            if (APP_ENV === 'dev') {
                throw $e;
            }
            // if an exception is thrown during controller execution
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
