<?php

namespace Modules\Resume\Infra;

use Modules\Resume\Domain\Resume;
use ApplicationBase\Infra\Database;
use ApplicationBase\Infra\Exceptions\DatabaseException;

class ResumeRepository
{
	/**
	 * @param Resume $resume
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public static function save(Resume $resume):void{
		$sql = "INSERT INTO candidate_resume (candidate_id, file_id) VALUES (?, ?)";
		
		try{
			Database::getInstance()->prepareAndExecute($sql, [
				$resume->getCandidateId(),
				$resume->getFileId()
			]);
		}catch (\Throwable $t){
			throw new DatabaseException("Error saving candidate resume", previous: $t);
		}
	}
}