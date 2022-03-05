<?php

namespace Modules\User\Application\Create;

use ApplicationBase\Infra\Attributes\RouteAuthenticator;
use ApplicationBase\Infra\Exceptions\{BusinessException, DatabaseException};
use Modules\User\Domain\User;
use Psr\Http\Message\ResponseInterface;

class Create extends \ApplicationBase\Infra\Abstracts\ControllerAbstract
{
	/**
	 * @param CreateDTO $dto
	 * @return ResponseInterface
	 * @throws DatabaseException|BusinessException
	 */
	#[RouteAuthenticator(['user::create'])]
	public function run(CreateDTO $dto):ResponseInterface{
		if ($dto->password !== $dto->confirmPassword){
			throw new BusinessException('The password must match the password confirmation field');
		}

		$password = password_hash($dto->password, PASSWORD_DEFAULT);

		$user = new User(
			name: $dto->name,
			password: $password,
			email: $dto->email
		);

		$user->save();

		return $this->replyRequest(status: 201);
	}
}