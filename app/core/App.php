<?php
namespace app\core;
require_once(dirname(dirname(__DIR__)).'/vendor/sayri/sayri/src/core/App.php');
class App extends \sayri\App{
	public function __construct(){
		parent::__construct();
	}
	/**
	 * Here's an example of overloading an App method
	static public function abort404($additonalMessage='',$viewPage='errors/404'){
		header("HTTP/1.0 404 Not Found");
		echo \sayri\View::get($viewPage,['message'=>$additonalMessage]);
		die();
	}	
	 */
}