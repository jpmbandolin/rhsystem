<?php

namespace ApplicationBase\Infra\Slim;

use Slim\App;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Router
{
	/**
	 * @param App $app
	 *
	 * @return void
	 */
	public function __invoke(App $app): void
	{
		$fullUri = $_SERVER['REQUEST_URI'];
		$rootUri = dirname($_SERVER['SCRIPT_NAME']);
		$path = str_replace($rootUri, "", $fullUri);
		
		$app->options(
			'/{routes:.+}', function (RequestInterface $request, Response $response): Response {
				return $response;
			}
		);
		
		$app->get('/', function (Request $request, Response $response): Response {
				$response->getBody()->write(json_encode(['online' => true], JSON_THROW_ON_ERROR));
				return $response;
			}
		);
		
		$routeGroups = [
			"/user"         => \Modules\User\Router::class,
			"/candidate"    => \Modules\Candidate\Router::class,
			"/test"         => \Modules\Test\Router::class,
			"/resume"       => \Modules\Resume\Router::class,
		];
		
		foreach ($routeGroups as $index => $router) {
			if (str_starts_with($path, $index)) {
				$app->group($index, $router);
				break;
			}
		}
	}
}