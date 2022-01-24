<?php

namespace ApplicationBase\Infra\Exceptions;

class UnauthenticatedException extends AppException
{

	public function getHttpStatusCode(): int
	{
		return 401;
	}
}