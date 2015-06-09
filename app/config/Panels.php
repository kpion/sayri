<?php

$panels=[
	//front panel (i.e. no panel at all)
	''=>[
		'controllerPrefix'=>'',
		'defaultController'=>'Homepage'
	],
	
	//admin panel, e.g. example.com//admin/users
	'admin'=>[
		'controllerPrefix'=>'admin',
		'defaultController'=>'Homepage'
	]
	//'admin'=>'admin'
];
return $panels;

