<?php
namespace sayri;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Auth{
	/**
	 *
	 * @var sayri\Users
	 */
	private static $cur=false;

	/*
	public static function get($userId,$passwordHash=null){
		$where=['id'=>$userId];
		if(!empty($passwordHash))
			$where=['passwordHash'=>$passwordHash];
		//return \App::$db->get('users',$where)->resultOne();
		return App::$db->query("
			SELECT *,users_roles.id roleId
			FROM `users` 
			left join `users_roles` WHERE users.id=$userId")->resultOne();
	}
	*/
	
	/**
	 * 
	 * @param type $roleId - this will be used in the future
	 * @return type
	 */
	/*
	public static function getAll($roleId=null){
		return \App::$db->get('users')->result();
	}
	*/
	public static function cur(){
		if(!empty(self::$cur)){
			return self::$cur;
		}
		$curUserSession=\Session::get('curUser');
		if(empty($curUserSession))
			return false;
		self::$cur=new Users;
		self::$cur->load(['users.id'=>$curUserSession['id'],'users.passwordHash'=>$curUserSession['passwordHash']]);
		if(!self::$cur->isLoaded())
			return false;
		return self::$cur;
		//\$app->db-
	}
	
	/**
	 * Is current visitor logged in?
	 * @return bool
	 */
	public static function isLoggedIn(){
		return self::cur()!==false;
	}
	
	public static function login(Array $credentials){
		$user=new Users();
		$user->load(['login'=>$credentials['login']]);
		if(!$user->isLoaded())
			return false;
		if($user['passwordHash']!=self::generateHash($credentials['password'],$user['salt'])){
			return false;
		}
		\Session::remove('curUser');
		\Session::set('curUser',$user->getLoaded());
		self::$cur=$user;
		return true;
	}
	
	public static function logout(){
		self::$cur=false;
		Session::remove('curUser');
	}
	
	
	public static function add($login,$password,$data=[]){
		if(SAYRI_ENV=='dev'){
			$exists=App::$db->get('users',['login'=>$login])->resultOne();
			if($exists)
				throw new \Exception("User already exists");
		}
		$data['login']=$login;
		$data['salt']=self::generateSalt();
		$data['passwordHash']=self::generateHash($password,$data['salt']);		
		App::$db->insert('users', $data)->lastInsertId();
	}
	
	public static function generateHash($pass,$salt){
		return hash('sha256',hash('sha256', $pass.'_'.$salt));
	}
	
	public static function generateSalt(){
		return Utils::getRandomString('0123456789abcdefghijklmnoprstwxyzABCDEFGHIJKLMNOPRSTWXYZ',32);
	}
}