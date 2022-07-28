<?php

namespace Modules\Candidate\Application\GetPhoto;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class GetDTO extends DTOAbstract
{
	public int $candidateId;
	public int $photoId;
}