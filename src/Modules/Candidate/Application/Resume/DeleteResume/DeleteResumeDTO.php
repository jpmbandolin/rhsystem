<?php

namespace Modules\Candidate\Application\Resume\DeleteResume;

class DeleteResumeDTO extends \ApplicationBase\Infra\Abstracts\DTOAbstract
{
	public int $candidateId;
	public int $resumeId;
}