<?php
namespace foundation;
class ViewBase{
	protected $file='';
	protected $loaded='';
	protected $data=[];
	public function compile ($string,$data=[]){
		//@include command
		$includePattern='~@include\\s\\(\\s?[\'"](.*?)[\'"]\\s?\\);?~';
		$result = preg_replace_callback(
				$includePattern,
				function ($matches) use ($data) {
					//echo 'match:';var_dump($matches);echo '<br>';
					//return strtolower($matches[1]);
					$file=$matches[1];
					$view=new static();
					return $view->get($file,$data)->getAsString();
				},
				$string
			);
		return $result;
	}
	
	public function get($file,$data=[]){
		$this->file=$file;
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
		extract($this->data);
		ob_start();
		include(App::$appDir.'views/'.$this->file.'.php');
		// Return the file data if requested
		$buffer = ob_get_contents();
		ob_end_clean();
		$this->loaded=$buffer;
		//ob_end_flush();
		return $this;		
	}
	
	public function getAsString(){
		$this->loadFile();
		return $this->compile($this->loaded,$this->data);
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
	
	public function __tostring(){
		return $this->getAsString();
	}	
	

}

class View{
	static protected $additionalData=[];
	static protected $viewBase=null;
	
	static function getViewBase(){
		if(!empty(static::$viewBase))
			return static::$viewBase;
		static::$viewBase=new ViewBase;
		return static::$viewBase;
	}
	static function get($file,$data=[]){
		$viewBase=static::getViewBase();
		foreach(static::$additionalData as $key=>$val){
			$viewBase->with($key,$val);
		}
		return $viewBase->get($file,$data);//->with('templateCssFiles',static::$cssFiles)->with('templateJsFiles',static::$jsFiles);
	}

	public function __tostring(){
		return static::$viewBase->getAsString();
	}
	
	public static function __callStatic($method, $arguments)
    {
		call_user_func_array(array(static::getViewBase(), $method), $arguments);		
		return static::$viewBase;
    }	
	
}