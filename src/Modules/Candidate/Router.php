<?php


namespace Modules\Candidate;

use ApplicationBase\Infra\DtoBuilder;
use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\Slim\Authenticator;
use Modules\Candidate\Application\Get\{Get, GetDTO};
use Modules\Candidate\Application\Create\{Create, CreateDTO};

class Router
{
	public function __invoke(RouteCollectorProxy $group): void
	{
		$group->group('/{candidateId}/photo',   [\Modules\Photo\Router::class,  'loadCandidateRoutes'])->add(new Authenticator);
		$group->group("/{candidateId}/resume",  [\Modules\Resume\Router::class, 'loadCandidateRoutes'])->add(new Authenticator);
		$group->group("/{candidateId}/test",    [\Modules\Test\Router::class,   'loadCandidateRoutes']);

		$group->post('', [Create::class, 'run'])
		      ->add(new DtoBuilder(CreateDTO::class))
		      ->add(new Authenticator);

		$group->get('[/{id}]', [Get::class, 'run'])
		      ->add(new DtoBuilder(GetDTO::class))
		      ->add(new Authenticator);
	}
}