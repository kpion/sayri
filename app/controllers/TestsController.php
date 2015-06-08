<?php
class TestsController extends \app\core\FrontController{
	
	public function __construct() {
		parent::__construct();
	}
	
	public function actionIndex($param1,$param2){
		echo 'TestsController->actionIndex, params:'.$param1.' '.$param2;
	}
	
}
