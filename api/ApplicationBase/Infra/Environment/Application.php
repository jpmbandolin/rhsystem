<?php

namespace ApplicationBase\Infra\Environment;

class Application
{
    public function __construct(
        private readonly string $environment,
        private readonly string $tokenWhitelist,
        private readonly string $errorWebhookAddress,
    )
    {

    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function getTokenWhitelist(): string
    {
        return $this->tokenWhitelist;
    }

    /**
     * @return string
     */
    public function getErrorWebhookAddress(): string
    {
        return $this->errorWebhookAddress;
    }
}