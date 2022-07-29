<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Enums\PermissionEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use PDOStatement;
use Throwable;

class Database extends \PDO
{
	protected static Database $instance;

	/**
	 * @throws DatabaseException
	 */
	public function __construct()
	{
		global $ENV;

		$host = $ENV['DATABASE']['host'];
		$user = $ENV['DATABASE']['user'];
		$pass = $ENV['DATABASE']['password'];
		$database = $ENV['DATABASE']['database'];

		$dsn = 'mysql:dbname=' . $database .';host=' . $host;

		try{
			parent::__construct($dsn, $user, $pass, ["charset"=>"utf8"]);
			$this->exec("set names utf8");
		}catch(Throwable $t){
			throw new DatabaseException(message: "Error starting database connection", previous: $t);
		}

		if (!isset(self::$instance)){
			self::$instance = $this;
		}
	}

	/**
	 * @return Database
	 */
	public static function getInstance(): Database
	{
		if (!isset(self::$instance) || is_null(self::$instance)){
			self::$instance = new Database;
		}

		return self::$instance;
	}
	
	/**
	 * @param QueryBuilder $queryBuilder
	 *
	 * @return bool|PDOStatement
	 */
	public function prepareAndExecute(QueryBuilder $queryBuilder):bool|PDOStatement{
		$sql = static::$instance->prepare($queryBuilder->getSql());
		if(empty($queryBuilder->getArgs())){
			$sql->execute();
		} else{
			$sql->execute($queryBuilder->getArgs());
		}

		return $sql;
	}
	
	/**
	 * @param QueryBuilder $queryBuilder
	 * @param string       $className
	 *
	 * @return array|bool
	 */
	public function fetchMultiObject(QueryBuilder $queryBuilder, string $className = \stdClass::class): array|bool
	{
		$sql    = static::$instance->prepareAndExecute($queryBuilder);
		if($className === \stdClass::class){
			$array = $sql->fetchAll(self::FETCH_CLASS, $className);
		}else{
			$array = $sql->fetchAll(self::FETCH_ASSOC);
			$array = array_map(static function($row) use ($className){
				if ($className === PermissionEnum::class){
					return PermissionEnum::tryFrom($row['name']);
				}

				return new $className(...$row);
			},$array);
		}
		$sql->closeCursor();
		return $array;
	}
	
	/**
	 * @param QueryBuilder $queryBuilder
	 * @param string       $className
	 *
	 * @return mixed
	 */
	public function fetchObject(QueryBuilder $queryBuilder, string $className = \stdClass::class): mixed
	{
		$sql    = static::$instance->prepareAndExecute($queryBuilder);
		if($className === \stdClass::class){
			$object = $sql->fetch($className);
		}else{

			$object = $sql->fetch(self::FETCH_ASSOC);
			if($object){
				$object = new $className(...$object);
			}

		}
		$sql->closeCursor();
		return $object;
	}
}