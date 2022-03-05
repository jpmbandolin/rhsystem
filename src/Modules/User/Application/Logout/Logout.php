<?php

namespace Modules\User\Application\Logout;

use ApplicationBase\Infra\{Abstracts\ControllerAbstract, Redis};
use DI\{DependencyException, NotFoundException};
use Psr\Http\Message\ResponseInterface;

class Logout extends ControllerAbstract
{
	/**
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function run(): ResponseInterface
	{
		global $container;
		$jwt = $container->get('jwt');

		Redis::del($jwt);
		return $this->replyRequest(status: 204);
	}
}