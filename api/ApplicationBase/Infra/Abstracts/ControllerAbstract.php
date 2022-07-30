<?php


namespace ApplicationBase\Infra\Abstracts;


use ApplicationBase\Infra\{Exceptions\RuntimeException, JWT, JWTPayload, PaginatedData};
use Modules\File\Domain\FileAbstract;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use ApplicationBase\Infra\Exceptions\UnauthenticatedException;

abstract class ControllerAbstract
{
	/**
	 * @param null|Request $request
	 *
	 * @return UploadedFileInterface
	 * @throws RuntimeException
	 */
	protected static function getUploadedFile(?Request $request = null): UploadedFileInterface{
		$files = $request?->getUploadedFiles();

		$file = $files['file'];
		
		if ($file?->getError() !== UPLOAD_ERR_OK) {
			throw new RuntimeException("Error retrieving the uploaded file");
		}
		
		return $file;
	}
	
	/**
	 * @param ResponseInterface|null $response
	 * @param mixed|null $body
	 * @param int $status
	 * @return ResponseInterface
	 */
	final protected function replyRequest(mixed $body = null, int $status = 200, ResponseInterface $response = null): ResponseInterface
	{
		if($response === null){
			$response = new Response;
		}

		if (is_a($body, PaginatedData::class)){
			$body = [
				"d"=>$body->getPaginatedData(),
				"pages"=>$body->getTotalPages(),
				"page"=>$body->getPage()
			];
		}

		$resBody = $response->getBody();
		$resBody->write(json_encode($body));

		if ($status === 201){
			return $response->withStatus($status);
		}

		return $response->withHeader('Content-Type', 'application/json')->withBody($resBody)->withStatus($status);
	}
	
	/**
	 * @param FileAbstract $file
	 * @param int $status
	 * @param ResponseInterface|null $response
	 * @return ResponseInterface
	 * @throws RuntimeException
	 */
	final protected function replyRequestWithFile(FileAbstract $file, int $status = 200, ResponseInterface $response = null): ResponseInterface{
		if (is_null($response)){
			$response = new Response;
		}
		
		if (is_null($file->getFileId())){
			throw new RuntimeException("The file must be saved to be returned by the API");
		}
		
		$finalResponse = $response
			->withHeader('Content-Type', $file->getType())
			->withHeader('Content-Disposition', 'inline; filename="' . $file->getName() . '"')
			->withHeader('etag', $file->getFileId());
		
		$responseBody = $finalResponse->getBody();
		$responseBody->write($file->getFile());
		
		return $finalResponse->withBody($responseBody)->withStatus($status);
	}
	
	/**
	 * @return JWTPayload
	 * @throws UnauthenticatedException
	 */
	final public static function getJwtData():JWTPayload{
		global $container;
		try{
			return new JWTPayload($container->get(JWT::class));
		}catch (\Throwable $t){
			throw new UnauthenticatedException("Error getting user payload", previous: $t);
		}
	}
	
	/**
	 * @return null|JWTPayload
	 */
	public static function getCurrentUserData(): ?JWTPayload
	{
		try {
			return self::getJwtData();
		} catch (\Throwable) {
			return null;
		}
	}
}