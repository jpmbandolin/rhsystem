<?php

namespace ApplicationBase\Infra\Slim;

use ApplicationBase\Infra\Exceptions\AppException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Handlers\ErrorHandler;
use Throwable;
use ApplicationBase\Infra\Exceptions\RuntimeException;

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
		global $app, $ENV;
		$payload = [];
		$devEnvironment = $ENV['APPLICATION']['environment'] === "dev";
		
		if (!is_a($exception, AppException::class)){
			$exception = new RuntimeException("Internal Server Error.", previous: $exception);
		}

		if ($devEnvironment){
			$payload['error'] = $exception->getDetailedErrorMessage();
			$payload['errorTrace'] = $exception->getTraceAsString();
			$payload['file'] = $exception->getFile();
			$payload['line'] = $exception->getLine();
		}else{
			$payload['error'] = $exception->getMessage();
		}

		$response = $app->getResponseFactory()->createResponse();
		$response->getBody()->write(
			json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
		);

		return $response
			->withStatus($exception->getHttpStatusCode())
			->withHeader('Content-Type', 'application/json')
			->withHeader('Access-Control-Allow-Origin', '*')
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	}

}