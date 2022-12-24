<?php

namespace ApplicationBase\Infra\Slim;

use ApplicationBase\Infra\Environment\Environment;
use ApplicationBase\Infra\Exceptions\AppException;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
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
     * @param Throwable $exception
     * @param bool $displayErrorDetails
     * @param bool $logErrors
     * @param bool $logErrorDetails
     * @return ResponseInterface
     * @throws JsonException
     */
	public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails): ResponseInterface
	{
		global $app;

		$payload = [];
		$devEnvironment = Environment::getEnvironment()->getApplication()->getEnvironment() === "dev";
        $isNotFoundRoute = $exception instanceof HttpNotFoundException;

        if ($isNotFoundRoute){
            $handledException = new NotFoundException("The requested route was not found.", previous: $exception);
        }else if (!is_a($exception, AppException::class)){
			$handledException = new RuntimeException("Internal Server Error.", previous: $exception);
		}else {
            $handledException = $exception;
        }

		if ($devEnvironment) {
			$payload['error'] = $handledException->getDetailedErrorMessage();
			$payload['errorTrace'] = $handledException->getTraceAsString();
			$payload['file'] = $handledException->getFile();
			$payload['line'] = $handledException->getLine();
		}else {
			$payload['error'] = $handledException->getMessage();
		}

		$response = $app->getResponseFactory()->createResponse();
		$response->getBody()->write(
			json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
		);

        if (!$isNotFoundRoute){
            try {
                $this->notifyDiscord($handledException);
            }catch (Throwable $t){
                if ($devEnvironment){
                    $handledException = new RuntimeException("Discord Webhook Error", previous: $t);
                }
            }
        }

		return $response
			->withStatus($handledException->getHttpStatusCode())
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
	private function notifyDiscord(Throwable $t):void
    {
		$currentUserData = ControllerAbstract::getCurrentUserData();
        $exceptionDescription = "Endpoint: " . $_SERVER['REQUEST_URI']. " full uri: " . $_SERVER['SCRIPT_NAME'] .  " - Trace Below";

        $embeds = [
            new Embed(
                title: "New Exception Detected. Type: " . $t::class . ". Code: " . $t->getCode(),
                description: $exceptionDescription,
                color: 15158332
            )
        ];

        foreach (AppException::yieldExceptionDataRecursive($t) as $index => $exceptionData){
			$embeds[] = new Embed(
				title: "#".($index+1) . " - " . $exceptionData["exceptionType"]. "::" . $exceptionData['message'],
				description: "File: " . $exceptionData['file'] . " Line: " . $exceptionData['line'],
				color: "15158332"
			);
		}

		$webhookNotification = new WebhookNotification(Environment::getEnvironment()->getApplication()->getErrorWebhookAddress(), "Bad News Bringer", ...$embeds);
		
		if($currentUserData !== null){
			$webhookNotification->setAuthor("Caused By: " . $currentUserData->id . " - " . $currentUserData->name);
		}

		$webhookNotification->send();
	}
}
