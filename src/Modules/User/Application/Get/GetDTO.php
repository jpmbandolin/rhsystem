<?php

namespace Modules\User\Application\Get;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Attributes\OptionalAttribute;

class GetDTO extends DTOAbstract
{
	#[OptionalAttribute]
	public ?int $id = null;

	#[OptionalAttribute]
	public ?int $page = null;
}