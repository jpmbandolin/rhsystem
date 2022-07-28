<?php

namespace Modules\Comment;

use Slim\Routing\RouteCollectorProxy;
use ApplicationBase\Infra\DtoBuilder;
use Modules\Candidate\Application\Test\Comment\AddComment\AddComment;
use Modules\Candidate\Application\Test\Comment\GetComments\GetComments;
use Modules\Candidate\Application\Test\Comment\AddComment\AddCommentDTO;
use Modules\Candidate\Application\Test\Comment\GetComments\GetCommentsDTO;
use Modules\Candidate\Application\Test\Comment\DeleteComment\DeleteComment;
use Modules\Candidate\Application\Test\Comment\DeleteComment\DeleteCommentDTO;

class Router
{
	/**
	 * @param RouteCollectorProxy $group
	 * Rotas desse método possuem as variáveis $candidateId e $testId que deve estar presente nos (DTO)'s
	 *
	 * @return void
	 */
	public function loadCandidateTestRoutes(RouteCollectorProxy $group): void
	{
		$group->post("", [AddComment::class, 'run'])
		      ->add(new DtoBuilder(AddCommentDTO::class));

		$group->delete("/{commentId}", [DeleteComment::class, 'run'])
		      ->add(new DtoBuilder(DeleteCommentDTO::class));

		$group->get("", [GetComments::class, 'run'])
			->add(new DtoBuilder(GetCommentsDTO::class));
	}
}