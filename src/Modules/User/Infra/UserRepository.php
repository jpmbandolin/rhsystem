<?php

namespace Modules\User\Infra;

use ApplicationBase\Infra\Database;
use ApplicationBase\Infra\Enums\PermissionEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use Modules\User\Domain\User;
use Throwable;

class UserRepository
{
	/**
	 * @param int $id
	 * @return User|null
	 * @throws DatabaseException
	 */
	public static function getById(int $id):?User{
		$sql = "SELECT id, name, email, password, status FROM user WHERE id = ?";

		try {
			return Database::getInstance()->fetchObject($sql, [$id], User::class) ?: null;
		}catch (Throwable $t){
			throw new DatabaseException(message: 'Error getting user by Id', previous: $t);
		}
	}

	/**
	 * @param string $login
	 * @return User|null
	 * @throws DatabaseException
	 */
	public static function getByLogin(string $login): ?User{
		$sql = "SELECT id, name, email, password, status FROM user WHERE (name = ? OR email = ?)";

		try {
			return Database::getInstance()->fetchObject($sql, [$login, $login], User::class) ?: null;
		}catch (Throwable $t){
			throw new DatabaseException(message: 'Error getting user by Login', previous: $t);
		}
	}

	/**
	 * @return User[]
	 * @throws DatabaseException
	 */
	public static function getAll(): array{
		$sql = "SELECT id, name, email, password, status FROM user";

		try {
			return Database::getInstance()->fetchMultiObject(sql: $sql, class_name: User::class) ?: [];
		}catch (Throwable $t){
			throw new DatabaseException(message: 'Error getting users', previous: $t);
		}
	}

	/**
	 * @param User $user
	 * @return int
	 * @throws DatabaseException
	 */
	public static function save(User $user):int{
		$sql = "INSERT INTO user (id, name, password, email, status)
				VALUES (?,?,?,?,?)
				ON DUPLICATE KEY UPDATE 
				id			= LAST_INSERT_ID(id),
				name		= VALUES(name),
				email		= VALUES(email),
				password	= VALUES(password),
				status		= VALUES(status)";

		try {
			Database::getInstance()->prepareAndExecute($sql, [
				$user->getId(),
				$user->getName(),
				$user->getPassword(),
				$user->getEmail(),
				$user->getStatus()->value
			]);

			return Database::getInstance()->lastInsertId();
		}catch (Throwable $t){
			throw new DatabaseException(message: "Error saving new user", previous: $t);
		}
	}
}