<?php
namespace sayri;
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
	private static $controllerPath;
	private static $method;//after resolving with config/Routes.php
	private static $parameters;//after resolving with config/Routes.php
	private static $segments;//after resolving with config/Routes.php, contains url's segments, starting with controller name
	public static function initialize(){
		if(self::$initialized)
			return;
		self::$initialized=true;
		//self::$routes=Config::getFile('routes');
		self::$routes=Route::getRoutes();
		self::$panels=Config::getFile('panels');
		//resolving:
		$uri=$_SERVER['QUERY_STRING'];
		$uri=trim($uri,'/');
		//panels
		$controllerPrefix='';
		$controllerDir='';//app/controllers/
		$defaultController='';
		$panelOffset=0;
		$uriSegments0=explode('/',$uri);		
		$firstSegment=empty($uriSegments0[0])?'':$uriSegments0[0];
		
		foreach(self::$panels as $panelName=>$panelData){
			if($firstSegment==$panelName){
				self::$panel=$panelName;
				if(!empty($panelData['controllerPrefix']))
					$controllerPrefix=ucfirst($panelData['controllerPrefix']);
				if(!empty($panelData['controllerDir']))
					$controllerDir=$panelData['controllerDir'].DIRECTORY_SEPARATOR;
				$defaultController=ucfirst($panelData['defaultController']);
				$panelOffset=1;
				break;
			}
		}
		$matchingRoute=[];
		$controllerFromRoute='';
		foreach(self::$routes as $find=>&$route){
			// Convert our wild-cards to reg expr.
			if(is_array($route))
				continue;
			if(is_callable($route))
				$val=$val();
			$find = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $find));
			/*
			if(is_a($val,'sayri\ViewBase')){
				echo $val;
				continue;
			}*/
			//echo $key.$val.'<br>';
			//
			if(preg_match("~$find~", $uri, $match)){
				$matchingRoute=['find'=>$find,'route'=>$route];
				/*if(preg_match('~.*?Controller~', $route, $match)){
					$segments=explode('/',$match[0]);
					$controllerFromRoute=$segments[count($segments)-1];
				}*/
			}
			$uri = preg_replace('#^'.$find.'$#', $route, $uri);
		}
		//echo 'controllerFromRoute:'.$controllerFromRoute.'<br>';
		$uri=str_replace('\\','/',$uri);//n
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
		
		/*
		$controller=$uriSegments[count($uriSegments)-1];
		$controller=ucfirst($controller);
		 */
		//$controller=$controllerFromRoute;
		
		
		self::$controllerPath=Utils::correctPath(App::$appDir.'controllers/'.$controllerDir.$controller.'.php');
		
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
	
	public static function getControllerPath(){
		self::initialize();
		return self::$controllerPath;
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
