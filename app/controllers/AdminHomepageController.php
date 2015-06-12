<?php

class AdminHomepageController extends \app\core\AdminController{
	
	public function actionIndex($param='domyślny param'){
		return View::get('admin/homepage/homepage')->with(['test'=>'blah']);
	}
	
	protected function hasAccess(){
		if(!parent::hasAccess())
			return false;
		return true;
	}
}