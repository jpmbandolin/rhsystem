<?php

namespace Modules\Test\Application\AddComment;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class AddCommentDTO extends DTOAbstract
{
	public string $comment;
	public int $id;
}