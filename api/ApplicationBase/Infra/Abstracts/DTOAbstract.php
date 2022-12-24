<?php

namespace ApplicationBase\Infra\Abstracts;

use ApplicationBase\Infra\Attributes\{ArrayTypeAttribute, OptionalAttribute};
use ApplicationBase\Infra\Exceptions\InvalidValueException;
use ReflectionClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;

abstract class DTOAbstract
{
	final public function __set($name, $value){
		return false;
	}

    /**
     * @return void
     * @throws InvalidValueException
     */
    final public function checkSymfonyAttributes(): void
    {
        $errors = [];
        $reflector = new ReflectionClass($this);
        $validator = Validation::createValidator();

        foreach ($reflector->getProperties() as $reflectionProperty){
            $attributes = [];

            foreach (
                array_filter(
                    $reflectionProperty->getAttributes(),
                    static fn($attribute): bool => is_subclass_of($attribute->getName(), Constraint::class)
                ) as $attribute
            ) {
                $attributes[] = $attribute->newInstance();
            }

            array_push($errors, ...$validator->validate($reflectionProperty->getValue($this), $attributes));
        }

        if (count($errors) > 0) {
            $message = "The following errors where identified: \n";

            foreach ($errors as $error) {
                $message .= $error->getMessage() . "\n";
            }

            throw new InvalidValueException($message);
        }
    }

	/**
	 * @return void
	 * @throws InvalidValueException
	 */
	final public function validateDTO():void{
        $this->checkSymfonyAttributes();
		$properties = (new ReflectionClass(static::class))->getProperties();

		foreach ($properties as $property){
			$propertyName       = $property->getName();
			$propertyAttributes = $property->getAttributes();
			$propertyAttributeNames = array_map(static function ($attribute){
				return $attribute->getName();
			}, $propertyAttributes);

			if (is_object($this->{$propertyName})){
				$this->{$propertyName}->validateDTO();
			}else if (is_array($this->{$propertyName})){
				if (in_array(ArrayTypeAttribute::class, $propertyAttributeNames, true)){
					$attributeInstance = $property->getAttributes(ArrayTypeAttribute::class)[0]->newInstance();
					if (!$attributeInstance->getIsPrimitive()){
						foreach ($this->{$propertyName} as $dtoInstance){
							$dtoInstance->validateDTO();
						}
					}
				}
			}else if (is_null($this->{$propertyName}) && !in_array(OptionalAttribute::class, $propertyAttributeNames, true)) {
				throw new InvalidValueException('Missing parameter');
			}
		}
	}
}