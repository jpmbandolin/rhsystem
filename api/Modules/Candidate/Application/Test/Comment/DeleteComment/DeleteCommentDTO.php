<?php

namespace Modules\Candidate\Application\Test\Comment\DeleteComment;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Type;

class DeleteCommentDTO extends DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
	public int $candidateId;
    #[Type("integer", message: "The testId parameter should be an integer")]
	public int $testId;
    #[Type("integer", message: "The commentId parameter should be an integer")]
	public int $commentId;
}