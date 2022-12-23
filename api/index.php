<?php

require_once '../vendor/autoload.php';

use ApplicationBase\Infra\Environment\Application;
use ApplicationBase\Infra\Environment\Database;
use ApplicationBase\Infra\Environment\Environment;
use ApplicationBase\Infra\Environment\FileStorage;
use ApplicationBase\Infra\Environment\Jwt;
use ApplicationBase\Infra\Environment\Migrations;
use ApplicationBase\Infra\Environment\Slim;
use ApplicationBase\Infra\Slim\{Authenticator, Router, SlimCorsMiddleware, SlimErrorHandler};
use DI\Bridge\Slim\Bridge;
use DI\Container;
use ApplicationBase\Infra\WhiteList\RedisWhiteList;

$ENV = parse_ini_file('../env.ini', true);

Environment::setupEnvironment(
    new Application(
        $ENV['APPLICATION']['environment'],
        $ENV['APPLICATION']['token_whitelist'],
        $ENV['APPLICATION']['error_webhook_address']
    ),
    new Database(
        $ENV['DATABASE']['database'],
        $ENV['DATABASE']['user'],
        $ENV['DATABASE']['password'],
        $ENV['DATABASE']['host']
    ),
    new FileStorage($ENV['FILE_STORAGE']['base_path']),
    new Jwt($ENV['JWT']['key'], $ENV['JWT']['expires_at']),
    new Migrations($ENV['MIGRATIONS']['db_host']),
    new \ApplicationBase\Infra\Environment\Redis($ENV["REDIS"]['host']),
    new Slim($ENV["SLIM"]['base_path'])
);

$environment = Environment::getEnvironment();

$container = new Container;

$app = Bridge::create($container);
$app->setBasePath($environment->getSlim()->getBasePath());
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->add(new SlimCorsMiddleware);

if ($environment->getApplication()->getTokenWhitelist() === "1") {
    Authenticator::setWhiteListHandler(new RedisWhiteList);
}

(new Router)($app);

$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler(new SlimErrorHandler($app->getCallableResolver(), $app->getResponseFactory()));

$app->run();