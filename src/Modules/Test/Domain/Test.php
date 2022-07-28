<?php

namespace Modules\Test\Domain;

use Modules\Comment\Domain\Comment;
use Modules\File\Domain\FileAbstract;
use Modules\Test\Infra\TestRepository;
use Psr\Http\Message\UploadedFileInterface;
use ApplicationBase\Infra\Exceptions\{AppException, DatabaseException, NotImplementedException};

class Test extends FileAbstract
{
	private int $candidateId;
	/**
	 * @var Comment[]
	 */
	private array $comments;
	private string $result;

	public function __construct(?int $fileId = null, string|UploadedFileInterface|null $file = null, ?string $userFriendlyName = null, ?string $type = null, ?int $createdBy = null, ?string $name = null)
	{
		parent::__construct(file: $file, name: $name);
		$this->fileId = $fileId;
		$this->userFriendlyName = $userFriendlyName;
		$this->type = $type;
		$this->createdBy = $createdBy;
	}

	/**
	 * @return int
	 * @throws AppException
	 */
	public function save(): int
	{
		return parent::save([TestRepository::class, 'save']);
	}
	
	/**
	 * @return int
	 */
	public function getCandidateId(): int
	{
		return $this->candidateId;
	}
	
	/**
	 * @param int $candidateId
	 *
	 * @return Test
	 */
	public function setCandidateId(int $candidateId): Test
	{
		$this->candidateId = $candidateId;
		return $this;
	}
	
	/**
	 * @return Comment[]
	 * @throws DatabaseException
	 */
	public function getComments(bool $forceReloadFromDb = false): array
	{
		if (!isset($this->comments) || $forceReloadFromDb){
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

	/**
	 * @param int $fileId
	 *
	 * @return null|FileAbstract
	 * @throws NotImplementedException
	 */
	public static function getByFileId(int $fileId): ?FileAbstract
	{
		throw new NotImplementedException('Not implemented');
	}
}