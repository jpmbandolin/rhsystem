<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Exceptions\UnauthenticatedException;
use Firebase\JWT\{BeforeValidException, ExpiredException, SignatureInvalidException};

class JWT extends \Firebase\JWT\JWT
{
	private function __construct(){}

	/**
	 * @param mixed $payload
	 * @return string
	 */
	public static function generateJWT(mixed $payload):string{
		global $ENV;
		$defaultPayload = [
			"iss"=>"server",
			"aud"=>"users",
			"iat"=>time(),
			"exp"=>time() + $ENV['JWT']['expires_at'] //time in seconds
		];

		return self::encode(array_merge((array)$payload, $defaultPayload), $ENV['JWT']['key']);
	}

	/**
	 * @param string $jwt
	 * @return object
	 * @throws UnauthenticatedException
	 */
	public static function getJWTPayload(string $jwt):object{
		global $ENV;

		try {
			return self::decode($jwt, $ENV['JWT']['key'], array_keys(self::$supported_algs));
		}catch (SignatureInvalidException $e){
			throw new UnauthenticatedException(message: "Invalid JWT", previous: $e);
		}catch (BeforeValidException $e){
			throw new UnauthenticatedException(message: 'JWT isn\'t valid yet', previous: $e);
		}catch (ExpiredException $e){
			throw new UnauthenticatedException(message: 'The JWT is expired', previous: $e);
		}
	}

}