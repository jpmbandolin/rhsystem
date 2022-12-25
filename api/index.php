<?php

require_once '../vendor/autoload.php';


use ApplicationBase\Infra\Application;
use ApplicationBase\Infra\Environment\Environment;
use ApplicationBase\Infra\WhiteList\RedisWhiteList;

Application::setupEnvironment();
Application::runSlimApp();

if (Environment::getEnvironment()->getApplication()->getTokenWhitelist() === "1") {
    Application::setWhitelistHandler(new RedisWhiteList);
}