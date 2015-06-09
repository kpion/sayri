<?php

class AdminHomepageController extends \app\core\AdminController{
	
	public function actionIndex($param='domyślny param'){
		
		$this->render('admin/homepage/homepage');
	}
}