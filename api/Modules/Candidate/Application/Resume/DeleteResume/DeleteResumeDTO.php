<?php

namespace Modules\Candidate\Application\Resume\DeleteResume;

use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class DeleteResumeDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
    #[Positive(message: "The Candidate ID should be a positive number")]
	public int $candidateId;
    #[Type("integer", message: "The resumeId parameter should be an integer")]
    #[Positive(message: "The Resume ID should be a positive number")]
	public int $resumeId;
}