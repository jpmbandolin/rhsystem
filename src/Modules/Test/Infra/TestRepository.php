<?php

namespace Modules\Test\Infra;

use Throwable;
use Modules\Test\Domain\Test;
use ApplicationBase\Infra\Database;
use Modules\Comment\Domain\Comment;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class TestRepository
{
	/**
	 * @param Test $test
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function save(Test $test): void
	{
		$sql = "INSERT INTO candidate_test (candidate_id, file_id, result) VALUES (?, ?, ?)";
		
		try {
			Database::getInstance()->prepareAndExecute(
				$sql, [
				    $test->getCandidateId(),
				    $test->getFileId(),
				    $test->getResult(),
			    ]
			);
		} catch (Throwable $t) {
			throw new DatabaseException("Error saving candidate test", previous: $t);
		}
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
		
		try {
			Database::getInstance()->prepareAndExecute($sql, [$test->getFileId(), $comment->getId()]);
		} catch (Throwable $t) {
			throw new DatabaseException("Error saving new comment", previous: $t);
		}
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
	
		try{
			Database::getInstance()->prepareAndExecute($sql, [
				$test->getFileId(),
				$comment->getId()
			]);
		}catch (Throwable $t){
			throw new DatabaseException("Error removing comment", previous: $t);
		}
	}
	
	/**
	 * @param Test $test
	 *
	 * @return array
	 * @throws DatabaseException
	 */
	public static function getComments(Test $test): array
	{
		$sql = "SELECT c.id, c.comment, c.author_id
				FROM comment c
				INNER JOIN candidate_test_comment tc ON tc.comment_id = c.id
				WHERE tc.file_id = ?";
		
		try {
			return Database::getInstance()->fetchMultiObject(
				$sql, [
					$test->getFileId(),
				],  Comment::class
			);
		} catch (Throwable $t) {
			throw new DatabaseException("Error getting test comments", previous: $t);
		}
	}
}