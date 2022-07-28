<?php

namespace Modules\Candidate\Application\Resume\GetResume;

use Modules\Resume\Domain\Resume;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Utilities\ArrayUtilities;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class GetResume extends ControllerAbstract
{
	use ArrayUtilities;
	
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
		
		
		$resumeList = $candidate->getResumes();

		$resume = self::find($resumeList, static function (Resume $resume) use ($dto): bool{
				return $resume->getFileId() === $dto->resumeId;
		});

		if ($resume === false) {
			throw new NotFoundException("This user does not have the requested resume");
		}

		return $this->replyRequestWithFile($resume);
	}
}