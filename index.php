<?php
//phpinfo();die();
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 'On');

session_start();
$uriSegments=$_SERVER['QUERY_STRING'];
$uriSegments=explode('/',$uriSegments);

$appPath='app/';
$projectFullPath=str_replace('\\','/',__DIR__).'/';
$frameworkPath='vendor/foundation/';
require_once($frameworkPath.'core/Autoload.php');
//require_once($frameworkPath.'core/Controller.php');
require_once($frameworkPath.'core/ArrayUtils.php');
require_once($frameworkPath.'core/Db.php');
require_once($frameworkPath.'core/Session.php');
require_once($frameworkPath.'core/User.php');
require_once($frameworkPath.'core/App.php');
require_once($frameworkPath.'core/Config.php');
require_once($frameworkPath.'core/Input.php');
require_once($frameworkPath.'core/Url.php');


//App::$input=new \system\Input();
//echo App::$input->get('blah');
//require_once($appPath.'core/Controller.php');
//database
$dbConfig=\Db::getConfig('default');
//var_dump($dbConfig);
\App::$db=new Db($dbConfig['dsn'],$dbConfig['user'],$dbConfig['password']);
require_once($appPath.'config/Routes.php');
require_once($frameworkPath.'core/View.php');
$controllerName=empty($uriSegments[1])?$routes['defaultController']:ucfirst($uriSegments[1]).'Controller';
$controllerMethod=empty($uriSegments[2])?'actionIndex':'action'.ucfirst($uriSegments[2]);
$controllerMethodParams=[];
for($n=3;$n<count($uriSegments);$n++){
	if(empty($uriSegments[$n]))
		break;
	$controllerMethodParams[]=$uriSegments[$n];
}
require_once($appPath.'controllers/'.$controllerName.'.php');
$controllerClass=$controllerName;
$controller=new $controllerClass();
//$controller->$controllerMethod(...['blah']);//php 5.6
call_user_func_array(array($controller, $controllerMethod), $controllerMethodParams);
//echo 'afd';

?>