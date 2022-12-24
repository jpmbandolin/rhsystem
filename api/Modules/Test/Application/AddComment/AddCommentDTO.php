<?php

namespace Modules\Test\Application\AddComment;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class AddCommentDTO extends DTOAbstract
{
    #[Type("string", message: "The comment parameter should be a string")]
    #[Length(
        min: 3,
        max: 200,
        minMessage: "The comment should have at least 3 characters",
        maxMessage: "The comment should have 200 characters at most"
    )]
	public string $comment;
    #[Type("Integer", message: "The ID should be an integer")]
	public int $id;
}