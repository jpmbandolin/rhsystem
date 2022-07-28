<?php

namespace ApplicationBase\Infra\Vo;

use ApplicationBase\Infra\Abstracts\ViewObjectAbstract;
use ApplicationBase\Infra\Exceptions\InvalidValueException;
use DateTime;
use Exception;

class Date extends ViewObjectAbstract
{
	private const INTERNATIONAL_DATE_FORMAT = 'Y-m-d';
	private const BRAZILIAN_DATE_FORMAT = 'd/m/Y';
	private const DATE_REGEX = '/^\d{4}-(0\d|1[0-2])-([0-2]\d|3[0-1])$/';
	
	private DateTime $dateTime;
	
	/**
	 * @param string $date The desired date in Y-m-d format
	 * @throws InvalidValueException
	 * @throws Exception
	 */
	public function __construct(string $date)
	{
		if (!preg_match(self::DATE_REGEX, $date)) {
			throw new InvalidValueException('Invalid date format');
		}
		
		$this->dateTime = new DateTime($date);
	}
	
	/**
	 * @return DateTime
	 * @throws Exception
	 */
	public function toDateTime(): DateTime
	{
		return $this->dateTime;
	}
	
	public function toBrazilianFormat(): string
	{
		return $this->dateTime->format(self::BRAZILIAN_DATE_FORMAT);
	}
	
	public function __toString(): string
	{
		return $this->dateTime->format(self::INTERNATIONAL_DATE_FORMAT);
	}
}
