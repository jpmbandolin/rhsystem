<?php

namespace Modules\Comment\Domain;

use Modules\Comment\Infra\CommentRepository;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class Comment
{
	public function __construct(
		private readonly string $comment,
		private readonly int    $authorId,
		private ?int            $id = null,
	) {}
	
	/**
	 * @return int
	 * @throws DatabaseException
	 */
	public function save(): int
	{
		return $this->id = CommentRepository::save($this);
	}
	
	/**
	 * @return string
	 */
	public function getComment(): string
	{
		return $this->comment;
	}
	
	/**
	 * @return int
	 */
	public function getAuthorId(): int
	{
		return $this->authorId;
	}
	
	/**
	 * @return null|int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}
}