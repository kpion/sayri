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
	public static function print_r(){
		echo '<pre>';
		foreach(func_get_args() as $arg)
			print_r($arg);
		echo '</pre>';
	}	
	
	public static function var_dump(){
		echo '<pre>';
		foreach(func_get_args() as $arg)
			var_dump($arg);
		echo '</pre>';
	}	
	
}
