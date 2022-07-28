<?php

namespace Modules\Photo\Infra;

use Modules\Photo\Domain\Photo;
use ApplicationBase\Infra\Database;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class PhotoRepository
{
	/**
	 * @param int $fileId
	 *
	 * @return null|Photo
	 * @throws DatabaseException
	 */
	public static function getByFileId(int $fileId): ?Photo{
		$sql = "SELECT id as fileId, created_by as createdBy, user_friendly_name as userFriendlyName, type, name
				FROM file
				WHERE id = ?";

		try {
			return Database::getInstance()->fetchObject($sql, [$fileId], Photo::class);
		}catch (\Throwable $t){
			throw new DatabaseException('Error getting file by id', previous: $t);
		}
	}
}