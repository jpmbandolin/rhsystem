<?php

namespace Modules\Candidate\Application\GetAllTests;

use Modules\Test\Domain\Test;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class GetAllTests extends ControllerAbstract
{
	/**
	 * @param GetAllTestsDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws DatabaseException
	 */
	public function run(GetAllTestsDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);

		if (is_null($candidate)) {
			throw new NotFoundException("The requested candidate was not found");
		}

		return $this->replyRequest(
			[
				"d" => array_map(
					static function (Test $test): array {
						return [
							"id"               => $test->getFileId(),
							"userFriendlyName" => $test->getUserFriendlyName(),
							"type"             => $test->getType(),
							"result"           => $test->getResult(),
						];
					}, $candidate->getTests()
				),
			]
		);
	}
}