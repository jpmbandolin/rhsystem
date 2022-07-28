<?php

namespace Modules\Candidate\Application\Test\Comment\AddComment;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class AddCommentDTO extends DTOAbstract
{
	public int $candidateId;
	public int $testId;
	public string $comment;
}