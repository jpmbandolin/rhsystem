<?php

namespace Modules\Test;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\DtoBuilder;
use Modules\Test\Application\Create\Create;
use ApplicationBase\Infra\Slim\Authenticator;
use Modules\Candidate\Application\AddTest\{AddTest, AddTestDTO};
use Modules\Test\Application\AddComment\AddComment;
use Modules\Test\Application\AddComment\AddCommentDTO;

class Router
{
	public function __invoke(RouteCollectorProxy $group): void{
		$group->post("", [Create::class, 'run'])
			->add(new Authenticator());

		$group->post("/{id}/comment", [AddComment::class, 'run'])
			->add(new DtoBuilder(AddCommentDTO::class))
			->add(new Authenticator());
	}

	/**
	 * @param RouteCollectorProxy $group
	 * Rotas desse método possuem a variável candidateId que deve estar presente nos (DTO)'s
	 *
	 * @return void
	 */
	public function loadCandidateRoutes(RouteCollectorProxy $group): void{
		$group->post("/{testId}", [AddTest::class, 'run'])
			->add(new DtoBuilder(AddTestDTO::class))
			->add(new Authenticator);
		
		$group->get("[/{testId}]", [])
		      ->add(new Authenticator); //get Tests
	}
}