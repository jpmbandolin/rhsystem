<?php

namespace Modules\File\Domain;

use Modules\File\Infra\FileRepository;
use ApplicationBase\Infra\Exceptions\AppException;
use ApplicationBase\Infra\Exceptions\RuntimeException;

trait Actions
{
	/**
	 * @param null|callable $repositoryMethod
	 * @param mixed         ...$args
	 *
	 * @return int
	 * @throws AppException
	 * @throws RuntimeException
	 */
	public function save(?callable $repositoryMethod = null, ...$args): int
	{
		$this->saveInDisk();

		$this->fileId = FileRepository::saveFile($this);
		if (!is_null($repositoryMethod)){
			$repositoryMethod(...$args);
		}

		return $this->fileId;
	}

	abstract public static function getByFileId(int $fileId): ?FileAbstract;
	
	/**
	 * @return void
	 * @throws RuntimeException
	 */
	private function saveInDisk(): void
	{
		global $ENV;
		$this->name = $this->getHashedName();
		$fileDirectoryPath = $ENV['FILE_STORAGE']['base_path'];

		if (!is_dir($fileDirectoryPath)){
			$this->createDir($fileDirectoryPath);
		}

		$newFolderName = substr($this->name, 2);
		$newFilePath = $fileDirectoryPath . $newFolderName;

		if (!is_dir($newFilePath)){
			$this->createDir($newFilePath);
		}

		$file = fopen($fileDirectoryPath . $newFolderName . DIRECTORY_SEPARATOR . $this->name, 'wb+');
		fwrite($file, $this->file);
		fclose($file);
	}
	
	/**
	 * @param string $dir
	 *
	 * @return void
	 * @throws RuntimeException
	 */
	private function createDir(string $dir): void
	{
		if (!mkdir($concurrentDirectory = $dir) && !is_dir($concurrentDirectory)) {
			throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
		}
	}
	
	private function getHashedName(): string
	{
		return md5($this->file);
	}
}