<?php

namespace ApplicationBase\Infra\Exceptions;

class BusinessException extends AppException
{

	public function getHttpStatusCode(): int
	{
		return 400;
	}
}