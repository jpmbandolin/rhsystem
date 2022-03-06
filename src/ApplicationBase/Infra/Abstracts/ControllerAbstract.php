<?php


namespace ApplicationBase\Infra\Abstracts;


use ApplicationBase\Infra\Enums\PermissionEnum;
use ApplicationBase\Infra\JWT;
use ApplicationBase\Infra\PaginatedData;
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

		if (is_a($body, PaginatedData::class)){
			$body = [
				"d"=>$body->getPaginatedData(),
				"pages"=>$body->getTotalPages(),
				"page"=>$body->getPage()
			];
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
		return new class($container->get(JWT::class)){
			public int $id;
			public string $name;
			public string $email;
			public array $permissions;

			public function __construct($jwtRawObject){
				$this->id           = $jwtRawObject->id;
				$this->name         = $jwtRawObject->name;
				$this->email        = $jwtRawObject->email;
				$this->permissions  = array_map(static function ($permission){
					return PermissionEnum::tryFrom($permission);
				}, $jwtRawObject->permissions);
			}
		};
	}
}