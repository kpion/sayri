<?php
function __autoload($class) {  
	//echo 'class:'.$class;die();
	global $projectFullPath,$appPath;
	//print_r(debug_backtrace() );
	//echo '<br>'.$class.'<br>';
	$path=str_replace('\\', '/',$class.'.php');
	if(strpos($path,'system/')===0){
		$path=str_replace('system/','vendor/foundation/core/',$path);
	}else{
		$path=$path;
	}
	$fullPath=$projectFullPath.$path;
	$dirs=[''];
	if(file_exists($fullPath)){
		include($fullPath);
	}
    /*if (file_exists(APPPATH."models/".strtolower($class).EXT)) {  
        require_once(APPPATH."models/".strtolower($class).EXT);  
    } */ 
} 