<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Environment\Application as EnvApplication;
use ApplicationBase\Infra\Environment\Database;
use ApplicationBase\Infra\Environment\Environment;
use ApplicationBase\Infra\Environment\FileStorage;
use ApplicationBase\Infra\Environment\Jwt;
use ApplicationBase\Infra\Environment\Migrations;
use ApplicationBase\Infra\Environment\Slim;
use ApplicationBase\Infra\Slim\Authenticator;
use ApplicationBase\Infra\Slim\Router;
use ApplicationBase\Infra\Slim\SlimCorsMiddleware;
use ApplicationBase\Infra\Slim\SlimErrorHandler;
use ApplicationBase\Infra\WhiteList\WhiteListInterface;
use DI\Container;
use DI\Bridge\Slim\Bridge;
use Slim\App;

class Application
{
    private static Container $container;
    private static App $app;

    public static function setupEnvironment(): void
    {
        $ENV = parse_ini_file('../env.ini', true);

        Environment::setupEnvironment(
            application: new EnvApplication(
                environment: $ENV['APPLICATION']['environment'],
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
    }

    public static function setWhitelistHandler(WhiteListInterface $whiteListHandler): void
    {
        Authenticator::setWhiteListHandler($whiteListHandler);
    }

    private static function createSlimApp(): void
    {
        self::$app = Bridge::create(self::getSlimContainer());
        self::$app->setBasePath(Environment::getEnvironment()->getSlim()->getBasePath());
        self::$app->addRoutingMiddleware();
        self::$app->addBodyParsingMiddleware();
        self::$app->add(new SlimCorsMiddleware);
        self::setCustomErrorHandler();
        self::declareRoutes();

    }

    private static function setCustomErrorHandler(): void
    {
        (self::$app->addErrorMiddleware(false, false, false))
            ->setDefaultErrorHandler(
                new SlimErrorHandler(self::$app->getCallableResolver(), self::$app->getResponseFactory())
            );
    }

    public static function runSlimApp(): void
    {
        self::getSlimApp()->run();
    }

    public static function getSlimApp(): App
    {
        if (!isset(self::$app)) {
            self::createSlimApp();
        }

        return self::$app;
    }

    public static function getSlimContainer(): Container
    {
        if (!isset(self::$container)) {
            self::$container = new Container();
        }

        return self::$container;
    }

    private static function declareRoutes(): void
    {
        Router::declareRoutes();
    }
}