<?php

namespace ApplicationBase\Infra\Slim;

use ApplicationBase\Infra\Exceptions\UnauthenticatedException;
use ApplicationBase\Infra\JWT;
use ApplicationBase\Infra\Redis;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class Authenticator
{
	/**
	 * @param ServerRequestInterface  $request
	 * @param RequestHandlerInterface $handler
	 * @return ResponseInterface
	 * @throws UnauthenticatedException
	 */
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface{
		global $container;
		$headers = $request->getHeaders();

		if ((!isset($headers['Authorization']) || empty($headers['Authorization'][0]))){
			throw new UnauthenticatedException('Token not found.');
		}

		if (!str_contains($headers['Authorization'][0], 'Bearer ')){
			throw new UnauthenticatedException('Invalid Token.');
		}

		$token = explode('Bearer ', $headers['Authorization'][0])[1];
		$container->set(JWT::class, JWT::getJWTPayload($token));
		self::checkWhitelist($token);
		//@todo implementar validação de atributos com reflection, deve ser feita após o set do JWT

		return $handler->handle($request);
	}

	/**
	 * @param string $jwt
	 * @return void
	 * @throws UnauthenticatedException
	 */
	private static function checkWhitelist(string $jwt): void
	{
		if (!Redis::get($jwt)){
			throw new UnauthenticatedException('The informed token is not whitelisted');
		}
	}
}