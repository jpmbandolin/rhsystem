<?php

namespace Modules\Candidate\Application\Create;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Attributes\OptionalAttribute;
use PHPUnit\Framework\Constraint\IsNull;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class CreateDTO extends DTOAbstract
{
    #[Type("string", message: "The name parameter should be a string")]
    #[Length(
        min: 3,
        max: 150,
        minMessage: "The name parameter should have at least 3 characters",
        maxMessage: "The name parameter should have at most 150 characters"
    )]
	public string $name;
	
	#[OptionalAttribute]
    #[AtLeastOneOf([
        new IsNull(),
        new All([
            new Email(),
            new Length(max: 150)
        ])
    ], message: "The email field must be null or a valid email string with at most 150 characters")]
	public ?string $email = null;
}