<?php

class AdminPagesController extends \app\core\AdminController{
	public function actionIndex(){
		echo 'AdminPagesController - index';
	}
	public function actionTests($param='default param'){
		echo 'AdminPagesController - tests, param:'.$param;
	}
}
