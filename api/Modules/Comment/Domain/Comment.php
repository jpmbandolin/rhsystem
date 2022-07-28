<?php

namespace Modules\Comment\Domain;

use Modules\Comment\Infra\CommentRepository;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Exceptions\InvalidValueException;

class Comment
{
	private EntityStatusEnum $status;
	
	/**
	 * @param string                  $comment
	 * @param int                     $authorId
	 * @param string|EntityStatusEnum $status
	 * @param null|int                $id
	 *
	 * @throws InvalidValueException
	 */
	public function __construct(
		private readonly string  $comment,
		private readonly int     $authorId,
		string|EntityStatusEnum  $status,
		private ?int             $id = null,
	) {
		$this->setStatus($status);
	}
	
	/**
	 * @return EntityStatusEnum
	 */
	public function getStatus(): EntityStatusEnum
	{
		return $this->status;
	}
	
	/**
	 * @param string|EntityStatusEnum $status
	 *
	 * @return Comment
	 * @throws InvalidValueException
	 */
	public function setStatus(string|EntityStatusEnum $status): Comment
	{
		if (is_string($status)){
			$status = EntityStatusEnum::tryFrom($status) ?? throw new InvalidValueException('Invalid Value supplied for comment status');
		}

		$this->status = $status;
		return $this;
	}
	
	/**
	 * @return void
	 * @throws DatabaseException
	 */
	public function updateStatus(): void{
		CommentRepository::updateStatus($this);
	}
	
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