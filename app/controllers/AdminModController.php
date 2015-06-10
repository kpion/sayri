<?php

class AdminModController extends \app\core\AdminController{
	
	public function __construct(){
		$this->allow('moderator');
		parent::__construct();
	}
	public function actionIndex($param='domyślny param'){
		
		$this->render('admin/mod/index');
	}
}