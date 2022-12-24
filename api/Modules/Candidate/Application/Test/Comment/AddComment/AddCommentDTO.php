<?php

namespace Modules\Candidate\Application\Test\Comment\AddComment;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class AddCommentDTO extends DTOAbstract
{
    #[Type("integer", message: "The candidateId parameter should be an integer")]
	public int $candidateId;
    #[Type("integer", message: "The testId parameter should be an integer")]
	public int $testId;
    #[Type("string", message: "The comment parameter should be a string")]
    #[Length(
        min: 3,
        max: 200,
        minMessage: "The comment parameter should have at least 3 characters",
        maxMessage: "The comment parameter should have at most 200 characters"
    )]
	public string $comment;
}