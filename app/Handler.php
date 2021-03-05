<?php


namespace App;


use Reactificate\Http\HttpHandlerInterface;
use Reactificate\Websocket\WebSocketHandlerInterface;

class Handler
{
    protected array $httpHandlers;
    protected array $socketHandlers;


    public static function create(): Handler
    {
        return new Handler();
    }

    /**
     * Add http middleware to request
     *
     * @param HttpHandlerInterface ...$httpHandlerInterfaces
     * @return Handler
     */
    public function httpHandler(HttpHandlerInterface ...$httpHandlerInterfaces): Handler
    {
        $this->httpHandlers = $httpHandlerInterfaces;
        return $this;
    }

    /**
     * Add websocket server
     *
     * @param WebSocketHandlerInterface ...$socketHandlerInterfaces Register websocket servers, this server must implement WebsocketHandlerInterface
     * @return Handler
     */
    public function websocketHandler(WebSocketHandlerInterface ...$socketHandlerInterfaces): Handler
    {
        $this->socketHandlers = $socketHandlerInterfaces;
        return $this;
    }

    public function get(): array
    {
        return [
            'handler.http' => $this->httpHandlers,
            'handler.websocket' => $this->socketHandlers,
        ];
    }
}