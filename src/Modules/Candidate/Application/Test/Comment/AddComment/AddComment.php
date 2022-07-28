<?php

namespace Modules\Candidate\Application\Test\Comment\AddComment;

use Throwable;
use Modules\Test\Domain\Test;
use Modules\Comment\Domain\Comment;
use ApplicationBase\Infra\Database;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Utilities\ArrayUtilities;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\UnauthenticatedException;

class AddComment extends ControllerAbstract
{
	use ArrayUtilities;
	
	/**
	 * @param AddCommentDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws DatabaseException
	 * @throws UnauthenticatedException
	 * @throws Throwable
	 */
	public function run(AddCommentDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException("The requested candidate was not found");
		}
		
		$tests = $candidate->getTests();
		
		/**
		 * @var bool|Test $test
		 */
		$test = self::find(
			$tests, static function (Test $test) use ($dto): bool {
				return $test->getFileId() === $dto->testId;
			}
		);
		
		if ($test === false) {
			throw new NotFoundException("The requested test does not exist or does not belong to the requested candidate.");
		}
		
		try {
			Database::getInstance()->beginTransaction();
			$comment = new Comment(comment: $dto->comment, authorId: $this->getJwtData()->id);
			$comment->save();
			$test->addComment($comment);
			Database::getInstance()->commit();
		} catch (Throwable $t) {
			Database::getInstance()->rollBack();
			throw $t;
		}
		
		return $this->replyRequest(status: 204);
	}
}