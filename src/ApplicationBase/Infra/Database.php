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
	 * @param string $sql
	 * @param array $args
	 *
	 * @return bool|PDOStatement
	 */
	public function prepareAndExecute(string $sql, array $args = array()):bool|PDOStatement{
		$sql = static::$instance->prepare($sql);
		if(empty($args)){
			$sql->execute();
		} else{
			$sql->execute($args);
		}

		return $sql;
	}

	/**
	 * @param        $sql
	 * @param array $args
	 * @param string $class_name
	 *
	 * @return array|bool
	 */
	public function fetchMultiObject($sql, array $args = array(), string $class_name = "stdClass"): array|bool
	{
		$sql    = static::$instance->prepareAndExecute($sql, $args);
		if($class_name === "stdClass"){
			$array = $sql->fetchAll(self::FETCH_CLASS, $class_name);
		}else{
			$array = $sql->fetchAll(self::FETCH_ASSOC);
			$array = array_map(static function($row) use ($class_name){
				if ($class_name === PermissionEnum::class){
					return PermissionEnum::tryFrom($row['name']);
				}

				return new $class_name(...$row);
			},$array);
		}
		$sql->closeCursor();
		return $array;
	}

	/**
	 * @param        $sql
	 * @param array  $args
	 * @param string $class_name
	 *
	 * @return mixed
	 */
	public function fetchObject($sql, array $args = array(), string $class_name = "stdClass"): mixed
	{
		$sql    = static::$instance->prepareAndExecute($sql, $args);
		if($class_name === "stdClass"){
			$object = $sql->fetch($class_name);
		}else{

			$object = $sql->fetch(self::FETCH_ASSOC);
			if($object){
				$object = new $class_name(...$object);
			}

		}
		$sql->closeCursor();
		return $object;
	}
}