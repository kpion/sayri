<?php
/*
//testy
		//$includePattern='~@include\\s?\\(\\s?[\'"](.*?)[\'"]\\s?\\);?~';
		//$includePattern='~@include \((,?.*?)\)~';
		$includePattern='~@include \((,?.*?)\)~';
		$string='
			blah<br>
			@include (\'file_to_load\')
			<br>
			@include (\'file_to_load\',\'param1,12\',\'param2\',[\'blah\'=>\'kacz,ka\'])
				';
	$params=[];
	$result = preg_replace_callback(
		$includePattern,
		function ($matches) {
			echo '---iteration---';
			echo '<br>params:'.$matches[1].'<br>';
			$params = preg_split("~,(?=([^']*'[^']*')*[^']*$)~",$matches[1]);
			echo '<pre>';
			var_dump($params);
			echo '</pre>';
			//$file=$matches[1];
			//echo '<br>file:'.$file.'<br>';
			return $matches[1];
		},
		$string
	);
echo $result;
die();
  */
//
defined('FOUNDATION_DEBUG') or define('FOUNDATION_DEBUG', true);
defined('FOUNDATION_ENV') or define('FOUNDATION_ENV', 'dev');//dev or production
//require_once('vendor/foundation/foundation/src/core/App.php');
require_once('app/core/App.php');

\app\core\App::run(__DIR__.'/');

//class_alias('vendor\foundation\core\Tests','Tests');
?>