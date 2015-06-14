<?php

class ModController extends \app\core\AdminController{
	
	public function __construct(){
		$this->allow('moderator');
		parent::__construct();
	}
	public function actionIndex($param='domyślny param'){
		return View::get('admin/mod/index');
	}
}