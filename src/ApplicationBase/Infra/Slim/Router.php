<?php

namespace ApplicationBase\Infra\Slim;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

class Router
{
	public function __invoke(App $app)
	{
		$fullUri	= $_SERVER['REQUEST_URI'];
		$rootUri	= dirname($_SERVER['SCRIPT_NAME']);
		$path		= str_replace($rootUri, "", $fullUri);

		$app->options('/{routes:.+}', function (RequestInterface $request, Response $response): Response {
			return $response;
		});

		$app->get('/connection-test', function (Request $request, Response $response): Response{
			$response->getBody()->write(json_encode(['online' => true], JSON_THROW_ON_ERROR));
			return $response;
		});

		$app->get('/connection-test-secure', function (Response $response) {
			$response->getBody()->write(json_encode(['online' => true], JSON_THROW_ON_ERROR));
			return $response;
		})->add(new Authenticator);

		$routeGroups = [
			"/user"  => \Modules\User\Router::class,
		];

		foreach ($routeGroups as $index => $router){
			if (str_starts_with($path, $index)){
				$app->group($index, $router);
				break;
			}
		}
	}
}