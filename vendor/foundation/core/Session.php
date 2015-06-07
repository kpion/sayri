<?php
/**
 * @todo: In the future store the session in database
 */
class Session{
	public static function get($key){
		if(empty($_SESSION[$key]))
			return null;
		return $_SESSION[$key];
	}
	
	public static function set($key,$val){
		$_SESSION[$key]=$val;
	}
	
	public static function remove($key){
		unset($_SESSION[$key]);
	}
}

