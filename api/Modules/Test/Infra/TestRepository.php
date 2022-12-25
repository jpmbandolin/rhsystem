<?php

namespace Modules\Test\Infra;

use ApplicationBase\Infra\Abstracts\RepositoryAbstract;
use Modules\Test\Domain\Test;
use Modules\Comment\Domain\Comment;
use Modules\Candidate\Domain\Candidate;
use ApplicationBase\Infra\QueryBuilder;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class TestRepository extends RepositoryAbstract
{
	/**
	 * @param Test      $test
	 * @param Candidate $candidate
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function save(Test $test, Candidate $candidate): void
	{
		$sql = "INSERT INTO candidate_test (candidate_id, file_id, result)
				VALUES (?, ?, ?)
				ON DUPLICATE KEY UPDATE
				result = VALUES(result)";

        self::prepareAndExecute(QueryBuilder::create($sql, [
            $candidate->getId(),
            $test->getFileId(),
            $test->getResult(),
		]), "Error saving candidate test");
	}
	
	/**
	 * @param int $fileId
	 *
	 * @return null|Test
	 * @throws DatabaseException
	 */
	public static function getByFileId(int $fileId): ?Test{
		$sql = "SELECT f.id as fileId, f.created_by as createdBy, f.user_friendly_name as userFriendlyName,
       				f.type, f.name, ct.result, f.status
				FROM file f
				LEFT JOIN candidate_test ct ON ct.file_id = f.id
				WHERE f.id = ? AND f.status != ?";

        return self::fetchObject(
            QueryBuilder::create($sql, [$fileId, EntityStatusEnum::Deleted->value]),
            Test::class,
            "Error getting Test by file ID"
        ) ?: null;
	}
	
	/**
	 * @param Test    $test
	 * @param Comment $comment
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function saveComment(Test $test, Comment $comment): void
	{
		$sql = "INSERT INTO candidate_test_comment (file_id, comment_id) VALUES (?, ?)";

        self::prepareAndExecute(
            QueryBuilder::create($sql, [$test->getFileId(), $comment->getId()]),
            "Error saving new comment"
        );
	}
	
	/**
	 * @param Test    $test
	 * @param Comment $comment
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function removeComment(Test $test, Comment $comment): void
	{
		$sql = "DELETE FROM candidate_test_comment WHERE file_id = ? AND comment_id = ?";

			self::prepareAndExecute(
                QueryBuilder::create($sql, [
                    $test->getFileId(),
                    $comment->getId()
                ]),
            "Error removing comment"
            );
	}
	
	/**
	 * @param Test $test
	 *
	 * @return array
	 * @throws DatabaseException
	 */
	public static function getComments(Test $test): array
	{
		$sql = "SELECT c.id, c.comment, c.author_id AS authorId, c.status
				FROM comment c
				INNER JOIN candidate_test_comment tc ON tc.comment_id = c.id
				WHERE tc.file_id = ? AND c.status != ?";

        return self::fetchMultiObject(QueryBuilder::create($sql, [
            $test->getFileId(),
            EntityStatusEnum::Deleted->value
        ]),  Comment::class, "Error getting test comments");
    }
}