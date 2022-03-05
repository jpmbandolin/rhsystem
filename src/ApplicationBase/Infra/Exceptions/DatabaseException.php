<?php

namespace ApplicationBase\Infra\Exceptions;

class DatabaseException extends AppException
{

	public function getHttpStatusCode(): int
	{
		return 500;
	}
}