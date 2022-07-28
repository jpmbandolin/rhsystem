<?php

namespace ApplicationBase\Infra;

use ApplicationBase\Infra\Exceptions\NotFoundException;

final class PaginatedData
{
	private readonly int $totalPages;
	private array $paginatedData;

	/**
	 * @param array $responseData
	 * @param int   $page
	 * @param int   $pageSize
	 * @throws NotFoundException
	 */
	public function __construct(array $responseData, private readonly int $page = 1, int $pageSize = 10){
		$this->totalPages = (int) ceil(count($responseData)/$pageSize);

		if ($this->page > $this->totalPages){
			throw new NotFoundException('The requested page doesn\'t exist');
		}

		$this->paginatedData = array_splice($responseData, (($page-1)  * $pageSize), $pageSize);
	}

	/**
	 * @return int
	 */
	public function getTotalPages():int{
		return $this->totalPages;
	}

	/**
	 * @return int
	 */
	public function getPage(): int
	{
		return $this->page;
	}

	/**
	 * @return array
	 */
	public function getPaginatedData():array{
		return $this->paginatedData;
	}
}