<?php

namespace Modules\Candidate\Application\Resume\DeleteResume;

use Modules\Resume\Domain\Resume;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Utilities\ArrayUtilities;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\InvalidValueException;

class DeleteResume extends ControllerAbstract
{
	use ArrayUtilities;
	
	/**
	 * @param DeleteResumeDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws DatabaseException
	 * @throws InvalidValueException
	 */
	public function run(DeleteResumeDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException('The requested candidate was not found');
		}
		
		$resumeList = $candidate->getResumes();
		
		/**
		 * @var bool|Resume $resume
		 */
		$resume = self::find(
			$resumeList, static function (Resume $resume) use ($dto): bool {
				return $resume->getFileId() === $dto->resumeId;
			}
		);
		
		if ($resume === false) {
			throw new NotFoundException("This user does not have the requested resume");
		}
		
		$resume->setStatus(EntityStatusEnum::Deleted);
		$resume->updateFileStatus();
		
		return $this->replyRequest(status: 204);
	}
}