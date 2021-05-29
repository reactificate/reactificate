<?php

use App\Handler;
use App\Server;
use Psr\Log\NullLogger;
use React\Cache\ArrayCache;
use React\EventLoop\Factory;
use Reactificate\Http\Prebuilt\Servers\HttpServer;
use Reactificate\Http\Response;
use Reactificate\Http\WebRoot;
use Reactificate\Utils\Console;
use Reactificate\Websocket\Prebuilt\Servers\ChatServer;

require 'vendor/autoload.php';

$loop = Factory::create();
$uri = '0.0.0.0:9200';

require 'app/Helper/functions.php';

//create handler instance
$handler = Handler::create()
    ->httpHandler(new HttpServer())
    ->websocketHandler(new ChatServer());

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