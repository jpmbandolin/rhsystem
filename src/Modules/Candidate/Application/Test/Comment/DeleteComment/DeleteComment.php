<?php

namespace Modules\Candidate\Application\Test\Comment\DeleteComment;

use Modules\Test\Domain\Test;
use Modules\Comment\Domain\Comment;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Utilities\ArrayUtilities;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\InvalidValueException;

class DeleteComment extends ControllerAbstract
{
	use ArrayUtilities;
	
	/**
	 * @param DeleteCommentDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws DatabaseException
	 * @throws InvalidValueException
	 * @throws NotFoundException
	 */
	public function run(DeleteCommentDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException('The requested candidate was not found');
		}
		
		/**
		 * @var bool|Test $test
		 */
		$test = self::find(
			$candidate->getTests(), static function (Test $resume) use ($dto): bool {
				return $resume->getFileId() === $dto->testId;
			}
		);
		
		if ($test === false) {
			throw new NotFoundException("This user does not have the requested test");
		}
		
		/**
		 * @var bool|Comment $comment
		 */
		$comment = self::find(
			$test->getComments(), static function (Comment $comment) use ($dto): bool {
				return $comment->getId() === $dto->commentId;
			}
		);
		
		if ($comment === false) {
			throw new NotFoundException("The request comment does not exist or doesnt belong to the requested test");
		}

		$comment->setStatus(EntityStatusEnum::Deleted);
		$comment->updateStatus();

		return $this->replyRequest(status: 204);
	}
}