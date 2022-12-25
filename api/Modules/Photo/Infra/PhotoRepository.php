<?php

namespace Modules\Photo\Infra;

use ApplicationBase\Infra\Abstracts\RepositoryAbstract;
use Modules\Photo\Domain\Photo;
use ApplicationBase\Infra\QueryBuilder;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class PhotoRepository extends  RepositoryAbstract
{
	/**
	 * @param int $fileId
	 *
	 * @return null|Photo
	 * @throws DatabaseException
	 */
	public static function getByFileId(int $fileId): ?Photo{
		$sql = "SELECT id as fileId, created_by as createdBy, user_friendly_name as userFriendlyName, type, name, status
				FROM file
				WHERE id = ? AND status != ?";

        return self::fetchObject(
            QueryBuilder::create($sql, [$fileId, EntityStatusEnum::Deleted->value]),
            Photo::class,
            "Error getting file by id"
        );
    }
}