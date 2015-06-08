<?php
namespace app\models;
/**
 * Model zdarzeń, pobieranie, dodawanie etc.
 */
class EventsModel extends \app\core\Model{
	
	public function __construct(){
		parent::__construct();
	}
	/**
	 * Popbranie zdarzeń. Tutaj też dodamy info dla każdego zdażenia np. ile godzin ono trwa
	 * @param type $where
	 * @return type
	 */
	private function get($where){
		\App::$db->orderBy('`from` ASC');
		$events=\App::$db->get('events',$where)->result();
		foreach($events as &$event){
			$event['fromDate']=date('Y-m-d',strtotime($event['from']));
			$event['toDate']=date('Y-m-d',strtotime($event['to']));
			$event['periodHours']=(strtotime($event['to'])-strtotime($event['from']))/(60*60);
		}
		return $events;
	}
	
	/**
	 * Pobranie zdarzeń dla danego usera
	 * @param type $userId
	 * @param type $from format to: 2015-06-07
	 * @param type $to
	 * @return type
	 */
	public function getByUserId($userId,$from=null,$to=null){
		$where=[];
		if($userId!='all'){
			$userId=(int)$userId;
			$where="userId='$userId'";
		}
		if(!empty($from)){
			$from=\App::$db->quote($from);
			$where.=" AND `from`>$from";
		}
		if(!empty($to)){
			$from=\App::$db->quote($to);
			$where.=" AND `to`>$to";
		}
		
		return $this->get($where);
	}
	
	/**
	 * Pobranie wg Id
	 * @param type $id
	 * @return mixed
	 */
	public function getById($id){
		$where=['userId'=>$id];
		$ret=$this->get($where);
		if(!empty($ret))
			return $ret[0];
		return false;
	}
	
	/**
	 * Dodanie nowego zdarzenia
	 * @param type $userId
	 * @param type $title
	 * @param type $description
	 * @return type
	 */
	public function add($userId,$title,$description){
		return \App::$db->insert('events',['userId'=>$userId,'title'=>$title,'description'=>$description])->lastInsertId();
	}
	
	/**
	 * Usunięcie zdarzenia o podanym id
	 * @param type $id
	 */
	public function delete($id){
		\App::$db->delete('events',['id'=>$id]);
	}	
	
	
	
}