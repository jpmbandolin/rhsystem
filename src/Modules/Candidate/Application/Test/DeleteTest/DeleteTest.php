<?php

namespace Modules\Candidate\Application\Test\DeleteTest;

use Modules\Test\Domain\Test;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Utilities\ArrayUtilities;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\InvalidValueException;

class DeleteTest extends ControllerAbstract
{
	use ArrayUtilities;
	
	/**
	 * @param DeleteTestDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws DatabaseException
	 * @throws InvalidValueException
	 * @throws NotFoundException
	 */
	public function run(DeleteTestDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException('The requested candidate was not found');
		}
		
		$testList = $candidate->getTests();
		
		/**
		 * @var bool|Test $test
		 */
		$test = self::find(
			$testList, static function (Test $resume) use ($dto): bool {
				return $resume->getFileId() === $dto->testId;
			}
		);
		
		if ($test === false) {
			throw new NotFoundException("This user does not have the requested test");
		}
		
		$test->setStatus(EntityStatusEnum::Deleted);
		$test->updateFileStatus();
		
		return $this->replyRequest(status: 204);
	}
}