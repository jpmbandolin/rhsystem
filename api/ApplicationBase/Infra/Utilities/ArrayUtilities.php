<?php

namespace ApplicationBase\Infra\Utilities;

trait ArrayUtilities
{
	/**
	 * Retorna true caso algum elemento atenda a condição implementada.
	 * Caso contrario, retorna false
	 *
	 * @param array $arr
	 * @param callable $fn
	 * @return boolean
	 */
	private static function some(array $arr, callable $fn):bool{
		foreach($arr as $index=>$item){
			if($fn($item, $index)){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Retorna true caso todos os elementos atendam a condição implementada.
	 * Caso contrario, retorna False
	 *
	 * @param array $arr
	 * @param callable $fn
	 * @return boolean
	 */
	private static function every(array $arr, callable $fn):bool{
		foreach($arr as $index=>$item){
			if(!$fn($item, $index)){
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Retorna o primeiro elemento que atenda a condição implementada.
	 * Caso nenhum elemento atenda a condição, é retornado false
	 *
	 * @param array $arr
	 * @param callable $fn
	 * @return mixed
	 */
	private static function find(array $arr, callable $fn):mixed{
		foreach($arr as $index=>$item){
			if($fn($item, $index)){
				return $item;
			}
		}
		return false;
	}
}