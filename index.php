<?php

require_once './vendor/autoload.php';

use ApplicationBase\Infra\Slim\{SlimCorsMiddleware, SlimErrorHandler};
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;

$ENV = parse_ini_file('../env.ini', true);

$container = new Container;

$app = Bridge::create($container);
$app->setBasePath($ENV['APPLICATION']['base_path']);
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->add(new SlimCorsMiddleware);

$app->options('/{routes:.+}', function (RequestInterface $request, Response $response) {
	return $response;
});

//add router here

$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler(new SlimErrorHandler($app->getCallableResolver(), $app->getResponseFactory()));

$app->run();