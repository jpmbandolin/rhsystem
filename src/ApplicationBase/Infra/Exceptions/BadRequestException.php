<?php

namespace ApplicationBase\Infra\Exceptions;

class BadRequestException extends AppException
{
	
	public function getHttpStatusCode(): int
	{
		return 400;
	}
}