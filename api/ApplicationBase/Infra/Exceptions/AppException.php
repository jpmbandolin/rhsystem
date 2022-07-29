<?php

namespace ApplicationBase\Infra\Exceptions;

abstract class AppException extends \Exception
{
	public function getDetailedErrorMessage(){
		$exceptionStack = [$this];
		$previous = $this->getPrevious();

		while($previous){
			$exceptionStack[]	= $previous;
			$previous			= $previous->getPrevious();
		}

		$exceptionStack = array_reverse($exceptionStack);

		$detailedMessage = [
			"message"	=> $this->getMessage(),
			"trace"		=> $this->getTraceAsString()
		];
		foreach ($exceptionStack as $index=>$exception) {
			$detailedMessage['message'] .= " #" . $index+1 . " " . $exception->getMessage();
		}

		return $detailedMessage;
	}

	abstract public function getHttpStatusCode():int;
}