<?php

namespace Modules\Candidate\Application\Resume\GetAllResumes;

use Modules\Resume\Domain\Resume;
use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Exceptions\NotFoundException;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;

class GetAllResumes extends ControllerAbstract
{
	/**
	 * @param GetAllResumesDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws NotFoundException
	 * @throws DatabaseException
	 */
	public function run(GetAllResumesDTO $dto): ResponseInterface
	{
		$candidate = Candidate::getById($dto->candidateId);
		
		if (is_null($candidate)) {
			throw new NotFoundException("The requested candidate was not found");
		}
		
		return $this->replyRequest(
			[
				"d" => array_map(
					static function (Resume $resume): array {
						return [
							"id"               => $resume->getFileId(),
							"userFriendlyName" => $resume->getUserFriendlyName(),
							"type"             => $resume->getType(),
							"status"           => $resume->getStatus()->value,
						];
					}, $candidate->getResumes()
				),
			]
		);
	}
}