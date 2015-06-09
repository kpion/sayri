<?php
namespace foundation;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Url{
	private static $baseUrl='';
	private static $initialized=false;
	
	private static function initialize(){
		if(self::$initialized){
			return;
		}
		self::$initialized=true;
		self::$baseUrl=Config::get('baseUrl');
	}
	
	public static function base($subUrl=''){
		self::initialize();
		return self::$baseUrl.$subUrl;
	}
	
	public static function redirect($url,$response_code=302){
		self::initialize();
		if(strpos($url,'http:')===false && strpos($url,'https:')===false)
			$url=self::base().$url;
		header("Location: ".$url, TRUE, $response_code);
		die();
	}
}