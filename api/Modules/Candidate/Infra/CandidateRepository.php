<?php

namespace Modules\Candidate\Infra;

use Throwable;
use Modules\Test\Domain\Test;
use Modules\Photo\Domain\Photo;
use Modules\Resume\Domain\Resume;
use ApplicationBase\Infra\Database;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\QueryBuilder;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class CandidateRepository
{
	/**
	 * @param Candidate $candidate
	 *
	 * @return int
	 * @throws DatabaseException
	 */
	public static function save(Candidate $candidate): int
	{
		$sql = "INSERT INTO candidate (id, created_by, name, email, photo_id)
				VALUES (?,?, ?, ?, ?)
				ON DUPLICATE KEY UPDATE
				id = LAST_INSERT_ID(id),
				name = VALUES(name),
				email = VALUES(email),
				photo_id = VALUES(photo_id)";
		
		try {
			Database::getInstance()->prepareAndExecute(QueryBuilder::create(
				$sql, [
					    $candidate->getId(),
					    $candidate->getCreatedBy(),
					    $candidate->getName(),
					    $candidate->getEmail(),
					    $candidate->getPhotoId()
				    ]
			)
			);
			
			return Database::getInstance()->lastInsertId();
		} catch (Throwable $t) {
			throw new DatabaseException("Error saving new candidate", previous: $t);
		}
	}
	
	/**
	 * @param int $id
	 *
	 * @return null|Candidate
	 * @throws DatabaseException
	 */
	public static function getById(int $id): ?Candidate{
		$sql = "SELECT name, email, created_by AS createdBy, id, photo_id as photoId
				FROM candidate
				WHERE id = ?";
		
		try{
			return Database::getInstance()->fetchObject(QueryBuilder::create($sql, [$id]), Candidate::class) ?: null;
		}catch (Throwable $t){
			throw new DatabaseException("Error getting candidate by id", previous: $t);
		}
	}

	/**
	 * @return Candidate[]
	 * @throws DatabaseException
	 */
	public static function getAll(): array{
		$sql = "SELECT name, email, created_by AS createdBy, id, photo_id as photoId
				FROM candidate";

		try {
			return Database::getInstance()->fetchMultiObject(QueryBuilder::create($sql), className: Candidate::class) ?: [];
		}catch (Throwable $t){
			throw new DatabaseException("", previous: $t);
		}
	}

	/**
	 * @param string $name
	 *
	 * @return Candidate[]
	 * @throws DatabaseException
	 */
	public static function searchByName(string $name): array{
		$sql = "SELECT name, email, created_by AS createdBy, id, photo_id as photoId
				FROM candidate
				WHERE name LIKE ?";
		
		try{
			return Database::getInstance()->fetchMultiObject(QueryBuilder::create($sql, ["%".$name."%"]), Candidate::class) ?: [];
		}catch (Throwable $t){
			throw new DatabaseException("Error getting candidates by name", previous: $t);
		}
	}
	
	/**
	 * @param Candidate $candidate
	 *
	 * @return null|Photo
	 * @throws DatabaseException
	 */
	public static function getPhoto(Candidate $candidate): ?Photo{
		$sql = "SELECT f.id as fileId, f.created_by as createdBy, f.user_friendly_name as userFriendlyName, f.type, f.name, f.status
				FROM file f
				INNER JOIN candidate c ON c.photo_id = f.id
				WHERE c.id = ? AND f.status != ?
				LIMIT 1";
		
		try {
			return Database::getInstance()->fetchObject(QueryBuilder::create($sql, [$candidate->getId(), EntityStatusEnum::Deleted->value]), Photo::class) ?: null;
		}catch (Throwable $t){
			throw new DatabaseException("Error getting candidate photo", previous: $t);
		}
	}
	
	/**
	 * @param Test      $test
	 * @param Candidate $candidate
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function addTest(Test $test, Candidate $candidate): void{
		$sql = "INSERT INTO candidate_test (file_id, candidate_id, result) VALUES (?, ?, ?)";
		
		try {
			Database::getInstance()->prepareAndExecute(QueryBuilder::create($sql, [
				$test->getFileId(),
				$candidate->getId(),
				$test->getResult()
			]));
		}catch (Throwable $t){
			throw new DatabaseException("Error saving candidate test", previous: $t);
		}
	}
	
	/**
	 * @param Resume    $resume
	 * @param Candidate $candidate
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function addResume(Resume $resume, Candidate $candidate): void{
		$sql = "INSERT INTO candidate_resume (file_id, candidate_id) VALUES (?, ?)";

		try {
			Database::getInstance()->prepareAndExecute(QueryBuilder::create($sql, [
				$resume->getFileId(),
				$candidate->getId()
			]));
		}catch (Throwable $t){
			throw new DatabaseException("Error saving candidate resume", previous: $t);
		}
	}
	
	/**
	 * @param Candidate $candidate
	 *
	 * @return Test[]
	 * @throws DatabaseException
	 */
	public static function getTests(Candidate $candidate): array{
		$sql = "SELECT f.id as fileId, f.created_by as createdBy, f.user_friendly_name as userFriendlyName, f.type, f.name, ct.result, f.status
				FROM file f
				INNER JOIN candidate_test ct ON ct.file_id = f.id
				WHERE ct.candidate_id = ? AND f.status != ?";

		try {
			return Database::getInstance()->fetchMultiObject(QueryBuilder::create($sql, [$candidate->getId(), EntityStatusEnum::Deleted->value]), Test::class) ?: [];
		}catch (Throwable $t){
			throw new DatabaseException("Error getting candidate tests", previous: $t);
		}
	}
	
	/**
	 * @param Candidate $candidate
	 *
	 * @return Resume[]
	 * @throws DatabaseException
	 */
	public static function getResumes(Candidate $candidate): array{
		$sql = "SELECT f.id as fileId, f.created_by as createdBy, f.user_friendly_name as userFriendlyName, f.type, f.name, f.status
				FROM file f
				INNER JOIN candidate_resume cr ON cr.file_id = f.id
				WHERE cr.candidate_id = ? AND f.status != ?";
		
		try {
			return Database::getInstance()->fetchMultiObject(QueryBuilder::create($sql, [$candidate->getId(), EntityStatusEnum::Deleted->value]), Resume::class) ?: [];
		}catch (Throwable $t){
			throw new DatabaseException("Error getting candidate resumes", previous: $t);
		}
	}
}