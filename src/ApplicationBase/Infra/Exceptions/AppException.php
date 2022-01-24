<?php

namespace ApplicationBase\Infra\Exceptions;

abstract class AppException extends \Exception
{
	abstract public function getHttpStatusCode():int;
}