<?php
namespace sayri;

class Route{
	protected static $routes=[];
	
	public static function get($find,$route){
		static::$routes[$find]=$route;
	}
	
	public static function getRoutes(){
		return static::$routes;
	}
}