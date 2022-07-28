<?php

namespace Modules\Candidate\Application\GetPhoto;

use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\{RuntimeException, DatabaseException, NotFoundException, PermissionException};

class Get extends ControllerAbstract
{
	/**
	 * @param GetDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws DatabaseException|RuntimeException|PermissionException|NotFoundException
	 */
	public function run(GetDTO $dto): ResponseInterface{
		$candidate = Candidate::getById($dto->candidateId);

		if (is_null($candidate)){
			throw new NotFoundException("The requested candidate was not found");
		}

		$photo = $candidate->getPhoto();

		if (is_null($photo)){
			throw new NotFoundException("This user does not have a photo");
		}

		if ($photo->getFileId() !== $dto->photoId){
			throw new PermissionException("You don't have permission to get this image");
		}

		return $this->replyRequestWithFile($photo);
	}
}