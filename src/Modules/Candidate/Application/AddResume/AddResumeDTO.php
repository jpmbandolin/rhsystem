<?php

namespace Modules\Candidate\Application\AddResume;

class AddResumeDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
	public int $candidateId;
	public int $resumeId;
}