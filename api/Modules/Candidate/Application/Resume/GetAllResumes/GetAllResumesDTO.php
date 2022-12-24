<?php

namespace Modules\Candidate\Application\Resume\GetAllResumes;

use Symfony\Component\Validator\Constraints\Type;

class GetAllResumesDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
	public int $candidateId;
}