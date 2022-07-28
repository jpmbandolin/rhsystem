<?php

namespace ApplicationBase\Infra\Slim;

use ApplicationBase\Infra\JWT;
use Psr\Http\Server\RequestHandlerInterface;
use ApplicationBase\Infra\WhiteList\WhiteListInterface;
use ApplicationBase\Infra\Exceptions\UnauthenticatedException;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

class Authenticator
{
	private static ?WhiteListInterface $whiteList = null;
	
	/**
	 * @param WhiteListInterface $whiteList
	 *
	 * @return void
	 */
	public static function setWhiteListHandler(WhiteListInterface $whiteList): void
	{
		self::$whiteList = $whiteList;
	}
	
	/**
	 * @return null|WhiteListInterface
	 */
	public static function getWhiteList(): ?WhiteListInterface
	{
		return self::$whiteList;
	}
	
	/**
	 * @param ServerRequestInterface  $request
	 * @param RequestHandlerInterface $handler
	 *
	 * @return ResponseInterface
	 * @throws UnauthenticatedException
	 */
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		global $container;
		$headers = $request->getHeaders();
		
		if ((!isset($headers['Authorization']) || empty($headers['Authorization'][0]))) {
			throw new UnauthenticatedException('Token not found.');
		}

		if (!str_contains($headers['Authorization'][0], 'Bearer ')) {
			throw new UnauthenticatedException('Invalid Token.');
		}

		$token = explode('Bearer ', $headers['Authorization'][0])[1];

		if (!is_null(self::$whiteList)) {
			self::$whiteList::checkWhitelist($token);
		}

		$container->set('jwt', $token);
		$container->set(JWT::class, JWT::getJWTPayload($token));

		return $handler->handle($request);
	}
}