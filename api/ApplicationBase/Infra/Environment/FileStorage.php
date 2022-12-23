<?php

namespace ApplicationBase\Infra\Environment;

class FileStorage
{
    public function __construct(private readonly string $basePath)
    {

    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }
}