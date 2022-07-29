<?php

namespace Modules\Comment\Infra;

use Throwable;
use ApplicationBase\Infra\Database;
use Modules\Comment\Domain\Comment;
use ApplicationBase\Infra\QueryBuilder;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class CommentRepository
{
	/**
	 * @param Comment $comment
	 *
	 * @return int
	 * @throws DatabaseException
	 */
	public static function save(Comment $comment): int
	{
		$sql = "INSERT INTO comment (comment, author_id) VALUES (?, ?)";
		
		try {
			Database::getInstance()->prepareAndExecute(QueryBuilder::create($sql, [
				$comment->getComment(),
				$comment->getAuthorId(),
			]));
			
			return Database::getInstance()->lastInsertId();
		} catch (Throwable $t) {
			throw new DatabaseException("Error saving new comment", previous: $t);
		}
	}
	
	/**
	 * @param Comment $comment
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function updateStatus(Comment $comment): void{
		$sql = "UPDATE comment SET status = ? WHERE id = ?";
		
		try {
			Database::getInstance()->prepareAndExecute(QueryBuilder::create($sql, [$comment->getStatus()->value, $comment->getId()]));
		}catch (Throwable $t){
			throw new DatabaseException("Error updating comment status", previous: $t);
		}
	}
}