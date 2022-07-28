<?php

namespace Modules\Candidate\Application\Photo\CreatePhoto;

use Throwable;
use Modules\Photo\Domain\Photo;
use ApplicationBase\Infra\Database;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use Psr\Http\Message\ServerRequestInterface as Request;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{AppException,
	RuntimeException,
	DatabaseException,
	NotFoundException,
	UnauthenticatedException};

class CreatePhoto extends ControllerAbstract
{
	/**
	 * @param CreatePhotoDTO $dto
	 * @param Request        $request
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws RuntimeException
	 * @throws AppException
	 * @throws DatabaseException
	 * @throws UnauthenticatedException|Throwable
	 */
	public function run(CreatePhotoDTO $dto, Request $request): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException('The requested candidate was not found');
		}
		
		$file = self::getUploadedFile($request);
		
		$photo = new Photo(file: $file, userFriendlyName: $file->getClientFilename(), type: $file->getClientMediaType(), createdBy: $this->getJwtData()->id);
		
		try {
			Database::getInstance()->beginTransaction();
			$photo->save();

			$candidate->setPhotoId($photo->getFileId())->save();
			Database::getInstance()->commit();
		} catch (Throwable $t) {
			Database::getInstance()->rollBack();
			throw $t;
		}
		
		
		return $this->replyRequest(status: 201);
	}
}