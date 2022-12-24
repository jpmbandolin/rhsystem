<?php

namespace Modules\Candidate\Application\Test\Comment\GetComments;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class GetCommentsDTO extends DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
    #[Positive(message: "The Candidate ID should be a positive number")]
	public int $candidateId;
    #[Type("integer", message: "The testId parameter should be an integer")]
    #[Positive(message: "The test ID should be a positive number")]
	public int $testId;
}