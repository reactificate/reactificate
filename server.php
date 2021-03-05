<?php

use App\Handler;
use App\Server;
use App\Servers\HttpServer;
use App\Servers\WebSocketServer;
use Psr\Log\NullLogger;
use React\Cache\ArrayCache;
use React\EventLoop\Factory;
use Reactificate\Http\Response;
use Reactificate\Http\WebRoot;
use Reactificate\Utils\Console;

require 'vendor/autoload.php';

$loop = Factory::create();
$uri = '0.0.0.0:9200';

require 'app/Helper/functions.php';

$myCounter = new HttpServer();
$socketHandler = new WebSocketServer();

//create handler instance
$handler = Handler::create()
    ->httpHandler($myCounter)
    ->websocketHandler($socketHandler);

//create webroot instance
$webRoot = WebRoot::create()
    ->path(public_path())
    ->logger(new NullLogger())
    ->cache(new ArrayCache());

Response::setViewPath(__DIR__ . '/resources/views/');

//create server
$server = Server::create($loop, $handler);
//set web root directory
$server->webRoot($webRoot);
$server->onError(function (Throwable $exception) {
    echo $exception;
});

Console::echo("WS Server: ws://{$uri}\n");
Console::echo("Http Server: http://{$uri}\n");
$server->run($uri);