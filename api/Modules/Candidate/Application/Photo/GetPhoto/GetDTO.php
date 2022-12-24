<?php

namespace Modules\Candidate\Application\Photo\GetPhoto;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class GetDTO extends DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
    #[Positive(message: "The Candidate ID should be a positive number")]
	public int $candidateId;
    #[Type("integer", message: "The photoId parameter should be an integer")]
    #[Positive(message: "The photo ID should be a positive number")]
	public int $photoId;
}