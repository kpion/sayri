<?php
$vendorDir=str_replace('\\','/',dirname(__DIR__).DIRECTORY_SEPARATOR);
$baseDir = str_replace('\\','/',dirname(dirname(dirname(dirname($vendorDir)))).DIRECTORY_SEPARATOR);

$namespaceAliases=[
	'foundation\\'=>$vendorDir.'core',
	'app\\'=>$baseDir.'app'
];
return $namespaceAliases;
