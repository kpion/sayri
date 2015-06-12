<?php
namespace foundation;
class ViewBase{
	protected $file='';
	protected $loaded='';
	protected $data=null;
	public function compile ($string,$data=[]){
		$includePattern='~@include\\s\\(\\s?[\'"](.*?)[\'"]\\s?\\)~';
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
		$this->data=$data;
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
	
	public function with($data){
		$this->data=array_merge($this->data,$data);
		return $this;
	}
}

class View{
	static function get($file,$data=[]){
		$view=new ViewBase();
		return $view->get($file,$data);
	}
}