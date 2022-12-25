<?php

namespace Modules\User\Infra;

use ApplicationBase\Infra\Abstracts\RepositoryAbstract;
use ApplicationBase\Infra\Database;
use ApplicationBase\Infra\QueryBuilder;
use ApplicationBase\Infra\Enums\EntityStatusEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use Modules\User\Domain\User;

class UserRepository extends RepositoryAbstract
{
	/**
	 * @param int $id
	 * @return User|null
	 * @throws DatabaseException
	 */
	public static function getById(int $id):?User
    {
		$sql = "SELECT id, name, email, password, status FROM user WHERE id = ?";

        return self::fetchObject(
            QueryBuilder::create($sql, [$id]),
            User::class,
            "Error getting user by Id"
        ) ?: null;
	}

	/**
	 * @param string $login
	 * @return User|null
	 * @throws DatabaseException
	 */
	public static function getByLogin(string $login): ?User
    {
		$sql = "SELECT id, name, email, password, status FROM user WHERE (name = ? OR email = ?)";

        return self::fetchObject(
            QueryBuilder::create($sql, [$login, $login]),
            User::class,
            "Error getting user by Login"
        ) ?: null;
	}

	/**
	 * @return User[]
	 * @throws DatabaseException
	 */
	public static function getAll(): array
    {
		$sql = "SELECT id, name, email, password, status FROM user";

        return self::fetchMultiObject(
            QueryBuilder::create(sql: $sql),
            className: User::class,
            errorMessage: "Error getting users"
        ) ?: [];
    }

	/**
	 * @param User $user
	 * @return int
	 * @throws DatabaseException
	 */
	public static function save(User $user): int
    {
		$sql = "INSERT INTO user (id, name, password, email, status)
				VALUES (?,?,?,?,?)
				ON DUPLICATE KEY UPDATE 
				id			= LAST_INSERT_ID(id),
				name		= VALUES(name),
				email		= VALUES(email),
				password	= VALUES(password),
				status		= VALUES(status)";

        self::prepareAndExecute(QueryBuilder::create($sql, [
            $user->getId(),
            $user->getName(),
            $user->getPassword(),
            $user->getEmail(),
            $user->getStatus()?->value ?: EntityStatusEnum::Active->value
        ]), "Error saving new user");

        return Database::getInstance()->lastInsertId();
	}
}