<?php

namespace Modules\Candidate\Application\Test\DeleteTest;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class DeleteTestDTO extends DTOAbstract
{
	public int $candidateId;
	public int $testId;
}