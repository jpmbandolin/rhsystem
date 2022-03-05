<?php

require_once './vendor/autoload.php';

use ApplicationBase\Infra\Slim\{Router, SlimCorsMiddleware, SlimErrorHandler};
use DI\Bridge\Slim\Bridge;
use DI\Container;

$ENV = parse_ini_file('../env.ini', true);

$container = new Container;

$app = Bridge::create($container);
$app->setBasePath($ENV['APPLICATION']['base_path']);
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->add(new SlimCorsMiddleware);

(new Router)($app);

$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler(new SlimErrorHandler($app->getCallableResolver(), $app->getResponseFactory()));

$app->run();