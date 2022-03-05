<?php


namespace Modules\User\Application\Login;


use ApplicationBase\Infra\Abstracts\DTOAbstract;

class LoginDTO extends DTOAbstract
{
	public ?string $login		= null;
	public ?string $password	= null;
}