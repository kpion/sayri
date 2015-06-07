<?php
namespace system;
class View{
	public static function render($file,$data=[],$return=false){
		global $appPath;
		extract($data);
		ob_start();
		include($appPath.'views/'.$file.'.php');
		// Return the file data if requested
		if ($return)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}
		ob_end_flush();
	}
}