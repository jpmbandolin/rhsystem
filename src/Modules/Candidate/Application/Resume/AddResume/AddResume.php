<?php

namespace Modules\Candidate\Application\Resume\AddResume;

use Modules\Resume\Domain\Resume;
use ApplicationBase\Infra\Database;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\PermissionException;

class AddResume extends \ApplicationBase\Infra\Abstracts\ControllerAbstract
{
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
		} catch (\Throwable $t) {
			Database::getInstance()->rollBack();
			throw $t;
		}

		return $this->replyRequest(status: 204);
	}
}