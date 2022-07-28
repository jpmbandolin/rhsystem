<?php

namespace Modules\Resume;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\DtoBuilder;
use ApplicationBase\Infra\Slim\Authenticator;
use Modules\Resume\Application\Create\Create;
use Modules\Candidate\Application\AddResume\AddResume;
use Modules\Candidate\Application\AddResume\AddResumeDTO;

class Router
{
	public function __invoke(RouteCollectorProxy $group): void
	{
		$group->post("", [Create::class, 'run'])
		      ->add(new Authenticator);
	}
	
	/**
	 * @param RouteCollectorProxy $group
	 * Rotas desse método possuem a variável candidateId que deve estar presente nos DTO's
	 *
	 * @return void
	 */
	public function loadCandidateRoutes(RouteCollectorProxy $group): void
	{
		$group->post("", [Create::class, 'run'])
		      ->add(new Authenticator);
		
		$group->get("[/{resumeId}]", [AddResume::class, 'run'])
		      ->add(new DtoBuilder(AddResumeDTO::class))
		      ->add(new Authenticator); //getNewResume
	}
}