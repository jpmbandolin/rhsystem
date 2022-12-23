<?php

namespace Modules\Candidate\Application\Get;

use ApplicationBase\Infra\Attributes\OptionalAttribute;
use Symfony\Component\Validator\Constraints\{AtLeastOneOf, Length, Type, IsNull};

class GetDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
    #[AtLeastOneOf(constraints: [
        new IsNull,
        new Type(type: "integer")
    ], message: "The ID must either be null or an integer.")]
    #[OptionalAttribute]
    public ?int $id = null;

    #[AtLeastOneOf(constraints: [
        new Length(min: 3, max: 150),
        new IsNull
    ], message: "The partialName must be a string greater than 3 and smaller than 150.")]
    #[OptionalAttribute]
    public ?string $partialName = null;
}