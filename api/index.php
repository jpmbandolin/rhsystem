<?php

require_once '../vendor/autoload.php';

use ApplicationBase\Infra\Slim\{Authenticator, Router, SlimCorsMiddleware, SlimErrorHandler};
use DI\Bridge\Slim\Bridge;
use DI\Container;
use ApplicationBase\Infra\WhiteList\RedisWhiteList;

$ENV = parse_ini_file('../env.ini', true);

$container = new Container;

$app = Bridge::create($container);
$app->setBasePath($ENV['SLIM']['base_path']);
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->add(new SlimCorsMiddleware);

if ($ENV['APPLICATION']['token_whitelist'] === "1"){
	Authenticator::setWhiteListHandler(new RedisWhiteList);
}

(new Router)($app);


$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler(new SlimErrorHandler($app->getCallableResolver(), $app->getResponseFactory()));

$app->run();