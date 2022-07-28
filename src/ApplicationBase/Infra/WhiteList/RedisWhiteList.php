<?php

namespace ApplicationBase\Infra\WhiteList;

use ApplicationBase\Infra\Redis;
use ApplicationBase\Infra\Exceptions\UnauthenticatedException;

class RedisWhiteList implements WhiteListInterface
{
	/**
	 * @param string $token
	 *
	 * @return void
	 * @throws UnauthenticatedException
	 */
	public static function checkWhitelist(string $token): void
	{
		if (!Redis::get($token)){
			throw new UnauthenticatedException('The informed token is not whitelisted');
		}
	}
	
	/**
	 * @param string         $token
	 * @param mixed          $value
	 * @param array|int|null $timeout
	 *
	 * @return void
	 */
	public static function addToWhiteList(string $token, mixed $value, array|int $timeout = null): void
	{
		Redis::set($token, $value, $timeout);
	}
	
	/**
	 * @param string $token
	 *
	 * @return void
	 */
	public static function removeFromWhiteList(string $token): void
	{
		Redis::del($token);
	}
}