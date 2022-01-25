<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Attributes\{ArrayTypeAttribute,OptionalAttribute};
use ApplicationBase\Infra\Exceptions\{InvalidValueException, RuntimeException};
use ReflectionClass;
use ReflectionException;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use UnitEnum;

class DTOConstructor
{
	public function __construct(private string $className){}

	/**
	 * @param ServerRequestInterface  $request
	 * @param RequestHandlerInterface $handler
	 * @return ResponseInterface
	 * @throws InvalidValueException
	 */
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface{
		global $container;
		$body = $request->getParsedBody();
		$query = array_merge($request->getQueryParams(), RouteContext::fromRequest($request)->getRoute()?->getArguments());
		$dto = new $this->className;

		try {
			$this->fillDto($dto, $query);
			$this->fillDto($dto, $body);
			$this->validateDTOObject($dto);
		}catch (\Throwable $t){
			throw new InvalidValueException(message: 'Invalid parameters where sent to the server', previous: $t);
		}

		$container->set($this->className, $dto);

		return $handler->handle($request);
	}

	/**
	 * @param DTOAbstract  $dto
	 * @param object|array $parameters
	 * @return DTOAbstract
	 * @throws InvalidValueException
	 * @throws ReflectionException
	 * @throws RuntimeException
	 */
	private function fillDto(DTOAbstract $dto, object|array $parameters):DTOAbstract{
		$reflector = new ReflectionClass($dto::class);
		$properties = $reflector->getProperties();

		foreach ($properties as $property){
			$propertyName = $property->getName();
			$propertyType = $property->getType()?->getName();

			if (is_array($parameters)){
				if (!isset($parameters[$propertyName])){
					continue;
				}
			}else if (!isset($parameters->{$propertyName})){
				continue;
			}

			if (class_exists($propertyType)){
				if (is_subclass_of($propertyType, DTOAbstract::class)){
					$dto->{$propertyName} = $this->fillDto(new $propertyType, is_array($parameters) ? $parameters[$propertyName] : $parameters->{$propertyName});
				}else if (is_subclass_of($propertyType, UnitEnum::class)){
					$dto->{$propertyName} =
						$propertyType::tryFrom(is_array($parameters) ? $parameters[$propertyName] : $parameters->propertyName) ??
						throw new InvalidValueException('Invalid Value supplied to Enum');
				}else{
					throw new InvalidValueException('Invalid object type');
				}
			}else if ($propertyType === "array"){
				if (is_array($parameters)){
					if (!is_array($parameters[$propertyName])){
						throw new InvalidValueException("The {$propertyName} parameter must be an array");
					}
				}else if (!is_array($parameters->{$propertyName})){
					throw new InvalidValueException("The {$propertyName} parameter must be an array");
				}

				$propertyAttributes = $property->getAttributes(ArrayTypeAttribute::class);
				if (count($propertyAttributes)){
					$atributeInstance = $propertyAttributes[0]->newInstance();
					if ($atributeInstance->getIsPrimitive()){
						$dto->{$propertyName} = is_array($parameters) ? $parameters[$propertyName] : $parameters->{$propertyName};
					}else{
						$dto->{$propertyName} = array_map(function ($object) use ($atributeInstance){
							return $this->fillDto(new $atributeInstance->getPropertyType, $object);
						}, is_array($parameters) ? $parameters[$propertyName] : $parameters->{$propertyName});
					}
				}else{
					throw new RuntimeException('All arrays in DTO\'s must have an ArrayTypeAttribute');
				}

			}else {
				$dto->{$propertyName} = $parameters->{$propertyName};
			}
		}

		return $dto;
	}

	/**
	 * @param DTOAbstract $dto
	 * @return void
	 * @throws InvalidValueException
	 * @throws ReflectionException
	 */
	private function validateDTOObject(DTOAbstract $dto): void
	{
		$reflector = new ReflectionClass($dto::class);
		$properties = $reflector->getProperties();

		foreach ($properties as $property){
			$propertyName = $property->getName();
			$propertyType = $property->getType()?->getName();
			$propertyAttributes = array_map(function ($attribute){
				return $attribute->getName();
			}, $property->getAttributes());

			if (!isset($dto->{$propertyName})){
				if (in_array(OptionalAttribute::class, $propertyAttributes)){
					continue;
				}

				throw new InvalidValueException("The {$propertyName} parameter is required");
			}

			if (is_subclass_of(DTOAbstract::class, $propertyType)){
				$this->validateDTOObject($this->{$propertyName});
			}

			if ($propertyType === 'array'){
				//@todo validar dto's dentro do array
			}

		}
	}
}