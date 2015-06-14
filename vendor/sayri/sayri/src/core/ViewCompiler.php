<?php
namespace sayri;
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
