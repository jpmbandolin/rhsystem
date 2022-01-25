<?php

namespace ApplicationBase\Infra\Attributes;

use ApplicationBase\Infra\DTOAbstract;
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
		if (!$this->isPrimitive && (!class_exists($this->propertyType) || !is_subclass_of(DTOAbstract::class, $this->propertyType))){
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