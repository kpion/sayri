<?php
defined('SAYRI_DEBUG') or define('SAYRI_DEBUG', true);
defined('SAYRI_ENV') or define('SAYRI_ENV', 'dev');//dev or production
//require_once('vendor/sayri/sayri/src/core/App.php');
require_once('app/core/App.php');

\app\core\App::run(__DIR__.'/');

//class_alias('vendor\sayri\core\Tests','Tests');
?>