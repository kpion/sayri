<?php
//case insensitive comparison
if(!function_exists('mb_strcasecmp')){
	function mb_strcasecmp($str1, $str2, $encoding = null) {
		if (null === $encoding) {
			$encoding = mb_internal_encoding();//domyślnie chyba utf-8, jak nie to można gdzieś zrobić mb_internal_encoding("UTF-8"); by ustawić 
		}
		return strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str2, $encoding));
	}
}




