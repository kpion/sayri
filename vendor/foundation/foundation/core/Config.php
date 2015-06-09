<?php
namespace foundation;


class Config{
	private static $configs=[];
	private static $initialized=false;
	private static function initialize(){
		if(self::$initialized){
			return;
		};
		self::$initialized=true;
		$autoload=require_once(App::$appDir.'config/Autoload.php');	
		foreach($autoload as $al){
			$alUcFirst=ucfirst($al);
			self::$configs[$al]=require_once(App::$appDir."config/{$alUcFirst}.php");	
		}
		
	}	
	/**
	 * returns ONE key from given config file ('config' file by default')
	 * @param type $key
	 * @param type $file
	 * @return type
	 */
	public static function get($key,$file='config'){
		self::initialize();
		return self::$configs[$file][$key];
	}
	/**
	 * returns the entire config array from given config file
	 * @param type $file
	 */
	public static function getFile($file){
		self::initialize();
		return self::$configs[$file];
	}
}