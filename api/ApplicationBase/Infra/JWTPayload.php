<?php

namespace ApplicationBase\Infra;


class JWTPayload
{
	public int $id;
	public string $name;
	public string $email;
	
	public function __construct(object $jwtRawObject){
		$this->id           = $jwtRawObject->id;
		$this->name         = $jwtRawObject->name;
		$this->email        = $jwtRawObject->email;
	}
}