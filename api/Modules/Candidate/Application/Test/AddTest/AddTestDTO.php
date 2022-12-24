<?php

namespace Modules\Candidate\Application\Test\AddTest;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class AddTestDTO extends DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
	public int $candidateId;
    #[Type("integer", message: "The testId parameter should be an integer")]
	public int $testId;
    #[Type("string", message: "The result parameter should be a string")]
    #[Length(
        min: 3,
        max: 50,
        minMessage: "The result parameter should have at least 3 characters",
        maxMessage: "The result parameter should have at most 50 characters"
    )]
	public string $result;
}