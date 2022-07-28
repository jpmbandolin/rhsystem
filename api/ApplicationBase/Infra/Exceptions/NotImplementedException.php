<?php

namespace ApplicationBase\Infra\Exceptions;

class NotImplementedException extends AppException
{
	
	public function getHttpStatusCode(): int
	{
		return 501;
	}
}