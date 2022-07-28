<?php

namespace Modules\Candidate\Infra;

use Throwable;
use Modules\Test\Domain\Test;
use Modules\Photo\Domain\Photo;
use ApplicationBase\Infra\Database;
use Modules\Candidate\Domain\Candidate;
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
			Database::getInstance()->prepareAndExecute(
				$sql, [
					$candidate->getId(),
					$candidate->getCreatedBy(),
					$candidate->getName(),
					$candidate->getEmail(),
					$candidate->getPhotoId()
				]
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
			return Database::getInstance()->fetchObject($sql, [$id], Candidate::class) ?: null;
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
			return Database::getInstance()->fetchMultiObject($sql, class_name: Candidate::class) ?: [];
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
			return Database::getInstance()->fetchMultiObject($sql, ["%".$name."%"], Candidate::class) ?: [];
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
		$sql = "SELECT f.id as fileId, f.created_by as createdBy, f.user_friendly_name as userFriendlyName, f.type, f.name
				FROM file f
				INNER JOIN candidate c ON c.photo_id = f.id
				WHERE c.id = ?
				LIMIT 1";
		
		try {
			return Database::getInstance()->fetchObject($sql, [$candidate->getId()], Photo::class) ?: null;
		}catch (Throwable $t){
			throw new DatabaseException("Error getting candidate photo", previous: $t);
		}
	}
	
	/**
	 * @param Test $test
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function addTest(Test $test): void{
		$sql = "INSERT INTO candidate_test (file_id, candidate_id, result) VALUES (?, ?, ?)";
		
		try {
			Database::getInstance()->prepareAndExecute($sql, [
				$test->getFileId(),
				$test->getCandidateId(),
				$test->getResult()
			]);
		}catch (Throwable $t){
			throw new DatabaseException("Error saving candidate test", previous: $t);
		}
	}
}