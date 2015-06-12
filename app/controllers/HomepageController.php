<?php

class HomepageController extends \app\core\FrontController{
	private $mhomepage;
	public function __construct() {
		parent::__construct();
		$this->mhomepage=new app\models\HomepageModel();
	}
	
	public function actionIndex(){
		//var_dump('ala','iza');
		$this->mhomepage->test();
		return View::get('homepage/index');
	}
	
	
	public function actionAjax(){
		$action=Input::post('action');
		die(json_encode(['error'=>'unknown action']));
	}
	
	/**
	 * To tylko testy
	 * @param type $param1
	 * @param type $param2
	 */
	public function actionTest($param1,$param2){
		//echo \Input::post('blah');
		//echo \Config::get('test');
		echo 'baseUrl:'.\Url::base();
		$mhomePage=new app\models\HomepageModel();
		$mhomePage->test();
		//$test=new system\Model();
		return View::get('homepage',['param1'=>$param1,'param2'=>$param2]);
	}
}
?>
