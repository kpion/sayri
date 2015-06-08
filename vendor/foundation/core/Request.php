<?php
/**
 * All about URL request - processes config/Routes.php file to resolve controller, method and parameters
 */
class Request{
	private static $initialized=false;
	private static $routes=[];
	//this will be string containing a controller/arguments
	private static $resolvedUrl='';
	private static $controller;//after resolving with config/Routes.php
	private static $method;//after resolving with config/Routes.php
	private static $parameters;//after resolving with config/Routes.php
	
	public static function initialize(){
		if(self::$initialized)
			return;
		self::$initialized=true;
		self::$routes=Config::getFile('routes');
		//resolving:
		$uri=$_SERVER['QUERY_STRING'];
		$uri=trim($uri,'/');
		foreach(self::$routes as $key=>&$val){
			// Convert our wild-cards to reg expr.
			if(is_array($val))
				continue;
			if(is_callable($val))
				$val=$val();
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
			//echo $key.$val.'<br>';
			$uri = preg_replace('#^'.$key.'$#', $val, $uri);
		}
		self::$resolvedUrl=$uri;
		//echo 'uri after resolving: '.$uri.'<br>';
		$uriSegments=explode('/',$uri);		
		//self::$controller=empty($uriSegments[0])?ucfirst(self::$routes['defaults']['front']).'Controller':ucfirst($uriSegments[0]).'Controller';
		self::$controller=ucfirst($uriSegments[0]).'Controller';
		self::$method=empty($uriSegments[1])?'actionIndex':'action'.ucfirst($uriSegments[1]);
		self::$parameters=[];
		for($n=2;$n<count($uriSegments);$n++){
			if(empty($uriSegments[$n]))
				break;
			self::$parameters[]=$uriSegments[$n];
		}
		//echo ' controller:'.self::$controller.' method:'.self::$method.' params:';var_dump(self::$parameters);echo '<br>';
	}
	
	/**
	 * returns the config/Routes.php array 
	 * @return type
	 */
	public static function getRoutes(){
		self::initialize();
		return self::$routes;
	}
	
	public static function getController(){
		self::initialize();
		return self::$controller;
	}
	
	public static function getMethod(){
		self::initialize();
		return self::$method;
	}
	
	public static function getParameters(){
		self::initialize();
		return self::$parameters;
	}

	
}
