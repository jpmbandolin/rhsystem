<?php

namespace Modules\Comment\Infra;

use ApplicationBase\Infra\Abstracts\RepositoryAbstract;
use ApplicationBase\Infra\Database;
use Modules\Comment\Domain\Comment;
use ApplicationBase\Infra\QueryBuilder;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class CommentRepository extends RepositoryAbstract
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

        self::prepareAndExecute(QueryBuilder::create($sql, [
            $comment->getComment(),
            $comment->getAuthorId(),
        ]), "Error saving new comment");

        return Database::getInstance()->lastInsertId();
	}
	
	/**
	 * @param Comment $comment
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function updateStatus(Comment $comment): void
    {
		$sql = "UPDATE comment SET status = ? WHERE id = ?";

        self::prepareAndExecute(
            QueryBuilder::create($sql, [$comment->getStatus()->value, $comment->getId()]),
            "Error updating comment status"
        );
	}
}