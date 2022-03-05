<?php

namespace Modules\User\Infra;

use ApplicationBase\Infra\Database;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use Modules\User\Domain\User;

class UserRepository
{
	/**
	 * @param int $id
	 * @return User|null
	 * @throws DatabaseException
	 */
	public static function getById(int $id):?User{
		$sql = "SELECT id, name FROM user WHERE id = ?";

		try {
			return Database::getInstance()->fetchObject($sql, [$id], User::class) ?: null;
		}catch (\Throwable $t){
			throw new DatabaseException(message: 'Error getting user by Id', previous: $t);
		}
	}

	/**
	 * @param string $login
	 * @return User|null
	 * @throws DatabaseException
	 */
	public static function getByLogin(string $login): ?User{
		$sql = "SELECT id, name, password FROM user WHERE name = ?";

		try {
			return Database::getInstance()->fetchObject($sql, [$login], User::class) ?: null;
		}catch (\Throwable $t){
			throw new DatabaseException(message: 'Error getting user by Login', previous: $t);
		}
	}

	/**
	 * @param User $user
	 * @return int
	 * @throws DatabaseException
	 */
	public static function save(User $user):int{
		$sql = "INSERT INTO user (id, name, password) 
				VALUES (?,?,?)
				ON DUPLICATE KEY UPDATE 
				id = LAST_INSERT_ID(id),
				name = VALUES(name),
				password = VALUES(password)";

		try {
			Database::getInstance()->prepareAndExecute($sql, [
				$user->getId(),
				$user->getName(),
				$user->getPassword()
			]);

			return Database::getInstance()->lastInsertId();
		}catch (\Throwable $t){
			throw new DatabaseException(message: "Error saving new user", previous: $t);
		}
	}
}