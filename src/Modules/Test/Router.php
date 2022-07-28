<?php

namespace Modules\Test;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\DtoBuilder;
use Modules\Test\Application\Create\Create;
use ApplicationBase\Infra\Slim\Authenticator;
use Modules\Candidate\Application\Test\AddTest\{AddTest};
use Modules\Test\Application\AddComment\AddComment;
use Modules\Test\Application\AddComment\AddCommentDTO;
use Modules\Candidate\Application\Test\GetTest\GetTest;
use Modules\Candidate\Application\Test\GetTest\GetTestDTO;
use Modules\Candidate\Application\Test\AddTest\AddTestDTO;
use Modules\Candidate\Application\Test\GetAllTests\GetAllTests;
use Modules\Candidate\Application\Test\GetAllTests\GetAllTestsDTO;

class Router
{
	public function __invoke(RouteCollectorProxy $group): void
	{
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
	public function loadCandidateRoutes(RouteCollectorProxy $group): void
	{
		$group->group("/{testId}/comment", [\Modules\Comment\Router::class, 'loadCandidateTestRoutes']);

		$group->post("/{testId}", [AddTest::class, 'run'])
		      ->add(new DtoBuilder(AddTestDTO::class));
		
		$group->get("/{testId}", [GetTest::class, 'run'])
		      ->add(new DtoBuilder(GetTestDTO::class));
		
		$group->get("", [GetAllTests::class, 'run'])
		      ->add(new DtoBuilder(GetAllTestsDTO::class));
	}
}