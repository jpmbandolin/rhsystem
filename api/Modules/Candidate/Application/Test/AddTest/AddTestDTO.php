<?php

namespace Modules\Candidate\Application\Test\AddTest;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class AddTestDTO extends DTOAbstract
{
	public int $candidateId;
	public int $testId;
	public string $result;
}