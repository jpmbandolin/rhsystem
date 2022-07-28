<?php

namespace Modules\File\Domain;

use Psr\Http\Message\UploadedFileInterface;

abstract class FileAbstract
{
	use Actions;
	
	protected ?string $name;
	
	public function __construct(
		protected ?int                              $fileId = null,
		protected UploadedFileInterface|string|null $file = null,
		protected ?string                           $userFriendlyName = null,
		protected ?string                           $type = null,
		protected ?int                              $createdBy = null,
		?string                                     $name = null
	) {
		if (is_a($this->file, UploadedFileInterface::class)) {
			$fileStream = $this->file->getStream();
			$this->file = $fileStream->read($fileStream->getSize());
		}

		$this->name = $name;
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
			$this->file = file_get_contents($ENV['FILE_STORAGE']['base_path']. substr($this->name, 2) . DIRECTORY_SEPARATOR . $this->name);
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