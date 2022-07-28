<?php

namespace ApplicationBase\Infra\Exceptions;

class NotFoundException extends AppException
{

	public function getHttpStatusCode(): int
	{
		return 404;
	}
}