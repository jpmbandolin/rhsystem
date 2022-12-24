<?php

namespace Modules\Candidate\Application\Photo\GetPhoto;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Type;

class GetDTO extends DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
	public int $candidateId;
    #[Type("integer", message: "The photoId parameter should be an integer")]
	public int $photoId;
}