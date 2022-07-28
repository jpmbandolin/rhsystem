<?php

namespace Modules\Resume\Domain;

use Modules\File\Domain\FileAbstract;
use Modules\Resume\Infra\ResumeRepository;
use Psr\Http\Message\UploadedFileInterface;
use ApplicationBase\Infra\Exceptions\AppException;
use ApplicationBase\Infra\Exceptions\NotImplementedException;

class Resume extends FileAbstract
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
	 * @return Resume
	 */
	public function setCandidateId(int $candidateId): Resume
	{
		$this->candidateId = $candidateId;
		return $this;
	}
	
	/**
	 * @return int
	 * @throws AppException
	 */
	public function save(): int
	{
		return parent::save([ResumeRepository::class, 'save']);
	}
	
	/**
	 * @param int $fileId
	 *
	 * @return null|FileAbstract
	 * @throws NotImplementedException
	 */
	public static function getByFileId(int $fileId): ?FileAbstract
	{
		throw new NotImplementedException("Not implemented");
	}
}