<?php

namespace Modules\User\Application\Get;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Attributes\OptionalAttribute;;

use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

class GetDTO extends DTOAbstract
{
	#[OptionalAttribute]
    #[AtLeastOneOf([
        new IsNull,
        new All([
            new Type("integer"),
            new Positive
        ])
    ], message: "The ID parameter should either be a positive integer or null")]
	public ?int $id = null;

	#[OptionalAttribute]
    #[AtLeastOneOf([
        new IsNull,
        new Type("integer"),
        new PositiveOrZero
    ], message: "The page parameter should either be a positive integer, zero or null")]
	public ?int $page = null;
}