<?php

class HomepageController extends \app\core\AdminController{
	
	public function actionIndex(){
		return View::get('admin/homepage/homepage');
	}
	
	protected function hasAccess(){
		if(!parent::hasAccess())
			return false;
		//here we can check more conditions.
		return true;
	}
}