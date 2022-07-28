<?php

namespace Modules\Test\Application\AddComment;

use Modules\Test\Domain\Test;
use Modules\Comment\Domain\Comment;
use ApplicationBase\Infra\Database;
use Psr\Http\Message\ResponseInterface;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\UnauthenticatedException;


class AddComment extends ControllerAbstract
{
	/**
	 * @param AddCommentDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws DatabaseException
	 * @throws NotFoundException
	 * @throws UnauthenticatedException
	 */
	public function run(AddCommentDTO $dto): ResponseInterface
	{
		$test = Test::getByFileId($dto->id);
		
		if (is_null($test)) {
			throw new NotFoundException("The requested test was not found");
		}
		
		$comment = new Comment(comment: $dto->comment, authorId: $this->getJwtData()->id);
		
		try {
			Database::getInstance()->beginTransaction();
			$comment->save();
			$test->addComment($comment);
			Database::getInstance()->commit();
		} catch (DatabaseException $e) {
			Database::getInstance()->rollBack();
			throw $e;
		}
		
		return $this->replyRequest(status: 204);
	}
}