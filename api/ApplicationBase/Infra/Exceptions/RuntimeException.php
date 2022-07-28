<?php

namespace ApplicationBase\Infra\Exceptions;

class RuntimeException extends AppException
{

	public function getHttpStatusCode(): int
	{
		return 500;
	}
}