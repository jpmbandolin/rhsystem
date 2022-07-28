<?php

namespace Modules\Candidate\Application\Resume\AddResume;

class AddResumeDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
	public int $candidateId;
	public int $resumeId;
}