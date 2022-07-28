<?php

namespace Modules\Candidate\Domain;

use Modules\Test\Domain\Test;
use Modules\Photo\Domain\Photo;
use Modules\Resume\Domain\Resume;
use Modules\Comment\Domain\Comment;
use Modules\Candidate\Infra\CandidateRepository;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class Candidate
{
	use Actions;

	/**
	 * @var Test[]
	 */
	private array $tests = [];
	
	/**
	 * @var Resume[]
	 */
	private array $resumes = [];
	
	/**
	 * @var Comment[]
	 */
	private array $comments = [];
	/**
	 * @var null|Photo
	 */
	private ?Photo $photo = null;
	
	public function __construct(
		private string  $name,
		private ?string $email,
		private int     $createdBy,
		private ?int    $photoId = null,
		private ?int    $id = null,
	) {}
	
	

	/**
	 * @return null|int
	 */
	public function getPhotoId(): ?int
	{
		return $this->photoId;
	}

	/**
	 * @param null|int $photoId
	 *
	 * @return Candidate
	 */
	public function setPhotoId(?int $photoId): Candidate
	{
		$this->photoId = $photoId;
		return $this;
	}
	
	/**
	 * @return int
	 * @throws DatabaseException
	 */
	public function save(): int
	{
		return $this->id = CandidateRepository::save($this);
	}
	
	/**
	 * @return int
	 */
	public function getCreatedBy(): int
	{
		return $this->createdBy;
	}
	
	/**
	 * @param int $createdBy
	 *
	 * @return Candidate
	 */
	public function setCreatedBy(int $createdBy): Candidate
	{
		$this->createdBy = $createdBy;
		return $this;
	}
	
	/**
	 * @return Comment[]
	 */
	public function getComments(): array
	{
		return $this->comments;
	}
	
	/**
	 * @param Comment[] $comments
	 *
	 * @return Candidate
	 */
	public function setComments(array $comments): Candidate
	{
		$this->comments = $comments;
		return $this;
	}
	
	/**
	 * @return Resume[]
	 * @throws DatabaseException
	 */
	public function getResumes(): array
	{
		if (!count($this->resumes)){
			$this->resumes = CandidateRepository::getResumes($this);
		}

		return $this->resumes;
	}
	
	/**
	 * @return Test[]
	 * @throws DatabaseException
	 */
	public function getTests(): array
	{
		if(!count($this->tests)){
			$this->tests = CandidateRepository::getTests($this);
		}

		return $this->tests;
	}
	
	/**
	 * @return null|int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	/**
	 * @param string $name
	 *
	 * @return Candidate
	 */
	public function setName(string $name): Candidate
	{
		$this->name = $name;
		return $this;
	}
	
	/**
	 * @return null|string
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}
	
	/**
	 * @param null|string $email
	 *
	 * @return Candidate
	 */
	public function setEmail(?string $email): Candidate
	{
		$this->email = $email;
		return $this;
	}
}