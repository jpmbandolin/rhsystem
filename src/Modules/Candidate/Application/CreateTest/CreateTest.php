<?php

namespace Modules\Candidate\Application\CreateTest;

use Throwable;
use Modules\Test\Domain\Test;
use ApplicationBase\Infra\Database;
use Modules\Comment\Domain\Comment;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Exceptions\AppException;
use Psr\Http\Message\ServerRequestInterface as Request;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Exceptions\UnauthenticatedException;

class CreateTest extends ControllerAbstract
{
	/**
	 * @param CreateTestDTO $dto
	 * @param Request       $request
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws RuntimeException
	 * @throws AppException
	 * @throws DatabaseException
	 * @throws UnauthenticatedException
	 * @throws Throwable
	 */
	public function run(CreateTestDTO $dto, Request $request): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);

		if (is_null($candidate)) {
			throw new NotFoundException('The requested candidate was not found');
		}

		$file = self::getUploadedFile($request);
		$test = new Test(file: $file, userFriendlyName: $file->getClientFilename(), type: $file->getClientMediaType(), createdBy: $this->getJwtData()->id);
		$test->setResult($dto->result);

		try {
			Database::getInstance()->beginTransaction();

			$test->save();

			foreach ($dto->comments as $comment) {
				$comment = new Comment($comment->comment, $this->getJwtData()->id);
				$comment->save();

				$test->addComment($comment);
			}

			$candidate->addTest($test);
			Database::getInstance()->commit();
		} catch (Throwable $t) {
			Database::getInstance()->rollBack();
			throw $t;
		}

		return $this->replyRequest(status: 201);
	}
}