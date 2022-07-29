<?php

namespace Modules\Candidate\Application\Create;

use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{DatabaseException, UnauthenticatedException};

class Create extends ControllerAbstract
{
	/**
	 * @param CreateDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws UnauthenticatedException|DatabaseException
	 */
	public function run(CreateDTO $dto): ResponseInterface
	{
		(new Candidate(name: $dto->name, email: $dto->email, createdBy: self::getJwtData()->id))
			->save();

		return $this->replyRequest(status: 201);
	}
}