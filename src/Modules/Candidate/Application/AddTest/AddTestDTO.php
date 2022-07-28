<?php

namespace Modules\Candidate\Application\AddTest;

use ApplicationBase\Infra\Abstracts\DTOAbstract;
use ApplicationBase\Infra\Attributes\ArrayTypeAttribute;

class AddTestDTO extends DTOAbstract
{
	public int $candidateId;
	public int $testId;
	public string $result;
}