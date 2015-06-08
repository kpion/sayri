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
require_once($frameworkPath.'core/Request.php');
require_once($frameworkPath.'core/View.php');
//App::$input=new \system\Input();
//echo App::$input->get('blah');
//require_once($appPath.'core/Controller.php');
//database
$dbConfig=\Db::getConfig('default');
//var_dump($dbConfig);
\App::$db=new Db($dbConfig['dsn'],$dbConfig['user'],$dbConfig['password']);

require_once($appPath.'controllers/'.Request::getController().'.php');
$controllerClass=Request::getController();
$controller=new $controllerClass();
//$controller->$controllerMethod(...['blah']);//php 5.6
call_user_func_array(array($controller, Request::getMethod()), Request::getParameters());

?>