<?php


namespace App\Http;


use App\Http\Middleware\RequestInspectorMiddleware;
use App\Http\Middleware\TestMiddleware;
use App\Http\Middleware\VisitCountMiddleware;

class Kernel
{
    public array $middlewares = [
        RequestInspectorMiddleware::class,
    ];

    public array $routingMiddlewares = [
        'count' => VisitCountMiddleware::class,
        'test' => TestMiddleware::class,
    ];
}