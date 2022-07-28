<?php

namespace Modules\Photo\Domain;

use Modules\File\Domain\FileAbstract;
use Modules\Photo\Infra\PhotoRepository;
use Psr\Http\Message\UploadedFileInterface;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class Photo extends FileAbstract
{
	private int $candidateId;
	
	public function __construct(?int $fileId = null, string|UploadedFileInterface|null $file = null, ?string $userFriendlyName = null, ?string $type = null, ?int $createdBy = null, ?string $name = null)
	{
		parent::__construct(file: $file, name: $name);
		$this->fileId = $fileId;
		$this->userFriendlyName = $userFriendlyName;
		$this->type = $type;
		$this->createdBy = $createdBy;
	}

	/**
	 * @return int
	 */
	public function getCandidateId(): int
	{
		return $this->candidateId;
	}
	
	/**
	 * @param int $candidateId
	 *
	 * @return Photo
	 */
	public function setCandidateId(int $candidateId): Photo
	{
		$this->candidateId = $candidateId;
		return $this;
	}
	
	/**
	 * @param int $fileId
	 *
	 * @return null|Photo
	 * @throws DatabaseException
	 */
	public static function getByFileId(int $fileId): ?Photo
	{
		return PhotoRepository::getByFileId($fileId);
	}
}