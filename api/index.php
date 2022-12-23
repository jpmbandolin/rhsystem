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
    application: new Application(
       environment:  $ENV['APPLICATION']['environment'],
        tokenWhitelist: $ENV['APPLICATION']['token_whitelist'],
        errorWebhookAddress: $ENV['APPLICATION']['error_webhook_address']
    ),
    database: new Database(
        database: $ENV['DATABASE']['database'],
        user: $ENV['DATABASE']['user'],
        password: $ENV['DATABASE']['password'],
        host: $ENV['DATABASE']['host']
    ),
    fileStorage: new FileStorage(
        basePath: $ENV['FILE_STORAGE']['base_path']
    ),
    jwt: new Jwt(
        key: $ENV['JWT']['key'],
        expiresAt: $ENV['JWT']['expires_at']
    ),
    migrations: new Migrations(
        dbHost: $ENV['MIGRATIONS']['db_host']
    ),
    redis: new \ApplicationBase\Infra\Environment\Redis(
        host: $ENV["REDIS"]['host']
    ),
    slim: new Slim(
        basePath: $ENV["SLIM"]['base_path']
    )
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