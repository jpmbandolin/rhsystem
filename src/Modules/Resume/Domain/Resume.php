<?php

namespace Modules\Resume\Domain;

use Modules\File\Domain\FileAbstract;
use Modules\Candidate\Domain\Candidate;
use Modules\Resume\Infra\ResumeRepository;
use Psr\Http\Message\UploadedFileInterface;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class Resume extends FileAbstract
{
	private int $candidateId;
	
	public function __construct(
		?int                              $fileId = null,
		string|UploadedFileInterface|null $file = null,
		?string                           $userFriendlyName = null,
		?string                           $type = null,
		?int                              $createdBy = null,
		?string                           $name = null,
		string|EntityStatusEnum|null      $status = null
	) {
		parent::__construct(file: $file, name: $name, status: $status);
		$this->fileId = $fileId;
		$this->userFriendlyName = $userFriendlyName;
		$this->type = $type;
		$this->createdBy = $createdBy;
	}
	
	/**
	 * @param int $fileId
	 *
	 * @return null|FileAbstract
	 * @throws DatabaseException
	 */
	public static function getByFileId(int $fileId): ?Resume
	{
		return ResumeRepository::getByResumeId($fileId);
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
	 * @param Candidate $candidate
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public function saveResume(Candidate $candidate): void
	{
		ResumeRepository::save($this, $candidate);
	}
}