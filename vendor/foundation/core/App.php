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
	static public $projectDir;
	static public $appDir;
	static public function abort404($additonalMessage='',$viewPage='errors/404'){
		header("HTTP/1.0 404 Not Found");
		system\View::render($viewPage,['message'=>$additonalMessage]);
		die();
	}
	
	static public function run($projectDir){
		error_reporting(E_ALL|E_STRICT);
		ini_set('display_errors', 'On');
		session_start();		
		$projectDir=str_replace('\\','/',$projectDir);
		self::$projectDir=$projectDir;
		self::$appDir=$projectDir.'app/';
		
				
		$frameworkPath='vendor/foundation/';
		foreach(
				['core/Autoload','core/ArrayUtils','core/Db','core/Session','core/User',
				'core/Config','core/Input','core/Url','core/Request','core/View'
			] as $frameworkFile){
			require_once($frameworkPath.$frameworkFile.'.php');
		}
		//App::$input=new \system\Input();
		//echo App::$input->get('blah');
		//require_once($appPath.'core/Controller.php');
		//database
		$dbConfig=\Db::getConfig('default');
		//var_dump($dbConfig);
		self::$db=new Db($dbConfig['dsn'],$dbConfig['user'],$dbConfig['password']);

		$controllerClass=Request::getController();
		$controllerFile=self::$appDir.'controllers/'.$controllerClass.'.php';
		if(file_exists($controllerFile)){
			require_once($controllerFile);
			$controller=new $controllerClass();
		}
		$controllerMethod=Request::getMethod();
		if(empty($controller) || !method_exists($controller,$controllerMethod)){
			self::abort404('Controller: '.$controllerClass.' method: '.$controllerMethod);
		}


		call_user_func_array(array($controller, $controllerMethod), Request::getParameters());		
		
	}
}
