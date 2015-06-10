<?php
namespace foundation;

/**
 * Various utility functions
 */
class Utils{
	
	public static function getRandomString($validChars, $length){
		$randomString = "";
		$numValidChars = strlen($validChars);
		for ($i = 0; $i < $length; $i++){
			$randomPick = mt_rand(1, $numValidChars);
			$randomChar = $validChars[$randomPick-1];
			$randomString .= $randomChar;
		}
		return $randomString;
	}
	public static function printr($var){
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}	
	
	public static function vardump($var){
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}	
	
}
