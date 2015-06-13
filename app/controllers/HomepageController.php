<?php

class HomepageController extends \app\core\FrontController{
	private $mhomepage;
	public function __construct() {
		parent::__construct();
		$this->mhomepage=new app\models\HomepageModel();
	}
	
	public function actionIndex(){
		return View::get('homepage/index');
	}
}
?>
