<?php
namespace foundation;
class ArrayUtils{
	/**
	 * Will return only specified keys from $sourceArray
	 * @param type $sourceArray
	 * @param type $keys
	 * @return type
	 */
	public static function limit(&$sourceArray, $keys){
		$ret=[];
		foreach($keys as &$key){
			$ret[$key]=$sourceArray[$key];
		}
		return $ret;
	}
}