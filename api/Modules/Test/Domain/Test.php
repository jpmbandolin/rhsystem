<?php

namespace Modules\Test\Domain;

use Modules\Comment\Domain\Comment;
use Modules\File\Domain\FileAbstract;
use Modules\Test\Infra\TestRepository;
use ApplicationBase\Infra\Exceptions\{DatabaseException};
use Modules\Candidate\Domain\Candidate;
use Psr\Http\Message\UploadedFileInterface;
use ApplicationBase\Infra\Enums\EntityStatusEnum;

class Test extends FileAbstract
{
	/**
	 * @var Comment[]
	 */
	private array $comments = [];

	public function __construct(
		?int                              $fileId = null,
		string|UploadedFileInterface|null $file = null,
		?string                           $userFriendlyName = null,
		?string                           $type = null,
		?int                              $createdBy = null,
		?string                           $name = null,
		private ?string                   $result = null,
		string|EntityStatusEnum|null      $status = null
	) {
		parent::__construct(file: $file, name: $name, status: $status);
		$this->fileId = $fileId;
		$this->userFriendlyName = $userFriendlyName;
		$this->type = $type;
		$this->createdBy = $createdBy;
	}
	
	/**
	 * @param int $fileId
	 *
	 * @return null|FileAbstract
	 * @throws DatabaseException
	 */
	public static function getByFileId(int $fileId): ?Test
	{
		return TestRepository::getByFileId($fileId);
	}

	/**
	 * @param Candidate $candidate
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public function patch(Candidate $candidate): void
	{
		TestRepository::save($this, $candidate);
	}
	
	/**
	 * @return Comment[]
	 * @throws DatabaseException
	 */
	public function getComments(bool $forceReloadFromDb = false): array
	{
		if ($forceReloadFromDb || !count($this->comments)) {
			$this->comments = TestRepository::getComments($this);
		}
		
		return $this->comments;
	}
	
	/**
	 * @param Comment $comment
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public function addComment(Comment $comment): void
	{
		TestRepository::saveComment($this, $comment);
	}
	
	/**
	 * @param Comment $comment
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public function removeComment(Comment $comment): void
	{
		TestRepository::removeComment($this, $comment);
	}
	
	/**
	 * @return string
	 */
	public function getResult(): string
	{
		return $this->result;
	}
	
	/**
	 * @param string $result
	 *
	 * @return Test
	 */
	public function setResult(string $result): Test
	{
		$this->result = $result;
		return $this;
	}
}