<?php

namespace ApplicationBase\Infra\Exceptions;

class InvalidValueException extends AppException
{

	public function getHttpStatusCode(): int
	{
		return 400;
	}
}