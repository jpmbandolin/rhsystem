<?php

namespace Modules\Resume\Infra;

use ApplicationBase\Infra\Abstracts\RepositoryAbstract;
use Modules\Resume\Domain\Resume;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\QueryBuilder;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class ResumeRepository extends RepositoryAbstract
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

        self::prepareAndExecute(QueryBuilder::create($sql, [
            $candidate->getId(),
            $resume->getFileId(),
		]), "Error saving candidate resume");
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

        return self::fetchObject(
            QueryBuilder::create($sql, [$fileId, EntityStatusEnum::Deleted->value]),
            Resume::class,
            "Error getting Test by file ID"
        ) ?: null;
	}
}