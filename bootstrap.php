<?php

require_once __DIR__ . '/vendor/autoload.php';

use Hardkode\Config;
use Apix\Log\Logger;
use Hardkode\Container;
use Hardkode\Dispatcher;
use Nyholm\Psr7\Request;
use Hardkode\Service\Session;
use Hardkode\Service\EntityManager;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;
use Hardkode\Controller\ErrorController;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$container = new Container();

$config = new Config();
$container->set(Config::class, $config);

$environment = getenv('APPLICATION_ENV');

$fileLogger = new Apix\Log\Logger\File(__DIR__ . '/logs/' . $environment . '.log');
$fileLogger->setMinLevel(($environment === 'development') ? 'debug' : 'error');
$logger = new Logger([$fileLogger]);
$container->set(Logger::class, $logger);

$psr17Factory = new Psr17Factory();
$creator      = new ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);
$request = $creator->fromGlobals();
$container->set(Request::class, $request, [\Psr\Http\Message\RequestInterface::class]);

$entityManager   = new EntityManager();
$container->set(EntityManager::class, $entityManager);

$session    = new Session();
$container->set(Session::class, $session);

$user       = new \Hardkode\Service\User($entityManager, $session);
$container->set(\Hardkode\Service\User::class, $user);

$translator = new \Hardkode\Service\Translator('de');
$container->set(\Hardkode\Service\Translator::class, $translator);

$errorController = new ErrorController($request, $logger, $config, $session, $entityManager, $user);
set_error_handler([$errorController, 'onError']);
set_exception_handler([$errorController, 'onException']);

$dispatcher = new Dispatcher($container);
$response   = $dispatcher->forward($request);

if ($response instanceof ResponseInterface) {

    $headers = $response->getHeaders();

    foreach ($headers as $name => $value) {
        header($name . ': ' . $value);
    }

    echo (string)$response->getBody();
    exit(0);
}



exit(1);