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
	/**
	 * @var Test[]
	 */
	private array $test = [];
	
	/**
	 * @var Resume[]
	 */
	private array $resume = [];
	
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
	 * @return null|Photo
	 * @throws DatabaseException
	 */
	public function getPhoto(): ?Photo{
		return $this->photo = CandidateRepository::getPhoto($this);
	}
	
	/**
	 * @param int $id
	 *
	 * @return null|Candidate
	 * @throws DatabaseException
	 */
	public static function getById(int $id): ?Candidate
	{
		return CandidateRepository::getById($id);
	}
	
	/**
	 * @return  Candidate[]
	 *
	 * @throws DatabaseException
	 */
	public static function getAll(): array{
		return CandidateRepository::getAll();
	}
	
	/**
	 * @param Test $test
	 *
	 * @return Candidate
	 * @throws DatabaseException
	 */
	public function addTest(Test $test): Candidate{
		CandidateRepository::addTest($test, $this);

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return Candidate[]
	 * @throws DatabaseException
	 */
	public static function searchByName(string $name): array
	{
		return CandidateRepository::searchByName($name);
	}

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
	 */
	public function getResume(): array
	{
		return $this->resume;
	}
	
	/**
	 * @param Resume ...$resume
	 *
	 * @return Candidate
	 */
	public function setResume(Resume ...$resume): Candidate
	{
		$this->resume = $resume;
		return $this;
	}
	
	/**
	 * @param null|Photo $photo
	 *
	 * @return Candidate
	 */
	public function setPhoto(?Photo $photo): Candidate
	{
		$this->photo = $photo;
		return $this;
	}
	
	/**
	 * @return Test[]
	 */
	public function getTest(): array
	{
		return $this->test;
	}
	
	/**
	 * @param Test ...$test
	 *
	 * @return Candidate
	 */
	public function setTest(Test ...$test): Candidate
	{
		$this->test = $test;
		return $this;
	}
	
	/**
	 * @return null|int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}
	
	/**
	 * @param int $id
	 *
	 * @return Candidate
	 */
	public function setId(int $id): Candidate
	{
		$this->id = $id;
		return $this;
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