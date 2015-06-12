<?php
defined('FOUNDATION_DEBUG') or define('FOUNDATION_DEBUG', true);
defined('FOUNDATION_ENV') or define('FOUNDATION_ENV', 'dev');//dev or production
//require_once('vendor/foundation/foundation/src/core/App.php');
require_once('app/core/App.php');

\app\core\App::run(__DIR__.'/');

//class_alias('vendor\foundation\core\Tests','Tests');
?>