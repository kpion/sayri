<?php
//namespace system;

class Input{
	private static $get=[];
	private static $post=[];
	private static $cookie=[];
	private static $initialized=false;
	
	public static function initialize(){
		if(self::$initialized){
			return;
		}
		self::$initialized=true;
		self::$get=$_GET;
		self::$post=$_POST;
		self::$cookie=$_COOKIE;
		if (get_magic_quotes_gpc())
		{
			self::$get = array_map( 'stripslashes', self::$get );
			self::$post = array_map( 'stripslashes', self::$post );
			self::$cookie = array_map( 'stripslashes', self::$cookie );
		}
	}
	/**
	 * @todo: w przyszłości może usuwania xss
	 * @param type $string
	 * @return type
	 */
	public static function clear($string){
		return $string;
	}
	
	public function get($key){
		self::initialize();
		return empty(self::$get[$key])?'':self::clear(self::$get[$key]);
	}
	
	public static function post($key){
		self::initialize();
		return empty(self::$post[$key])?'':self::clear(self::$post[$key]);
	}
	
	public static function cookie($key){
		self::initialize();
		return empty(self::$cookie[$key])?'':self::clear(self::$cookie[$key]);
	}
	
	public static function test(){
		echo '<br>test in input</br>';
	}
}
