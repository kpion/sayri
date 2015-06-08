<?php

class Routes{
	private static $routes=[];
	
	public static function initialize(){
		self::routes=Config::get();
	}
	public static function get(){
	}
}
