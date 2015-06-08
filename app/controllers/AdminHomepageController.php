<?php

class AdminHomepageController extends \app\core\AdminController{
	
	public function actionIndex($param='domyślny param'){
		echo 'AdminHomepageController->actionIndex, param:'.$param;
	}
}