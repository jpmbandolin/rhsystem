<?php

namespace Modules\Candidate\Application\Get;

use Psr\Http\Message\ResponseInterface;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\Exceptions\{DatabaseException, BadRequestException, NotFoundException};
use ApplicationBase\Infra\Abstracts\ControllerAbstract;

class Get extends ControllerAbstract
{
	/**
	 * @param GetDTO $dto
	 *
	 * @return ResponseInterface
	 * @throws BadRequestException
	 * @throws DatabaseException|NotFoundException
	 */
	public function run(GetDTO $dto): ResponseInterface
	{
		$candidates = [];
		$dtoIdIsNull = is_null($dto->id);
		$dtoPartialNameIsNull = is_null($dto->partialName);

		if ($dtoIdIsNull xor $dtoPartialNameIsNull) {
			if (!$dtoIdIsNull) {
				$candidates[] = Candidate::getById($dto->id) ?? throw new NotFoundException("The requested candidate was not found");
			}else{
				$candidates = Candidate::searchByName($dto->partialName);
			}
		}else if ($dtoIdIsNull && $dtoPartialNameIsNull){
			$candidates = Candidate::getAll();
		}else{
			throw new BadRequestException("You can only inform the id or the partial name, but not both");
		}

		$response = array_map(static function (Candidate $candidate){
			return [
				"id"        => $candidate->getId(),
				"name"      => $candidate->getName(),
				"email"     => $candidate->getEmail(),
				"createdBy" => $candidate->getCreatedBy(),
				"photoId"   => $candidate->getPhotoId()
			];
		}, $candidates);
		
		return $this->replyRequest(body: $dtoIdIsNull ? ["d"=>$response] : $response[0]);
	}
}