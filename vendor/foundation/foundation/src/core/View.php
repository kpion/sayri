<?php
namespace foundation;
/**
 * 
 */
class ViewCompiler{
	protected $commands=[];
	//currently parsed file
	protected $currentFile='';
	public function __construct(){
		//registering standard commands
		$this->registerCommand('include', function($params,$data){
			
			$file=$params[0];
			$data['parameters']=$params;
			array_shift($data['parameters']);//we remove the 'file' parameter
			//Utils::var_dump($data['parameters']);
			return $this->compile($this->loadFile(App::$appDir.'views/'.$file.'.php',$data), $data);
		});
	}
	
	public function compile($string,$data=[]){
		//@include command
		//$includePattern='~@include\\s?\\(\\s?[\'"](.*?)[\'"]\\s?\\);?~';
		foreach($this->commands as $commandInfo){
			$command=$commandInfo['command'];
			$commandCallback=$commandInfo['callback'];
			$commandPattern="~@{$command}\s?\((,?.*)\);?~";
			$curFile=$this->currentFile;
			$result = preg_replace_callback(
				$commandPattern,
				function ($matches) use ($command,$commandCallback,$data,$curFile) {
					//$params = preg_split("~,(?=([^']*'[^']*')*[^']*$)~",$matches[1]);
					//yeah, suppressing error, a syntax error is catched below
					$params=eval('return ['.$matches[1].'];');
					//Utils::var_dump($params);
					if($params===false){
						die("
							Something wrong with partial view parameters.<br>
							Processed file: {$curFile}
							");
					}
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
		$this->currentFile=$file;
		extract($data);
		ob_start();
		include(Utils::correctPath($file));
		// Return the file data if requested
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
		//ob_end_flush();
		//return $this;		
	}

	/**
	 * Used internally, when array parameter is used, for example @include('file',['variable'=>'value'])
	 * @param type $str
	 * @return type
	 */
	protected function getArrayParams($str){
		$str=trim($str,'[]');
		$params = preg_split("~,(?=([^']*'[^']*')*[^']*$)~",$str);
		$ret=[];
		foreach($params as &$p){
			//$nameAndVal=str_replace();
			$nameAndVal = preg_split("~=\>(?=([^']*'[^']*')*[^']*$)~",$p);
			$name=$nameAndVal[0];
			$val=trim($nameAndVal[1],"'");
			/*echo '<br>next param:<br>';
			echo '<pre>';
			echo "name: {$name} val: {$val}<br>";
			echo '</pre>';
			 */
			$ret[]=[$name=>$val];
		}
		//var_dump($params);
		return $ret;
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