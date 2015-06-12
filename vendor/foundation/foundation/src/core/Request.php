<?php
namespace foundation;
/**
 * All about URL request - processes config/Routes.php file to resolve controller, method and parameters
 */
class Request{
	private static $initialized=false;
	private static $routes=[];
	//panels available in config/Panels.php
	private static $panels=[];
	//this will be string containing a controller/arguments
	private static $resolvedUrl='';
	//panel name - empty if there is no panel, or, for example 'admin'
	private static $panel;
	private static $controller;//after resolving with config/Routes.php
	private static $method;//after resolving with config/Routes.php
	private static $parameters;//after resolving with config/Routes.php
	private static $segments;//after resolving with config/Routes.php, contains url's segments, starting with controller name
	public static function initialize(){
		if(self::$initialized)
			return;
		self::$initialized=true;
		self::$routes=Config::getFile('routes');
		self::$panels=Config::getFile('panels');
		//resolving:
		$uri=$_SERVER['QUERY_STRING'];
		$uri=trim($uri,'/');
		//panels
		$controllerPrefix='';
		$panelOffset=0;
		$uriSegments0=explode('/',$uri);		
		$firstSegment=empty($uriSegments0[0])?'':$uriSegments0[0];
		$defaultController='';
		foreach(self::$panels as $panelName=>$panelData){
			if($firstSegment==$panelName){
				self::$panel=$panelName;
				$controllerPrefix=ucfirst($panelData['controllerPrefix']);
				$defaultController=ucfirst($panelData['defaultController']);
				$panelOffset=1;
				break;
			}
		}

		foreach(self::$routes as $key=>&$val){
			// Convert our wild-cards to reg expr.
			if(is_array($val))
				continue;
			if(is_callable($val))
				$val=$val();
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
			/*
			if(is_a($val,'foundation\ViewBase')){
				echo $val;
				continue;
			}*/
			//echo $key.$val.'<br>';
			$uri = preg_replace('#^'.$key.'$#', $val, $uri);
		}
		self::$resolvedUrl=$uri;
		//echo 'uri after resolving: '.$uri.'<br>';
		$uriSegments=self::$segments=explode('/',$uri);		
		//self::$controller=$controllerPrefix.ucfirst($uriSegments[0+$panelOffset]).'Controller';
		$controller=$controllerPrefix;
		if(!empty($uriSegments[0+$panelOffset]))
			$controller.=ucfirst($uriSegments[0+$panelOffset]);
		else
			$controller.=$defaultController;
		$controller.='Controller';
		self::$controller=$controller;
		
		self::$method=empty($uriSegments[1+$panelOffset])?'actionIndex':'action'.ucfirst($uriSegments[1+$panelOffset]);
		self::$parameters=[];
		for($n=2+$panelOffset;$n<count($uriSegments);$n++){
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
	public static function getPanel(){
		self::initialize();
		return self::$panel;
	}
	/**
	 * returns a URL segment (after resolving with config/Routes.php) starting with controllers name
	 * @param type $index
	 * @return string
	 */
	public static function getSegment($index){
		self::initialize();
		if(!empty(self::$segments[$index]))
			return self::$segments[$index];
		return '';
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
