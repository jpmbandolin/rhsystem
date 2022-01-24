<?php

namespace ApplicationBase\Infra\Attributes;

use ApplicationBase\Infra\Exceptions\PermissionException;
use ApplicationBase\Infra\JWT;
use Attribute;
use DI\{DependencyException, NotFoundException};

#[Attribute]
final class RouteAuthenticator
{
	/**
	 * @param array $permissionRequired
	 * @throws PermissionException
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function __construct(private array $permissionRequired){
		global $container;
		$jwtPayload = $container->get(JWT::class);
		$haveAccess = in_array($this->permissionRequired, $jwtPayload->permissions, true);

		if (!$haveAccess){
			throw new PermissionException('You don\'t have permission to access this route');
		}
	}
}