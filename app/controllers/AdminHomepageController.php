<?php

class AdminHomepageController extends \app\core\AdminController{
	
	public function actionIndex($param){
		echo 'AdminHomepageController->actionIndex, param:'.$param;
	}
}