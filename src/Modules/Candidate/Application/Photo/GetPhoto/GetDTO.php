<?php

namespace Modules\Candidate\Application\Photo\GetPhoto;

use ApplicationBase\Infra\Abstracts\DTOAbstract;

class GetDTO extends DTOAbstract
{
	public int $candidateId;
	public int $photoId;
}