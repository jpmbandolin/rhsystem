<?php

namespace Modules\Photo;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\DtoBuilder;
use ApplicationBase\Infra\Slim\Authenticator;
use Modules\Candidate\Application\Photo\GetPhoto\{Get};
use Modules\Candidate\Application\Photo\GetPhoto\GetDTO;
use Modules\Candidate\Application\Photo\CreatePhoto\CreatePhoto;
use Modules\Candidate\Application\Photo\CreatePhoto\CreatePhotoDTO;

class Router
{
	public function __invoke(RouteCollectorProxy $group): void {}
	
	/**
	 * @param RouteCollectorProxy $group
	 * Rotas desse método possuem a variável candidateId que deve estar presente nos DTO's
	 *
	 * @return void
	 */
	public function loadCandidateRoutes(RouteCollectorProxy $group): void
	{
		$group->post('', [CreatePhoto::class, 'run'])
		      ->add(new DtoBuilder(CreatePhotoDTO::class))
		      ->add(new Authenticator);
		
		$group->get('/{photoId}', [Get::class, 'run'])
		      ->add(new DtoBuilder(GetDTO::class))
		      ->add(new Authenticator);
	}
}