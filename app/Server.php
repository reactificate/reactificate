<?php


namespace App;


use Closure;
use InvalidArgumentException;
use React\EventLoop\LoopInterface;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;
use Reactificate\Http\HttpHandlerInterface;
use Reactificate\Http\Middleware;
use Reactificate\Http\WebRoot;
use Reactificate\Utils\Utils;
use Reactificate\Websocket\Middleware as WSMiddleware;
use Reactificate\Websocket\WebSocketHandlerInterface;
use Voryx\WebSocketMiddleware\WebSocketMiddleware;
use WyriHaximus\React\Http\Middleware\WebrootPreloadMiddleware;

class Server
{
    protected LoopInterface $loop;
    protected Handler $handler;
    protected SocketServer $socketServer;
    protected HttpServer $httpServer;
    protected WebRoot $webRoot;
    protected Closure $errorCallback;

    public function __construct(LoopInterface $loop, Handler $handler)
    {
        $this->loop = $loop;
        $this->handler = $handler;
        Utils::set('loop', $loop);
    }

    public static function create(LoopInterface $loop, Handler $handler): Server
    {
        return new Server($loop, $handler);
    }

    public function useSocketServer(SocketServer $server): Server
    {
        $this->socketServer = $server;
        return $this;
    }

    public function useHttpServer(HttpServer $server): Server
    {
        $this->httpServer = $server;
        return $this;
    }

    public function webRoot(WebRoot $webRoot): Server
    {
        $this->webRoot = $webRoot;
        return $this;
    }

    public function onError(Closure $closure): Server
    {
        $this->errorCallback = $closure;
        return $this;
    }

    public function run(?string $uri = null): void
    {
        $handlers = $this->handler->get();

        $reactMiddlewares = [];

        if (isset($this->webRoot)) {
            $webRootData = $this->webRoot->getValues();
            $reactMiddlewares[] = new WebrootPreloadMiddleware(
                $webRootData['path'],
                $webRootData['logger'],
                $webRootData['cache']
            );
        }

        /**@var HttpHandlerInterface[] $httpHandlers * */
        $httpHandlers = $handlers['handler.http'];

        /**@var WebSocketHandlerInterface[] $websocketHandlers * */
        $websocketHandlers = $handlers['handler.websocket'];

        $middleware = (new Middleware());
        $middleware->handler(...$httpHandlers);

        foreach ($websocketHandlers as $wsHandler) {
            $reactMiddlewares[] = new WebSocketMiddleware(
                [$wsHandler->getServerInfo()->getInfo()],
                new WSMiddleware($wsHandler)
            );
        }

        //add http handlers
        $reactMiddlewares[] = $middleware;

        if (!isset($this->httpServer)) {
            $this->httpServer = new HttpServer($this->loop, ...$reactMiddlewares);
        }

        if (!isset($this->socketServer)) {
            if (null === $uri) {
                throw new InvalidArgumentException("HttpServer uri must be provided or socket server must be provided");
            }

            $this->socketServer = new SocketServer($uri, $this->loop);
        }

        $this->httpServer->on('error', $this->errorCallback);
        $this->socketServer->on('error', $this->errorCallback);

        $this->httpServer->listen($this->socketServer);
        $this->loop->run();
    }
}