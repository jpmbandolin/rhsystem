<?php

namespace ApplicationBase\Infra;

final class Redis
{
	private static ?\Redis $connection = null;

	private function __construct(){}

	/**
	 * @return \Redis
	 */
	public static function getInternalConnection():\Redis{
		if (is_null(self::$connection)){
			global $ENV;
			self::$connection = new \Redis;
			self::$connection->connect($ENV['REDIS']['host']);
		}

		return self::$connection;
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public static function get(string $key):mixed{
		return self::getInternalConnection()->get($key);
	}

	/**
	 * @param string         $key
	 * @param mixed          $value
	 * @param int|array|null $timeout
	 * @return bool
	 */
	public static function set(string $key, mixed $value, int|array $timeout = null):bool{
		if (!is_string($value)){
			$value = json_encode($value);
		}

		return self::getInternalConnection()->set($key, $value, $timeout);
	}

	/**
	 * @param string|int ...$key
	 * @return int
	 */
	public static function del(string|int ...$key):int{
		return self::getInternalConnection()->del(...$key);
	}
}