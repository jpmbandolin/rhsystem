<?php

namespace ApplicationBase\Infra\Slim;

use ApplicationBase\Infra\Exceptions\AppException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Handlers\ErrorHandler;
use Throwable;

class SlimErrorHandler extends ErrorHandler
{

	/**
	 * @param ServerRequestInterface $request
	 * @param Throwable              $exception
	 * @param bool                   $displayErrorDetails
	 * @param bool                   $logErrors
	 * @param bool                   $logErrorDetails
	 * @return ResponseInterface
	 * @throws JsonException
	 */
	public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails): ResponseInterface
	{
		global $app;
		$status = 500;
		$payload = [];
		if (is_a($exception, AppException::class)){
			$payload['error'] = $exception->getMessage();
			$status = $exception->getHttpStatusCode();
		}else{
			$payload['error'] = "Internal Server Error.";
		}

		$response = $app->getResponseFactory()->createResponse();
		$response->getBody()->write(
			json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
		);

		return $response
			->withStatus($status)
			->withHeader('Access-Control-Allow-Origin', '*')
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	}

}