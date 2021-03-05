<?php


namespace App\Http;


use App\Http\Middleware\RequestInspectorMiddleware;
use App\Http\Middleware\RoutingMiddleware;
use App\Http\Middleware\TestMiddleware;
use App\Http\Middleware\VisitCountMiddleware;

class Kernel
{
    public array $middlewares = [
        RequestInspectorMiddleware::class,
        RoutingMiddleware::class,
    ];

    public array $routingMiddlewares = [
        'count' => VisitCountMiddleware::class,
        'test' => TestMiddleware::class,
    ];
}