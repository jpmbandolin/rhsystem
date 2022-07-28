<?php

namespace Modules\User\Application\Create;

class CreateDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
	public ?string $name = null;
	public ?string $password = null;
	public ?string $confirmPassword = null;
	public ?string $email = null;
}