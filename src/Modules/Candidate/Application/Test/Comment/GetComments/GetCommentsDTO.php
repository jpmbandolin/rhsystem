<?php

namespace Modules\Candidate\Application\Test\Comment\GetComments;

class GetCommentsDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
	public int $candidateId;
	public int $testId;
}