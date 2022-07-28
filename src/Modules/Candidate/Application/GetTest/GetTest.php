<?php

namespace Modules\Candidate\Application\GetTest;

use Modules\Test\Domain\Test;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;

class GetTest extends ControllerAbstract
{
	/**
	 * @param GetTestDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws DatabaseException
	 * @throws RuntimeException
	 */
	public function run(GetTestDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException("The requested candidate was not found");
		}
		
		$test = Test::getByFileId($dto->testId);
		
		if (is_null($test)) {
			throw new NotFoundException("This user does not have this test");
		}
		
		return $this->replyRequestWithFile($test);
	}
}