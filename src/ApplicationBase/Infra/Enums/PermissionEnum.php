<?php

namespace ApplicationBase\Infra\Enums;

enum PermissionEnum: string implements EnumInterface
{
	case UserRead   = 'user:read';
	case UserEdit   = 'user:edit';
	case UserCreate = 'user:create';

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
			self::UserRead   => "user:read",
			self::UserEdit   => "user:edit",
			self::UserCreate => "user:create"
		};
	}
}