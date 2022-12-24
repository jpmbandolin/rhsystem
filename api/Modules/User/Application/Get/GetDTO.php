<?php

namespace Modules\User\Application\Get;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Attributes\OptionalAttribute;;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\Type;

class GetDTO extends DTOAbstract
{
	#[OptionalAttribute]
    #[AtLeastOneOf([
        new IsNull,
        new Type("integer")
    ], message: "The ID parameter should either be an integer or null")]
	public ?int $id = null;

	#[OptionalAttribute]
    #[AtLeastOneOf([
        new IsNull,
        new Type("integer")
    ], message: "The page parameter should either be an integer or null")]
	public ?int $page = null;
}