<?php

namespace Modules\Candidate\Application\Test\Comment\GetComments;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class GetCommentsDTO extends DTOAbstract
{
	public int $candidateId;
	public int $testId;
}