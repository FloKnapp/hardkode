<?php

require_once __DIR__ . '/vendor/autoload.php';

use Hardkode\Config;
use Apix\Log\Logger;
use Hardkode\Dispatcher;
use Hardkode\Service\Session;
use Hardkode\Service\EntityManager;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Hardkode\Controller\ErrorController;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$config      = new Config();
$environment = getenv('APPLICATION_ENV');

$fileLogger = new Apix\Log\Logger\File(__DIR__ . '/logs/' . $environment . '.log');
$fileLogger->setMinLevel(($environment === 'development') ? 'debug' : 'notice');

$logger = new Logger([$fileLogger]);

$psr17Factory = new Psr17Factory();
$request = $psr17Factory->createServerRequest(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
    $_SERVER
);

$database   = new EntityManager();
$session    = new Session();

$errorController = new ErrorController($request, $logger, $config, $session, $database);
set_error_handler([$errorController, 'onError']);
set_exception_handler([$errorController, 'onException']);

$dispatcher = new Dispatcher($config, $logger, $session, $database);
$response   = $dispatcher->forward($request);

if ($response instanceof ResponseInterface) {
    echo (string)$response->getBody();
    exit(0);
}

exit(1);