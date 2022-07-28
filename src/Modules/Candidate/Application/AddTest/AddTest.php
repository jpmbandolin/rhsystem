<?php

namespace Modules\Candidate\Application\AddTest;

use Throwable;
use Modules\Test\Domain\Test;
use ApplicationBase\Infra\Database;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{NotFoundException,
	DatabaseException,
	PermissionException,
	UnauthenticatedException
};

class AddTest extends ControllerAbstract
{
	/**
	 * @param AddTestDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws DatabaseException
	 * @throws NotFoundException
	 * @throws PermissionException
	 * @throws UnauthenticatedException|Throwable
	 */
	public function run(AddTestDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException('The requested candidate was not found');
		}
		
		$test = Test::getByFileId($dto->testId);
		
		if (is_null($test)) {
			throw new NotFoundException('The requested test was not found');
		}
		
		if ($test->getCreatedBy() !== $this->getJwtData()->id) {
			throw new PermissionException("You dont have permission to add this test to a candidate");
		}
		
		$test->setResult($dto->result);
		
		try {
			Database::getInstance()->beginTransaction();
			//$test->patch($candidate);
			$candidate->addTest($test);
			Database::getInstance()->commit();
		} catch (Throwable $t) {
			Database::getInstance()->rollBack();
			throw $t;
		}
		
		return $this->replyRequest(status: 204);
	}
}