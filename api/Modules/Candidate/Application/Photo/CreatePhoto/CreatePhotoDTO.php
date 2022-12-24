<?php

namespace Modules\Candidate\Application\Photo\CreatePhoto;

use Symfony\Component\Validator\Constraints\Type;

class CreatePhotoDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
	public int $candidateId;
}