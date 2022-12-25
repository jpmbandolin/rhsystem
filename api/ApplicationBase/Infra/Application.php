<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Environment\Application as EnvApplication;
use ApplicationBase\Infra\Environment\{Redis, Database, Environment, FileStorage, Jwt, Migrations, Slim};
use ApplicationBase\Infra\Slim\{Authenticator, Router, SlimCorsMiddleware, SlimErrorHandler};
use ApplicationBase\Infra\WhiteList\WhiteListInterface;
use DI\Container;
use DI\Bridge\Slim\Bridge;
use Slim\App;

class Application
{
    private static Container $container;
    private static App $app;

    public static function setupEnvironment(string $envFilePath = "../env.ini"): void
    {
        $ENV = parse_ini_file($envFilePath, true);

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
            redis: new Redis(
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