<?php

namespace Modules\Candidate\Application\Test\GetAllTests;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Type;

class GetAllTestsDTO extends DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
	public int $candidateId;
}