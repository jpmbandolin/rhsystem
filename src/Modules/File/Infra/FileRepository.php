<?php

namespace Modules\File\Infra;

use Throwable;
use ApplicationBase\Infra\Database;
use Modules\File\Domain\FileAbstract;
use ApplicationBase\Infra\Exceptions\AppException;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class FileRepository
{
	/**
	 * @param FileAbstract $file
	 *
	 * @return int
	 * @throws AppException
	 */
	public static function saveFile(FileAbstract $file): int
	{
		$sql = "INSERT INTO file (name, user_friendly_name, type, created_by) VALUES (?, ?, ?, ?)";
		
		try {
			Database::getInstance()->prepareAndExecute(
				$sql, [
					$file->getName()                ?? throw new RuntimeException("Missing 'name' parameter to create file"),
					$file->getUserFriendlyName()    ?? throw new RuntimeException("Missing 'user_friendly_name' parameter to create file"),
					$file->getType()                ?? throw new RuntimeException("Missing 'type' parameter to create file"),
					$file->getCreatedBy()           ?? throw new RuntimeException("Missing 'created_by' parameter to create file"),
				]
			);

			return Database::getInstance()->lastInsertId();
		} catch (AppException $e) {
			throw $e;
		}catch (Throwable $t) {
			throw new DatabaseException("Error saving file into database", previous: $t);
		}
	}
	
	/**
	 * @param FileAbstract $file
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function updateFileStatus(FileAbstract $file): void{
		$sql = "UPDATE file SET status = ? WHERE id = ?";
		
		try {
			Database::getInstance()->prepareAndExecute($sql, [$file->getStatus(), $file->getFileId()]);
		}catch (Throwable $t){
			throw new DatabaseException("Error updating file status");
		}
	}
}