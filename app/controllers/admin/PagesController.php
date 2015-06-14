<?php

class PagesController extends \app\core\AdminController{
	public function actionIndex(){
		return View::get('admin/pages/adminIndex');
	}
	public function actionTests($param='default param'){
		echo 'AdminPagesController - tests, param:'.$param;
	}
}
