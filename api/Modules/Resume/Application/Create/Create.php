<?php

namespace Modules\Resume\Application\Create;

use Throwable;
use Modules\Resume\Domain\Resume;
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
	 * @throws Throwable
	 * @throws AppException
	 * @throws RuntimeException
	 * @throws UnauthenticatedException
	 */
	public function run(Request $request): ResponseInterface
	{
		$file = self::getUploadedFile($request);

		$resume = new Resume(file: $file, userFriendlyName: $file->getClientFilename(), type: $file->getClientMediaType(), createdBy: self::getJwtData()->id);

		try {
			Database::getInstance()->beginTransaction();
			$resume->save();
			Database::getInstance()->commit();
		} catch (Throwable $t) {
			Database::getInstance()->rollBack();
			throw $t;
		}

		return $this->replyRequest(body: ["id" => $resume->getFileId()]);
	}
}