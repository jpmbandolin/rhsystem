<?php

namespace Modules\Candidate\Domain;

use Modules\Test\Domain\Test;
use Modules\Photo\Domain\Photo;
use Modules\Resume\Domain\Resume;
use Modules\Candidate\Infra\CandidateRepository;
use ApplicationBase\Infra\Exceptions\DatabaseException;

trait Actions
{
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
	public static function getAll(): array
	{
		return CandidateRepository::getAll();
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
	 * @return null|Photo
	 * @throws DatabaseException
	 */
	public function getPhoto(): ?Photo
	{
		return $this->photo = CandidateRepository::getPhoto($this);
	}
	
	/**
	 * @param Test $test
	 *
	 * @return Candidate
	 * @throws DatabaseException
	 */
	public function addTest(Test $test): Candidate
	{
		CandidateRepository::addTest($test, $this);
		
		return $this;
	}
	
	/**
	 * @param Resume $resume
	 *
	 * @return Candidate
	 * @throws DatabaseException
	 */
	public function addResume(Resume $resume): Candidate
	{
		CandidateRepository::addResume($resume, $this);
		
		return $this;
	}
}