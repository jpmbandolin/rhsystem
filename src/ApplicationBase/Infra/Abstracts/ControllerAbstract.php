<?php


namespace ApplicationBase\Infra\Abstracts;


use ApplicationBase\Infra\JWT;
use DI\{DependencyException, NotFoundException};
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

abstract class ControllerAbstract
{
	/**
	 * @param ResponseInterface|null $response
	 * @param mixed|null $body
	 * @param int $status
	 * @return ResponseInterface
	 */
	final protected function replyRequest(mixed $body = null, int $status = 200, ResponseInterface $response = null): ResponseInterface
	{
		if($response === null){
			$response = new Response;
		}

		$resBody = $response->getBody();
		$resBody->write(json_encode($body));

		if ($status === 201){
			return $response->withStatus($status);
		}

		return $response->withBody($resBody)->withStatus($status);
	}

	/**
	 * @return object
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	final protected function getJwtData():object{
		global $container;
		return $container->get(JWT::class);
	}
}