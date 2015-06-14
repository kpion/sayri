<?php
namespace sayri;
class Autoload{
	private $namespaceAliases=[];
	function __construct(){
		$this->namespaceAliases=require_once(App::$frameworkDir.'config/NamespaceAliases.php');
		spl_autoload_register([$this,'load'],true,true);
	}
	
	public function load($class){
		//echo '<br>---<br>autloading class:'.$class.'<br>';
		//global $projectFullPath,$appPath;
		$projectDir=App::$projectDir;//ended with a slash
		
		//var_dump($namespaceAliases);
		//
		//print_r(debug_backtrace() );
		//echo '<br>'.$class.'<br>';
		$path=str_replace('\\', DIRECTORY_SEPARATOR,$class);
		$path=str_replace('/', DIRECTORY_SEPARATOR,$path);
		$path.='.php';
		if(strpos($path,'system/')===0){
			$path=str_replace('system/','vendor/sayri/sayri/core/',$path);
		};
		//var_dump($this->namespaceAliases);echo '<br>';
		$namespaceAliasFound=false;
		foreach($this->namespaceAliases as $shortcut=>$namespaceAlias){
			$namespaceAlias=str_replace('\\', DIRECTORY_SEPARATOR,$namespaceAlias);
			$shortcut=str_replace('\\', DIRECTORY_SEPARATOR,$shortcut);
			//echo "replacing $shortcut with $namespaceAlias in $path <br>";
			if(strpos($path,$shortcut)===0){
				$path=str_replace($shortcut,$namespaceAlias.DIRECTORY_SEPARATOR,$path);
				$namespaceAliasFound=true;
				break;
			}
		}
		$fullPath=$path;
		if(!$namespaceAliasFound)
			$fullPath=$projectDir.$fullPath;
		
		//echo 'autoloading full path: '.$fullPath;
		$dirs=[''];
		if(file_exists($fullPath)){
			//echo ' (OK, including it)'.'<br>';
			include($fullPath);
		}else{
			//echo ' (File doesn\'t exists)<br>';
		}
		
	}
}
/*
function __autoload($class) {  
	echo 'autloading class:'.$class.'<br>';
	//global $projectFullPath,$appPath;
	$projectDir=\App::$projectDir;//ended with a slash
	//$namespaceAliases=require_once(\App::$frameworkDir.'config/NamespaceAliases.php');
	//var_dump($namespaceAliases);
	//
	//print_r(debug_backtrace() );
	//echo '<br>'.$class.'<br>';
	$path=str_replace('\\', '/',$class.'.php');
	if(strpos($path,'system/')===0){
		$path=str_replace('system/','vendor/sayri/core/',$path);
	};
	if(0)
	foreach($namespaceAliases as $namespaceAlias){
		if(strpos($path,$namespaceAlias)===0){
			$path=str_replace($namespaceAlias,$namespaceAlias,$path);
		}
	}
	$fullPath=$projectDir.$path;
	echo 'autoloading full path: '.$fullPath.'<br>';
	$dirs=[''];
	if(file_exists($fullPath)){
		echo 'autloading, including: '.$fullPath.'<br>';
		include($fullPath);
	}
} */