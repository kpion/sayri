<?php

class HomepageController extends \app\core\FrontController{
	private $mevents=null;
	
	/**
	 * Tu przede wszystkim dodanie potrzebnych css i js
	 */
	public function __construct() {
		parent::__construct();
		$this->addJs('calendar.js');
		$this->addCss('calendar.css');
		$this->addJs('datetimepicker/jquery.datetimepicker.js');
		$this->addCss(Url::base('assets/js/datetimepicker/jquery.datetimepicker.css'));
		$this->mevents=new app\models\EventsModel();
		if(!User::is()){
			$this->error('Musisz się zalogować',true);
			die();
		}
	}
	/**
	 * Strona główna kalendarza
	 * @param string from to np. 2015-06-06
	 * @param int userId - id user, jeśli puste to aktualnie zalogowany
	 */
	public function actionIndex($from=null,$userId=null){
		if(empty($from)){
			$from=date('Y-m-01');
		};
		$from.=' 00:00:00';
		$userId=(int)$userId;
		if(empty($userId))
			$userId=User::cur()['id'];
		$data=['from'=>$from,'userId'=>$userId];
		$this->render('calendar/calendar',$data);
	}
	
	
	/**
	 * Różne operacje ajaxowe, np. edycja zdarzenia
	 */
	public function actionAjax(){
		$action=Input::post('action');
		if($action=='getEvents'){
			$userId=Input::post('userId');//jeśli '0'all' to wszystkie
			$from=Input::post('from');
			$to=strtotime($from);
			$to=date('Y-m-d',strtotime('+1 month',$to));
			$events=$this->mevents->getByUserId($userId,$from,$to);
			$debugLog=['test'=>'blah'];
			die(json_encode(['error'=>'','events'=>$events,'debugLog'=>$debugLog]));
		}
		if($action=='addOrUpdate'){
			$data=Input::post('data');
			$dbData=ArrayUtils::limit($data,['title','description','from','userId']);
			$dbData['title']=strip_tags($dbData['title']);
			$dbData['description']=strip_tags($dbData['description']);
			$periodHours=(int)$data['periodHours'];
			$to=date('Y-m-d H:i:s',strtotime($dbData['from'].' +'.$periodHours.' hour'));
			$dbData['to']=$to;
			if(empty($data['id']))
				App::$db->insert('events',$dbData);
			else
				App::$db->update('events',$dbData,['id'=>$data['id']]);
			die(json_encode(['error'=>'']));
		}
		if($action=='delete'){
			$id=Input::post('id');
			$events=$this->mevents->delete($id);
			die(json_encode(['error'=>'']));
		}
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
		$this->render('homepage',['param1'=>$param1,'param2'=>$param2]);
	}
}
?>
