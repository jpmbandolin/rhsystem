<?php

namespace Modules\Candidate\Application\GetResume;

use Modules\Resume\Domain\Resume;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class GetResume extends ControllerAbstract
{
	/**
	 * @param GetResumeDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws DatabaseException
	 * @throws RuntimeException
	 */
	public function run(GetResumeDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException("The requested candidate was not found");
		}
		
		$resume = Resume::getByFileId($dto->resumeId);
		
		if (is_null($resume)) {
			throw new NotFoundException("This user does not have a resume");
		}
		
		return $this->replyRequestWithFile($resume);
	}
}