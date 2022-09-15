<?php

namespace ApplicationBase\Infra\Slim;

use ApplicationBase\Infra\Exceptions\AppException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Handlers\ErrorHandler;
use Throwable;
use ApplicationBase\Infra\DiscordIntegration\Embed;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\DiscordIntegration\WebhookNotification;

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
		
		try {
			$this->notifyDiscord($exception);
		}catch (Throwable $t){
			if ($devEnvironment){
				die($t->getMessage() . "A");
			}
		}

		return $response
			->withStatus($exception->getHttpStatusCode())
			->withHeader('Content-Type', 'application/json')
			->withHeader('Access-Control-Allow-Origin', '*')
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	}
	
	/**
	 * @param Throwable $t
	 *
	 * @return void
	 * @throws JsonException
	 */
	private function notifyDiscord(Throwable $t):void{
		$currentUserData = ControllerAbstract::getCurrentUserData();
		$embeds = [new Embed(title: "New Exception Detected", type: "rich", description: "Endpoint: " . $_SERVER['REQUEST_URI']. " - Trace Below", color: 15158332)];
		foreach (AppException::yieldExceptionDataRecursive($t) as $index => $exceptionData){
			$embeds[] = new Embed(
				title: "#".($index+1) . " - " . $exceptionData['message'],
				type: "rich",
				description: "File: " . $exceptionData['file'] . " Line: " . $exceptionData['line'],
				color: "15158332"
			);
		}
		global $ENV;
		$webhookNotification = new WebhookNotification($ENV["APPLICATION"]['error_webhook_address'], "Bad News Bringer", ...$embeds);
		
		if($currentUserData !== null){
			$webhookNotification->setAuthor("Caused By: " . $currentUserData->id . " - " . $currentUserData->name);
		}

		$webhookNotification->send();
	}
}