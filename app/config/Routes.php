<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$routes=array(
	''=>'homepage',//default controller for front
	'admin'=>'adminHomepage',//default controller for admin (that is when user enters /admin)
	'admin/(:any)'=>'adminHomepage/$1',
	'blah/(:any)'=>'tests/index/$1',
);

return $routes;
