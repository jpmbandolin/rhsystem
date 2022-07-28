<?php

namespace ApplicationBase\Infra\Attributes;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Exceptions\RuntimeException;
use Attribute;

#[Attribute]
class ArrayTypeAttribute
{
	/**
	 * @param string $propertyType
	 * @param bool   $isPrimitive
	 * @throws RuntimeException
	 */
	public function __construct(private string $propertyType, private bool $isPrimitive = false){
		if (!$this->isPrimitive && (!class_exists($this->propertyType) || !is_a($this->propertyType,DTOAbstract::class, true))){
			throw new RuntimeException('All propertyTypes must be an existing class and an instance of ' . DTOAbstract::class);
		}
	}

	public function getPropertyType():string{
		return $this->propertyType;
	}

	public function getIsPrimitive():bool{
		return $this->isPrimitive;
	}
}