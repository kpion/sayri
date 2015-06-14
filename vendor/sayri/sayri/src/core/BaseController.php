<?php
namespace sayri;

class BaseController{
	/**
	 * for example
	 * [
	 *	'admin'=>['*'],
	 *  'moderator'=>['AdminController1','AdminController2']
	 * ]
	 * @var Array
	 */
	protected $accessRules=[];
	
	public function __construct(){
		$this->setAccess();
		if(!$this->hasAccess()){
			//$this->error('no access',true);
			Url::redirect('users/login');
			die();
		}
	}
	
	protected function setAccess(){
		
	}
	
	protected function hasAccess(){
		//var_dump($this->accessRules);
		if(empty($this->accessRules))
			return true;
		$hasAccess=false;
		$curUser=Auth::cur();
		$curController=Request::getController();
		
		foreach($this->accessRules as $role=>$controllers){
			$roleMatches=false;
			if(empty($curUser) && $role=='?') 
				$roleMatches=true;
			elseif(!empty($curUser) && $curUser->is($role)) 
				$roleMatches=true;
			if(!$roleMatches)
				continue;
			//ok, so we have rules for this role, maybe current controller is allowed?
			foreach($controllers as $controller){
				//* means any controller
				if($controller=='*'){
					$hasAccess=true;
					break;
				}
				if(mb_strcasecmp($controller, $curController)==0){
					$hasAccess=true;
					break;
				}
			}
			if($hasAccess)
				break;
		}
		return $hasAccess;
	}
	/**
	 * Will build a ->$access array
	 * @param string $roles, for example 'admin|moderator', or '?' (guest)
	 * @param string $controllers, for example '*' (any controller), 'AdminSpecificController', 
	 *				or empty - in this case this will apply to current controller
	 */
	public function allow($roles,$controllers=null){
		if(empty($controllers))
			$controllers=Request::getController();
		$roles=explode('|',$roles);
		$controllers=explode('|',$controllers);
		foreach($roles as $role){
			foreach($controllers as $controller){
				if(empty($this->accessRules[$role][$controller]))
					$this->accessRules[$role][]=$controller;
			}
		}
		return $this;
	}
}


