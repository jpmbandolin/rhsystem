<?php

namespace ApplicationBase\Infra\Vo;

use ApplicationBase\Infra\Abstracts\ViewObjectAbstract;
use ApplicationBase\Infra\Exceptions\InvalidValueException;

class Email extends ViewObjectAbstract
{
	/**
	 * @param string $email
	 * @throws InvalidValueException
	 */
	public function __construct(private string $email){
		if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
			throw new InvalidValueException('Invalid Email Value Supplied.');
		}
	}

	public function __toString()
	{
		return $this->email;
	}
}