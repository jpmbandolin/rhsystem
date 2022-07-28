<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Enums\PermissionEnum;

class JWTPayload
{
	public int $id;
	public string $name;
	public string $email;
	public array $permissions;
	
	public function __construct(object $jwtRawObject){
		$this->id           = $jwtRawObject->id;
		$this->name         = $jwtRawObject->name;
		$this->email        = $jwtRawObject->email;
	}
}