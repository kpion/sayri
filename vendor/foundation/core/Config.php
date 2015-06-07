<?php
//namespace system;


class Config{
	private static $configs=[];
	private static $initialized=false;
	private static function initialize(){
		if(self::$initialized){
			return;
		};
		self::$initialized=true;
		global $appPath;
		$autoload=require_once($appPath.'config/Autoload.php');	
		foreach($autoload as $al){
			$alUcFirst=ucfirst($al);
			self::$configs[$al]=require_once($appPath."config/{$alUcFirst}.php");	
		}
		
	}	
	public static function get($key,$file='config'){
		self::initialize();
		
		return self::$configs[$file][$key];
	}
}