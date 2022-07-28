<?php

namespace ApplicationBase\Infra\Enums;

enum EntityStatusEnum: string implements EnumInterface
{
	case Active     = 'ACTIVE';
	case Inactive   = 'INACTIVE';
	case Deleted    = 'DELETED';
	
	/**
	 * @return string
	 */
	public function label(): string
	{
		return self::getLabel($this);
	}
	
	/**
	 * @param $value
	 * @return string
	 */
	public static function getLabel($value): string
	{
		return match ($value){
			self::Active    => "ACTIVE",
			self::Inactive  => "INACTIVE",
			self::Deleted   => "DELETED"
		};
	}
}