<?php

namespace Modules\Resume\Infra;

use Throwable;
use Modules\Resume\Domain\Resume;
use ApplicationBase\Infra\Database;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\QueryBuilder;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class ResumeRepository
{
	/**
	 * @param Resume    $resume
	 * @param Candidate $candidate
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function save(Resume $resume, Candidate $candidate): void
	{
		$sql = "INSERT INTO candidate_resume (candidate_id, file_id) VALUES (?, ?)";
		
		try {
			Database::getInstance()->prepareAndExecute(QueryBuilder::create($sql, [
				$candidate->getId(),
				$resume->getFileId(),
			]));
		} catch (Throwable $t) {
			throw new DatabaseException("Error saving candidate resume", previous: $t);
		}
	}
	
	/**
	 * @param int $fileId
	 *
	 * @return null|Resume
	 * @throws DatabaseException
	 */
	public static function getByResumeId(int $fileId): ?Resume
	{
		$sql = "SELECT f.id as fileId, f.created_by as createdBy, f.user_friendly_name as userFriendlyName,
       				f.type, f.name, f.status
				FROM file f
				LEFT JOIN candidate_resume cr ON cr.file_id = f.id
				WHERE f.id = ? AND f.status != ?";
		
		try {
			return Database::getInstance()->fetchObject(QueryBuilder::create($sql, [$fileId, EntityStatusEnum::Deleted->value]), Resume::class) ?: null;
		} catch (Throwable $t) {
			throw new DatabaseException("Error getting Test by file ID", previous: $t);
		}
	}
}