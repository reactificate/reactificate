<?php


namespace App\Http\Middleware;


use Reactificate\Http\Middleware\Middleware;
use Reactificate\Http\Middleware\MiddlewareInterface;
use Reactificate\Http\ResponseInterface;

class VisitCountMiddleware implements MiddlewareInterface
{
    public function run(Middleware $middleware, ResponseInterface $response): void
    {

    }
}