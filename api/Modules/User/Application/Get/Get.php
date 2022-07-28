<?php

namespace Modules\User\Application\Get;

use Modules\User\Domain\User;
use Psr\Http\Message\ResponseInterface;
use ApplicationBase\Infra\PaginatedData;
use ApplicationBase\Infra\Enums\PermissionEnum;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{DatabaseException,
	NotFoundException,
	PermissionException,
	UnauthenticatedException
};

class Get extends ControllerAbstract
{
	/**
	 * @param GetDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws DatabaseException
	 * @throws NotFoundException
	 * @throws PermissionException|UnauthenticatedException
	 */
	public function run(GetDTO $dto): ResponseInterface
	{
		if (!is_null($dto->id)) {
			$jwtData = $this->getJwtData();
			
			if ($dto->id !== $jwtData->id && !in_array(PermissionEnum::UserRead, $jwtData->permissions, true)) {
				throw new PermissionException('You don\'t have permission to get data from this user');
			}
			
			$user = User::getById($dto->id);
			
			if (is_null($user)) {
				throw new NotFoundException('The requested user was not found');
			}
			
			$response = [
				"id"     => $user->getId(),
				"name"   => $user->getName(),
				"email"  => $user->getEmail(),
				"status" => $user->getStatus(),
			];
		} else {
			$data = array_map(
				static function (User $user): array {
					return [
						"id"     => $user->getId(),
						"name"   => $user->getName(),
						"email"  => $user->getEmail(),
						"status" => $user->getStatus(),
					];
				}, User::getAll()
			);
			
			$response = new PaginatedData($data, $dto->page ?? 1, 30);
		}
		
		return $this->replyRequest(body: $response);
	}
}