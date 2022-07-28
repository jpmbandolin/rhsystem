<?php

namespace Modules\User\Application\Logout;

use ApplicationBase\Infra\{Abstracts\ControllerAbstract, Slim\Authenticator};
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

		if (!is_null($whiteList = Authenticator::getWhiteList())) {
			$whiteList::removeFromWhiteList($jwt);
		}

		return $this->replyRequest(status: 204);
	}
}