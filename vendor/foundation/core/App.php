<?php
//namespace system;
/**
 * Wyjątkowo nie w namespace app by można było po prostu napisać App::coś_tam
 */
class App{
	/**
	 *
	 * @var \Db 
	 */
	static public $db;
	
	static public function abort404($additonalMessage='',$viewPage='errors/404'){
		header("HTTP/1.0 404 Not Found");
		system\View::render($viewPage,['message'=>$additonalMessage]);
		die();
	}
	
	static public function run(){
		
	}
}
