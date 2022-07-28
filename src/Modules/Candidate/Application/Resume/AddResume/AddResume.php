<?php

namespace Modules\Candidate\Application\Resume\AddResume;

use Throwable;
use Modules\Resume\Domain\Resume;
use ApplicationBase\Infra\Database;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\PermissionException;
use ApplicationBase\Infra\Exceptions\UnauthenticatedException;

class AddResume extends ControllerAbstract
{
	/**
	 * @param AddResumeDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws PermissionException
	 * @throws DatabaseException
	 * @throws UnauthenticatedException
	 * @throws Throwable
	 */
	public function run(AddResumeDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException('The requested candidate was not found');
		}
		
		$resume = Resume::getByFileId($dto->resumeId);
		
		if (is_null($resume)) {
			throw new NotFoundException('The requested resume was not found');
		}
		
		if ($resume->getCreatedBy() !== $this->getJwtData()->id) {
			throw new PermissionException("You dont have permission to add this resume to a candidate");
		}
		
		try {
			Database::getInstance()->beginTransaction();
			$candidate->addResume($resume);
			Database::getInstance()->commit();
		} catch (Throwable $t) {
			Database::getInstance()->rollBack();
			throw $t;
		}
		
		return $this->replyRequest(status: 204);
	}
}