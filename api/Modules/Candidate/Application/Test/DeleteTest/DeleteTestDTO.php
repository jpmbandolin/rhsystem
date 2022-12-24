<?php

namespace Modules\Candidate\Application\Test\DeleteTest;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Type;

class DeleteTestDTO extends DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
	public int $candidateId;
    #[Type("integer", message: "The testId parameter should be an integer")]
	public int $testId;
}