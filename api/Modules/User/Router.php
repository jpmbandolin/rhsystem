<?php


namespace Modules\User;


use ApplicationBase\Infra\DtoBuilder;
use ApplicationBase\Infra\Slim\Authenticator;
use Modules\User\Application\Login\{Login, LoginDTO};
use Modules\User\Application\Create\{Create, CreateDTO};
use Modules\User\Application\Get\Get;
use Modules\User\Application\Get\GetDTO;
use Modules\User\Application\Logout\Logout;
use Slim\Routing\RouteCollectorProxy;

class Router
{
	public function __invoke(RouteCollectorProxy $group): void
	{
		$group->post('', [Create::class, 'run'])
			->add(new DtoBuilder(CreateDTO::class))
			->add(new Authenticator);

		$group->post('/login', [Login::class, 'run'])
			->add(new DtoBuilder(LoginDTO::class));

		$group->post('/logout', [Logout::class, 'run'])
			->add(new Authenticator);

		$group->get('[/{id}]', [Get::class, 'run'])
			->add(new DtoBuilder(GetDTO::class))
			->add(new Authenticator);
	}
}
