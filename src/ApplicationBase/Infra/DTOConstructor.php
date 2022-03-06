<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Attributes\ArrayTypeAttribute;
use ApplicationBase\Infra\Exceptions\{AppException, InvalidValueException};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Slim\Routing\RouteContext;
use Throwable;
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
		$body       = $request->getParsedBody();
		$query      = $request->getQueryParams();
		$arguments  = RouteContext::fromRequest($request)->getRoute()?->getArguments();

		/**
		 * @var DTOAbstract
		 */
		$dto = new $this->className;

		try {
			if ($body){
				self::fill($dto, $body);
			}

			if (count($query)){
				self::fill($dto, $query);
			}

			if (count($arguments)){
				self::fill($dto, $arguments);
			}

			$dto->validateDTO();
		}catch (AppException $e){
			throw $e;
		}catch (Throwable $t){
			throw new InvalidValueException(message: 'Invalid values supplied in the request', previous: $t);
		}

		global $container;
		$container->set($dto::class, $dto);
		$request = $request->withParsedBody($dto);

		return $handler->handle($request);
	}

	/**
	 * @param DTOAbstract  $dto
	 * @param object|array $parameters
	 * @return DTOAbstract
	 * @throws InvalidValueException
	 * @throws ReflectionException
	 */
	public static function fill(DTOAbstract $dto, object|array $parameters):DTOAbstract{
		$properties = (new ReflectionClass($dto::class))->getProperties();

		foreach ($properties as $property){
			self::fetchFrom($dto, $parameters, $property);
		}

		return $dto;
	}

	/**
	 * @param                    $dto
	 * @param mixed              $data
	 * @param ReflectionProperty $property
	 * @return void
	 * @throws InvalidValueException
	 * @throws ReflectionException
	 */
	private static function fetchFrom(&$dto, mixed $data, ReflectionProperty $property):void{
		$propertyName       = $property->getName();
		$propertyType       = $property->getType()?->getName();

		if (!is_null($data)){
			if (is_array($data)){
				if (isset($data[$propertyName])){
					if (is_array($data[$propertyName])){
						self::fetchFromArray($dto->{$propertyName}, $data[$propertyName], $property);
					}else if (is_object($data[$propertyName])){
						self::fetchFromObject($dto->{$propertyName}, $data[$propertyName], $propertyType);
					}else if (is_string($data[$propertyName]) || is_numeric($data[$propertyName])){
						$dto->{$propertyName} = $data[$propertyName];
					}else{
						$dto->{$propertyName} = $data;
					}
				}
			}else if (is_object($data)){
				if (isset($data->{$propertyName})){
					if (is_array($data->{$propertyName})){
						self::fetchFromArray($dto->{$propertyName}, $data->{$propertyName}, $property);
					}else if (is_object($data->{$propertyName})){
						self::fetchFromObject($dto->{$propertyName}, $data->{$propertyName}, $propertyType);
					}else if (is_subclass_of($propertyType, UnitEnum::class)){
						$dto->{$propertyName} = $propertyType::tryFrom($data) ?? throw new InvalidValueException('Invalid Value supplied to Enum');
					}else{
						$dto->{$propertyName} = $data->{$propertyName};
					}
				}
			}else{
				$dto = $data;
			}
		}
	}

	/**
	 * @param                    $dto
	 * @param array              $array
	 * @param ReflectionProperty $property
	 * @return void
	 * @throws InvalidValueException
	 * @throws ReflectionException
	 */
	private static function fetchFromArray(&$dto, array $array, ReflectionProperty $property):void{
		$propertyAttributes = $property->getAttributes();
		$propertyAttributeNames = array_map(static function ($attribute){
			return $attribute->getName();
		}, $propertyAttributes);

		if (!in_array(ArrayTypeAttribute::class, $propertyAttributeNames, true)){
			throw new InvalidValueException('All array properties must have a ' . ArrayTypeAttribute::class . " class");
		}

		$atributeInstance = $property->getAttributes(ArrayTypeAttribute::class)[0]->newInstance();

		if (!$atributeInstance->getIsPrimitive()) {
			$array = array_map(static function ($item) use ($atributeInstance) {
				return self::fill((new ($atributeInstance->getPropertyType())), $item);
			}, $array);
		}
		$dto = $array;
	}

	/**
	 * @param        $dto
	 * @param object $object
	 * @param string $propertyType
	 * @return void
	 * @throws InvalidValueException
	 * @throws ReflectionException
	 */
	private static function fetchFromObject(&$dto, object $object, string $propertyType):void{
		if (class_exists($propertyType)){
			if (is_subclass_of($propertyType, DTOAbstract::class)){
				$dto = new $propertyType;
				self::fill($dto, $object);
			}else{
				throw new InvalidValueException('Invalid object type');
			}
		}
	}
}