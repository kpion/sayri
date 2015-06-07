<?php
function getBaseUrl(){
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http";
	$u=$protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	$u=pathinfo($u);
	$base_url=$u['dirname'].'/';
	return $base_url;
}
$config=[
	'test'=>'blah2',
	'baseUrl'=>getBaseUrl(),
];

return $config;


