<?php

namespace Modules\Candidate\Application\Test\GetTest;

use Modules\Test\Domain\Test;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Utilities\ArrayUtilities;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;

class GetTest extends ControllerAbstract
{
	use ArrayUtilities;

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

		$tests = $candidate->getTests();

		$test = self::find($tests, static function(Test $test) use ($dto): bool{
			return $test->getFileId() === $dto->testId;
		});

		if ($test === false) {
			throw new NotFoundException("This user does not have this test");
		}

		return $this->replyRequestWithFile($test);
	}
}