<?php

namespace Modules\Resume;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\Slim\Authenticator;

class Router
{
	public function __invoke(RouteCollectorProxy $group): void {}
	
	/**
	 * @param RouteCollectorProxy $group
	 * Rotas desse método possuem a variável candidateId que deve estar presente nos DTO's
	 *
	 * @return void
	 */
	public function loadCandidateRoutes(RouteCollectorProxy $group): void{
		$group->post("", [])
		      ->add(new Authenticator); //createNewResume
		
		$group->get("[/{resumeId}]", [])
		      ->add(new Authenticator); //getNewResume
	}
}