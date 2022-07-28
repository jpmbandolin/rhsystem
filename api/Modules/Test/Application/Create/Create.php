<?php

namespace Modules\Test\Application\Create;

use Throwable;
use Modules\Test\Domain\Test;
use ApplicationBase\Infra\Database;
use Psr\Http\Message\ResponseInterface;
use ApplicationBase\Infra\Exceptions\AppException;
use Psr\Http\Message\ServerRequestInterface as Request;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;
use ApplicationBase\Infra\Exceptions\UnauthenticatedException;

class Create extends ControllerAbstract
{
	/**
	 * @param Request $request
	 *
	 * @return ResponseInterface
	 * @throws AppException
	 * @throws RuntimeException
	 * @throws UnauthenticatedException
	 * @throws Throwable
	 */
	public function run(Request $request): ResponseInterface
	{
		$file = self::getUploadedFile($request);

		$test = new Test(file: $file, userFriendlyName: $file->getClientFilename(), type: $file->getClientMediaType(), createdBy: $this->getJwtData()->id);

		try {
			Database::getInstance()->beginTransaction();
			$test->save();
			Database::getInstance()->commit();
		} catch (Throwable $t) {
			Database::getInstance()->rollBack();
			throw $t;
		}

		return $this->replyRequest(body: ["id" => $test->getFileId()]);
	}
}