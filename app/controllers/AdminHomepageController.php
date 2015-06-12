<?php

class AdminHomepageController extends \app\core\AdminController{
	
	public function actionIndex($param='domyślny param'){
		View::error('błąd!!!!!')->error('Drugi błąd');
		return View::get('admin/homepage/homepage')->with('test','test var');
	}
	
	protected function hasAccess(){
		if(!parent::hasAccess())
			return false;
		return true;
	}
}