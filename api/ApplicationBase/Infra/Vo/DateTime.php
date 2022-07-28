<?php

namespace ApplicationBase\Infra\Vo;

use ApplicationBase\Infra\Abstracts\ViewObjectAbstract;
use ApplicationBase\Infra\Exceptions\InvalidValueException;
use DateTime as DefaultDateTime;
use Exception;

class DateTime extends ViewObjectAbstract
{
	private const INTERNATIONAL_DATETIME_FORMAT = 'Y-m-d H:i:s';
	private const BRAZILIAN_DATETIME_FORMAT = 'd/m/Y H:i:s';
	private const DATETIME_REGEX = '/^(\d{4}-(0\d|1[0-2])-([0-2]\d|3[0-1])\s([0-1]\d|2[0-3]):[0-5]\d:[0-5]\d)$/';
	
	private DefaultDateTime $dateTime;
	
	/**
	 * @param string $dateTime The datetime in Y-m-d H:i:s format
	 * @throws InvalidValueException
	 * @throws Exception
	 */
	public function __construct(string $dateTime)
	{
		if (!preg_match(self::DATETIME_REGEX, $dateTime)) {
			throw new InvalidValueException('Invalid datetime format');
		}
		
		$this->dateTime = new DefaultDateTime($dateTime);
	}
	
	/**
	 * @return DefaultDateTime
	 * @throws Exception
	 */
	public function toDateTime(): DefaultDateTime
	{
		return $this->dateTime;
	}
	
	public function toBrazilianFormat(): string
	{
		return $this->dateTime->format(self::BRAZILIAN_DATETIME_FORMAT);
	}
	
	public function __toString(): string
	{
		return $this->dateTime->format(self::INTERNATIONAL_DATETIME_FORMAT);
	}
}
