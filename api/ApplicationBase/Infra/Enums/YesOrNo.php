<?php

namespace ApplicationBase\Infra\Enums;

enum YesOrNo: string implements EnumInterface
{
	case Yes    = 'Y';
	case No     = 'N';

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
			self::Yes => 'Y',
			self::No => 'N'
		};
	}
}