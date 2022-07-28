<?php

namespace ApplicationBase\Infra\WhiteList;

interface WhiteListInterface
{
	public static function checkWhitelist(string $token): void;

	public static function addToWhiteList(string $token, mixed $value, array|int $timeout = null): void;

	public static function removeFromWhiteList(string $token): void;
}