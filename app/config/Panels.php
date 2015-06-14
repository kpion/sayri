<?php

$panels=[
	/*
	//front panel (i.e. no panel at all)
	''=>[
		'controllerPrefix'=>'',
		'defaultController'=>'Homepage'
	],
	
	//admin panel, e.g. example.com/admin/users
	'admin'=>[
		'controllerPrefix'=>'admin',
		'defaultController'=>'Homepage'
	],
	*/
	''=>[
		'controllerDir'=>'',
		'defaultController'=>'Homepage'
	],
	
	//admin panel, e.g. example.com/admin/users
	'admin'=>[
		/*'controllerPrefix'=>'',*/
		'controllerDir'=>'admin',
		'defaultController'=>'Homepage'
	],	
	/**
	 * An example of a panel, where our controllers are in the main controllers directory, 
	 * that is: app/controllers, but prefixed with "Test"
	 */
	/*
	'test'=>[
		'controllerPrefix'=>'test',
		//'controllerDir'=>'test',
		'defaultController'=>'Some'
	]
	*/
];
return $panels;

