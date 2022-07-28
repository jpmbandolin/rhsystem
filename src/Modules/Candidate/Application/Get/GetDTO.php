<?php

namespace Modules\Candidate\Application\Get;

use ApplicationBase\Infra\Attributes\OptionalAttribute;

class GetDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
	#[OptionalAttribute]
	public ?int $id = null;

	#[OptionalAttribute]
	public ?string $partialName = null;
}