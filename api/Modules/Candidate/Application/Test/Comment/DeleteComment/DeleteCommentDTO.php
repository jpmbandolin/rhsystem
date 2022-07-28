<?php

namespace Modules\Candidate\Application\Test\Comment\DeleteComment;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class DeleteCommentDTO extends DTOAbstract
{
	public int $candidateId;
	public int $testId;
	public int $commentId;
}