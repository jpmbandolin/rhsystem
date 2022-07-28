<?php

namespace Modules\User\Domain;

use ApplicationBase\Infra\JWT;
use Modules\User\Infra\UserRepository;
use ApplicationBase\Infra\Exceptions\DatabaseException;

trait Actions
{
	/**
	 * @param int $id
	 *
	 * @return static|null
	 * @throws DatabaseException
	 */
	public static function getById(int $id): ?User
	{
		return UserRepository::getById($id);
	}
	
	/**
	 * @param string $login
	 *
	 * @return static|null
	 * @throws DatabaseException
	 */
	public static function getByLogin(string $login): ?User
	{
		return UserRepository::getByLogin($login);
	}
	
	/**
	 * @return User[]
	 * @throws DatabaseException
	 */
	public static function getAll(): array
	{
		return UserRepository::getAll();
	}
	
	/**
	 * @return string
	 */
	public function getJWT(): string
	{
		return JWT::generateJWT(
			[
				"id"     => $this->getId(),
				"name"   => $this->getName(),
				"email"  => $this->getEmail(),
				"status" => $this->getStatus()->value,
			]
		);
	}
	
	/**
	 * @return User
	 * @throws DatabaseException
	 */
	public function save(): User
	{
		return $this->setId(UserRepository::save($this));
	}
}