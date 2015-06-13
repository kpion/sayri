<?php
namespace foundation;
/**
 * 
 */
class ViewCompiler{
	protected $commands=[];
	
	public function __construct(){
		//registering standard commands
		$this->registerCommand('include', function($params,$data){
			//var_dump($params);
			$file=$params[0];
			return $this->compile($this->loadFile(App::$appDir.'views/'.$file.'.php',$data), $data);
		});
	}
	
	public function compile($string,$data=[]){
		//@include command
		//$includePattern='~@include\\s?\\(\\s?[\'"](.*?)[\'"]\\s?\\);?~';
		foreach($this->commands as $commandInfo){
			$command=$commandInfo['command'];
			$commandCallback=$commandInfo['callback'];
			$commandPattern="~@{$command}\s?\((,?.*?)\);?~";
			$result = preg_replace_callback(
				$commandPattern,
				function ($matches) use ($command,$commandCallback,$data) {
					//echo 'match:';var_dump($matches);echo '<br>';
					//return strtolower($matches[1]);
					//$file=$matches[1];
					$params = preg_split("~,(?=([^']*'[^']*')*[^']*$)~",$matches[1]);
					//Utils::var_dump($params);
					foreach($params as &$param){
						$param=trim($param,"'");
					}
					//$compiled=$this->compile($this->loadFile($params[0],$data), $data);
					//return $compiled;
					return $commandCallback($params,$data);
				},
				$string
			);
		}
		return $result;
	}
	
	public function registerCommand($command,$callback){
		$this->commands[]=['command'=>$command,'callback'=>$callback];
		return $this;
	}
	
	public function loadFile($file,$data){
		extract($data);
		ob_start();
		include($file);
		// Return the file data if requested
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
		//ob_end_flush();
		//return $this;		
	}
	
}

class ViewBase{
	protected $file='';
	protected $loaded='';
	protected $data=[];
	protected $compiler=null;
	
	public function __construct(ViewCompiler $compiler=null){
		if(empty($compiler))
			$compiler=new ViewCompiler;
		$this->compiler=$compiler;
	}
	
	public function compile ($string,$data=[]){
		return $this->compiler->compile($string,$data);
	}
	
	public function get($file,$data=[]){
		$this->file=Utils::correctPath($file);
		$this->data=array_merge($this->data,$data);
		return $this;
		/*
		extract($data);
		ob_start();
		include(App::$appDir.'views/'.$file.'.php');
		// Return the file data if requested
		$buffer = ob_get_contents();
		ob_end_clean();
		$this->loaded=$buffer;
		$this->data=$data;
		//ob_end_flush();
		return $this;
		 */
	}
	protected function loadFile(){
		$this->loaded=$this->compiler->loadFile(App::$appDir.'views/'.$this->file.'.php',$this->data);
		return $this;
	}
	
	public function getAsString(){
		return $this->loadFile()->compile($this->loaded,$this->data);
	}
	
	public function with($data,$val=null){
		if(!is_array($data))
			$data=[$data=>$val];
		$this->data=array_merge($this->data,$data);
		return $this;
	}
	
	public function getData(){
		return $this->data;
	}
	
	public function js($file){
		if(stripos($file,'//')===false) 
			$file=\Url::base().'assets/js/'.$file;		
		$this->data['templateJsFiles'][]=$file;
		return $this;
	}			
	
	public function css($file){
		if(stripos($file,'//')===false) 
			$file=\Url::base().'assets/css/'.$file;		
		$this->data['templateCssFiles'][]=$file;
		return $this;
	}			
	
	public function message($message){
		$this->data['templateMessages'][]=$message;
		return $this;
	}		
	
	public function error($error){
		$this->data['templateErrors'][]=$error;
		return $this;
	}
	
	public function dump(){
		foreach(func_get_args() as $arg)
			$this->data['templateDumps'][]=$arg;
		return $this;
	}
	
	public function __tostring(){
		return $this->getAsString();
	}	
	

}

class View {
	static protected $viewBase=null;
	
	static function getViewBase(){
		if(!empty(static::$viewBase))
			return static::$viewBase;
		static::$viewBase=new ViewBase;
		return static::$viewBase;
	}
	static function get($file,$data=[]){
		$viewBase=static::getViewBase();
		return $viewBase->get($file,$data);
	}

	public function __tostring(){
		return static::$viewBase->getAsString();
	}
	
	/**
	 * Thanks to this, we can call View::error('something'), which is declared in ViewBase class
	 * @param type $method
	 * @param type $arguments
	 * @return type
	 */
	public static function __callStatic($method, $arguments)
    {
		call_user_func_array(array(static::getViewBase(), $method), $arguments);		
		return static::$viewBase;
    }	
	
}