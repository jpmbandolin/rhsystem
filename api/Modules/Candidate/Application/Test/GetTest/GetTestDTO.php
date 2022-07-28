<?php

namespace Modules\Candidate\Application\Test\GetTest;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class GetTestDTO extends DTOAbstract
{
	public int $candidateId;
	public int $testId;
}