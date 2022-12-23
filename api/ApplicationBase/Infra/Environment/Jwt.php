<?php

namespace ApplicationBase\Infra\Environment;

class Jwt
{
    public function __construct(
        private readonly string $key,
        private readonly int $expiresAt
    )
    {

    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }
}