<?php

namespace ApplicationBase\Infra\Environment;

class Environment
{
    private static Environment $environment;
    private function __construct(
        private readonly Application $application,
        private readonly Database $database,
        private readonly FileStorage $fileStorage,
        private readonly Jwt $jwt,
        private readonly Migrations $migrations,
        private readonly Redis $redis,
        private readonly Slim $slim
    )
    {

    }

    /**
     * This method should be called once.
     *
     * @param Application $application
     * @param Database $database
     * @param FileStorage $fileStorage
     * @param Jwt $jwt
     * @param Migrations $migrations
     * @param Redis $redis
     * @param Slim $slim
     * @return void
     */
    public static function setupEnvironment(
        Application $application,
        Database $database,
        FileStorage $fileStorage,
        Jwt $jwt,
        Migrations $migrations,
        Redis $redis,
        Slim $slim
    ): void {
        if (!isset(self::$environment)){
            self::$environment = new self($application, $database, $fileStorage, $jwt, $migrations, $redis, $slim);
        }
    }

    /**
     * self::setupEnvironment should be called before calling this method.
     *
     * @return static
     */
    public static function getEnvironment(): self
    {
        return self::$environment;
    }

    /**
     * @return Application
     */
    public function getApplication(): Application
    {
        return $this->application;
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * @return FileStorage
     */
    public function getFileStorage(): FileStorage
    {
        return $this->fileStorage;
    }

    /**
     * @return Jwt
     */
    public function getJwt(): Jwt
    {
        return $this->jwt;
    }

    /**
     * @return Migrations
     */
    public function getMigrations(): Migrations
    {
        return $this->migrations;
    }

    /**
     * @return Redis
     */
    public function getRedis(): Redis
    {
        return $this->redis;
    }

    /**
     * @return Slim
     */
    public function getSlim(): Slim
    {
        return $this->slim;
    }
}