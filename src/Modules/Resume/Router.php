<?php

namespace Modules\Resume;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\DtoBuilder;
use ApplicationBase\Infra\Slim\Authenticator;
use Modules\Resume\Application\Create\Create;
use Modules\Candidate\Application\Resume\AddResume\AddResume;
use Modules\Candidate\Application\Resume\GetResume\GetResume;
use Modules\Candidate\Application\Resume\AddResume\AddResumeDTO;
use Modules\Candidate\Application\Resume\GetResume\GetResumeDTO;
use Modules\Candidate\Application\Resume\DeleteResume\DeleteResume;
use Modules\Candidate\Application\Resume\GetAllResumes\GetAllResumes;
use Modules\Candidate\Application\Resume\DeleteResume\DeleteResumeDTO;
use Modules\Candidate\Application\Resume\GetAllResumes\GetAllResumesDTO;

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
		$group->post("/{resumeId}", [AddResume::class, 'run'])
		      ->add(new DtoBuilder(AddResumeDTO::class));
		
		$group->delete("/{resumeId}", [DeleteResume::class, ''])
		      ->add(new DtoBuilder(DeleteResumeDTO::class));

		$group->get("/{resumeId}", [GetResume::class, 'run'])
		      ->add(new DtoBuilder(GetResumeDTO::class));

		$group->get("", [GetAllResumes::class, 'run'])
		      ->add(new DtoBuilder(GetAllResumesDTO::class));
	}
}