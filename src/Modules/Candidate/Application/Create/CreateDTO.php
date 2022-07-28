<?php

namespace Modules\Candidate\Application\Create;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Attributes\OptionalAttribute;

class CreateDTO extends DTOAbstract
{
	public string $name;
	
	#[OptionalAttribute]
	public ?string $email = null;
}