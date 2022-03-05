<?php

namespace Modules\User\Application\Create;

class CreateDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
	public string $name;
	public string $password;
	public string $confirmPassword;
	public string $email;
}