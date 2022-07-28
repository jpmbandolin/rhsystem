<?php

namespace Modules\Candidate\Application\CreateTest;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Attributes\ArrayTypeAttribute;

class CreateTestDTO extends DTOAbstract
{
	public int $candidateId;
	
	public string $result;
	
	/**
	 * @var CommentDTO[]
	 */
	#[ArrayTypeAttribute(CommentDTO::class)]
	public array $comments = [];
}