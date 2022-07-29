<?php

namespace ApplicationBase\Infra;

use Throwable;
use ApplicationBase\Infra\Enums\SqlClauseEnum;
use ApplicationBase\Infra\Exceptions\DatabaseException;
use ApplicationBase\Infra\Abstracts\ControllerAbstract;

class QueryBuilder
{
	private static string $requestId;
	private static int $executionOrder = 1;
	private int $executionPosition;

	/**
	 * @param string $sql
	 * @param array  $args
	 * @param bool   $log
	 *
	 */
	public function __construct(
		private readonly string $sql,
		private readonly array  $args = [],
		private readonly bool   $log = true
	) {
		$this->executionPosition = self::$executionOrder;
		self::$executionOrder++;
	}
	
	/**
	 * @throws DatabaseException
	 */
	public function __destruct(){
		if ($this->log) {
			$this->log();
		}
	}
	
	/**
	 * @return void
	 * @throws DatabaseException
	 */
	private function log(): void
	{
		$sql = "INSERT INTO sql_logs (request_id, first_clause, target_table, query, json_encoded_args, executed_by, execution_order)
				VALUES (?, ?, ?, ?, ?, ?, ?)";
		
		try {
			Database::getInstance()->prepareAndExecute(
				self::create(sql: $sql, args: [
					self::getRequestId(),
					$this->getFirstClause()?->value,
					$this->getTargetTable(),
					$this->sql,
					json_encode($this->args, JSON_THROW_ON_ERROR),
					self::getCurrentUserId(),
					$this->executionPosition
				],  log: false)
			);
		} catch (Throwable $t) {
			throw new DatabaseException("Error saving query logs", previous: $t);
		}
	}
	
	/**
	 * @param string $sql
	 * @param array  $args
	 * @param bool   $log
	 *
	 * @return QueryBuilder
	 */
	public static function create(string $sql, array $args = [], bool $log = true): QueryBuilder
	{
		return new QueryBuilder(sql: $sql, args: $args, log: $log);
	}
	
	/**
	 * @return string
	 */
	private static function getRequestId(): string
	{
		if (!isset(self::$requestId)) {
			self::$requestId = uniqid("request_", true);
		}
		
		return self::$requestId;
	}
	
	/**
	 * @return null|SqlClauseEnum
	 */
	private function getFirstClause(): ?SqlClauseEnum
	{
		$sql = strtoupper($this->sql);
		
		if (str_starts_with(strtoupper($sql), "INSERT")) {
			$clause = SqlClauseEnum::Insert;
		} else if (str_starts_with($sql, 'UPDATE')) {
			$clause = SqlClauseEnum::Update;
		} else if (str_starts_with($sql, "DELETE")) {
			$clause = SqlClauseEnum::Delete;
		} else if (str_starts_with($sql, "SELECT")) {
			$clause = SqlClauseEnum::Select;
		} else if (str_starts_with($sql, "TRUNCATE")) {
			$clause = SqlClauseEnum::Truncate;
		}
		
		return $clause ?? null;
	}
	
	/**
	 * @return string
	 */
	private function getTargetTable(): string
	{
		preg_match(pattern: "/from(?:\W|)([A-z]+)(?:$|\W)/i", subject: $this->sql, matches: $matches);
		return $matches[1];
	}
	
	/**
	 * @return null|int
	 */
	private static function getCurrentUserId(): ?int
	{
		try {
			return ControllerAbstract::getJwtData()?->id;
		} catch (Throwable) {
			return null;
		}
	}
	
	/**
	 * @return string
	 */
	public function getSql(): string
	{
		return $this->sql;
	}
	
	/**
	 * @return array
	 */
	public function getArgs(): array
	{
		return $this->args;
	}
}