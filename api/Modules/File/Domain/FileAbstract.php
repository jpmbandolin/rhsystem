<?php

namespace Modules\File\Domain;

use Modules\File\Infra\FileRepository;
use Psr\Http\Message\UploadedFileInterface;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Exceptions\InvalidValueException;

abstract class FileAbstract
{
	use Actions;
	
	protected ?string $name;
	protected ?EntityStatusEnum $status;
	
	/**
	 * @param null|int                          $fileId
	 * @param null|UploadedFileInterface|string $file
	 * @param null|string                       $userFriendlyName
	 * @param null|string                       $type
	 * @param null|int                          $createdBy
	 * @param null|string                       $name
	 * @param null|string|EntityStatusEnum      $status
	 *
	 * @throws InvalidValueException
	 */
	public function __construct(
		protected ?int                              $fileId = null,
		protected UploadedFileInterface|string|null $file = null,
		protected ?string                           $userFriendlyName = null,
		protected ?string                           $type = null,
		protected ?int                              $createdBy = null,
		?string                                     $name = null,
		string|EntityStatusEnum|null                $status = null
	) {
		if (is_a($this->file, UploadedFileInterface::class)) {
			$fileStream = $this->file->getStream();
			$this->file = $fileStream->read($fileStream->getSize());
		}
		$this->setStatus($status);
		$this->name = $name;
	}
	
	/**
	 * @return null|EntityStatusEnum
	 */
	public function getStatus(): EntityStatusEnum|null
	{
		return $this->status;
	}
	
	/**
	 * @param null|EntityStatusEnum|string $status
	 *
	 * @return FileAbstract
	 * @throws InvalidValueException
	 */
	public function setStatus(EntityStatusEnum|string|null $status): FileAbstract
	{
		if (is_string($status)){
			$status = EntityStatusEnum::tryFrom($status) ?? throw new InvalidValueException("Invalid value suppied for file status");
		}

		$this->status = $status;
		return $this;
	}
	
	/**
	 * @return void
	 * @throws DatabaseException
	 */
	public function updateFileStatus(): void{
		FileRepository::updateFileStatus($this);
	}
	
	/**
	 * @return null|string
	 */
	public function getUserFriendlyName(): ?string
	{
		return $this->userFriendlyName;
	}
	
	/**
	 * @param null|string $userFriendlyName
	 *
	 * @return FileAbstract
	 */
	public function setUserFriendlyName(?string $userFriendlyName): FileAbstract
	{
		$this->userFriendlyName = $userFriendlyName;
		return $this;
	}
	
	/**
	 * @return null|int
	 */
	public function getFileId(): ?int
	{
		return $this->fileId;
	}
	
	/**
	 * @param null|int $fileId
	 *
	 * @return FileAbstract
	 */
	public function setFileId(?int $fileId): FileAbstract
	{
		$this->fileId = $fileId;
		return $this;
	}
	
	/**
	 * @return null|int
	 */
	public function getCreatedBy(): ?int
	{
		return $this->createdBy;
	}
	
	/**
	 * @param int $createdBy
	 *
	 * @return FileAbstract
	 */
	public function setCreatedBy(int $createdBy): FileAbstract
	{
		$this->createdBy = $createdBy;
		return $this;
	}
	
	/**
	 * @return null|string
	 */
	public function getType(): ?string
	{
		return $this->type;
	}
	
	/**
	 * @param null|string $type
	 *
	 * @return FileAbstract
	 */
	public function setType(?string $type): FileAbstract
	{
		$this->type = $type;
		return $this;
	}
	
	/**
	 * @return null|string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}
	
	/**
	 * @param null|string $name
	 *
	 * @return FileAbstract
	 */
	public function setName(?string $name): FileAbstract
	{
		$this->name = $name;
		return $this;
	}
	
	/**
	 * @return null|UploadedFileInterface|string
	 */
	public function getFile(): string|UploadedFileInterface|null
	{
		global $ENV;

		if (is_null($this->file)){
			$this->file = file_get_contents($ENV['FILE_STORAGE']['base_path']. substr($this->name, 0, 2) . DIRECTORY_SEPARATOR . $this->name);
		}

		return $this->file;
	}
	
	/**
	 * @param string|UploadedFileInterface $file
	 *
	 * @return FileAbstract
	 */
	public function setFile(string|UploadedFileInterface $file): FileAbstract
	{
		if (is_a($file, UploadedFileInterface::class)) {
			$fileStream = $file->getStream();
			$this->file = $fileStream->read($fileStream->getSize());
		}else{
			$this->file = $file;
		}

		return $this;
	}
}