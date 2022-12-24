<?php

namespace Modules\Candidate\Application\Resume\AddResume;

use Symfony\Component\Validator\Constraints\Type;

class AddResumeDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
	public int $candidateId;
    #[Type("integer", message: "The resumeId parameter should be an integer")]
	public int $resumeId;
}