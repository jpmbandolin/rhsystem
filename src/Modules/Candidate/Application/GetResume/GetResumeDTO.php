<?php

namespace Modules\Candidate\Application\GetResume;

class GetResumeDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
	public int $candidateId;
	public int $resumeId;
}