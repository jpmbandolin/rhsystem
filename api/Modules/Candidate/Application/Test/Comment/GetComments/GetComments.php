<?php

namespace Modules\Candidate\Application\Test\Comment\GetComments;

use Modules\Test\Domain\Test;
use Modules\Comment\Domain\Comment;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Utilities\ArrayUtilities;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;

class GetComments extends ControllerAbstract
{
	use ArrayUtilities;
	
	/**
	 * @param GetCommentsDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws DatabaseException
	 */
	public function run(GetCommentsDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException("The requested candidate was not found");
		}
		
		/**
		 * @var bool|Test $test
		 */
		$test = self::find(
			$candidate->getTests(), static function (Test $test) use ($dto): bool {
				return $test->getFileId() === $dto->testId;
			}
		);
		
		if ($test === false) {
			throw new NotFoundException("The requested test does not exist or does not belong to the requested candidate.");
		}
		
		return $this->replyRequest(
			[
				"d" => array_map(
					static function (Comment $comment): array {
						return [
							"id"       => $comment->getId(),
							"authorId" => $comment->getAuthorId(),
							"comment"  => $comment->getComment(),
							"status"   => $comment->getStatus()->value
						];
					}, $test->getComments()
				),
			]
		);
	}
}