<?php

namespace ApplicationBase\Infra\Exceptions;

class PermissionException extends AppException
{

	public function getHttpStatusCode(): int
	{
		return 403;
	}
}