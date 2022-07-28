<?php

namespace Modules\Test;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\DtoBuilder;
use Modules\Test\Application\Create\Create;
use ApplicationBase\Infra\Slim\Authenticator;
use Modules\Candidate\Application\GetTest\GetTest;
use Modules\Test\Application\AddComment\AddComment;
use Modules\Candidate\Application\GetTest\GetTestDTO;
use Modules\Test\Application\AddComment\AddCommentDTO;
use Modules\Candidate\Application\GetAllTests\GetAllTests;
use Modules\Candidate\Application\GetAllTests\GetAllTestsDTO;
use Modules\Candidate\Application\AddTest\{AddTest, AddTestDTO};

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
		$group->post("/{testId}", [AddTest::class, 'run'])
		      ->add(new DtoBuilder(AddTestDTO::class))
		      ->add(new Authenticator);
		
		$group->get("/{testId}", [GetTest::class, 'run'])
		      ->add(new DtoBuilder(GetTestDTO::class))
		      ->add(new Authenticator);
		
		$group->get("", [GetAllTests::class, 'run'])
		      ->add(new DtoBuilder(GetAllTestsDTO::class))
		      ->add(new Authenticator);
	}
}