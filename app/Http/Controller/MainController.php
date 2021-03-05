<?php


namespace App\Http\Controller;


use Nette\Utils\JsonException;
use Reactificate\Http\ResponseInterface;

class MainController
{
    /**
     * Webpage test
     *
     * @param ResponseInterface $response
     * @throws JsonException
     */
    public function index(ResponseInterface $response): void
    {
        $response->html('Welcome to homepage :)')->end();
    }

    /**
     * API end-point test
     *
     * @param ResponseInterface $response
     * @throws JsonException
     */
    public function api(ResponseInterface $response): void
    {
        $response->json([
            'status' => 200,
            'success' => true,
            'data' => [
                'message' => 'It worked',
                'time' => microtime(true),
            ],
        ])->end();
    }
}