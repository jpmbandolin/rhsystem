<?php

namespace Modules\Test;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\DtoBuilder;
use ApplicationBase\Infra\Slim\Authenticator;
use Modules\Candidate\Application\CreateTest\{CreateTest, CreateTestDTO};

class Router
{
	public function __invoke(RouteCollectorProxy $group): void{}
	
	/**
	 * @param RouteCollectorProxy $group
	 * Rotas desse método possuem a variável candidateId que deve estar presente nos DTO's
	 *
	 * @return void
	 */
	public function loadCandidateRoutes(RouteCollectorProxy $group): void{
		$group->post("", [CreateTest::class, 'run'])
			->add(new DtoBuilder(CreateTestDTO::class))
			->add(new Authenticator);
		
		$group->get("[/{testId}]", [])
		      ->add(new Authenticator); //get Tests
	}
}