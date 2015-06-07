<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User{
	private static $cur=false;
	
	public static function get($userId,$passwordHash=''){
		$where=['id'=>$userId];
		if(!empty($passwordHash))
			$where=['passwordHash'=>$passwordHash];
		return \App::$db->get('users',$where)->resultOne();
	}
	
	/**
	 * 
	 * @param type $roleId - this will be used in the future
	 * @return type
	 */
	public static function getAll($roleId=null){
		return \App::$db->get('users')->result();
	}
	
	public static function cur(){
		if(!empty(self::$cur)){
			return self::$cur;
		}
		$curUserSession=\Session::get('curUser');
		if(empty($curUserSession))
			return false;
		self::$cur=self::get($curUserSession['id'],$curUserSession['passwordHash']);
		return self::$cur;
		//\$app->db-
	}
	
	/**
	 * Is current visitor logged in?
	 * @return bool
	 */
	public static function is(){
		return self::cur()!==false;
	}
	
	public static function login($login,$pass){
		
		$passHash=self::hash($pass);
		$userDb=\App::$db->get('users',array('login'=>$login,'passwordHash'=>$passHash))->resultOne();
		if(empty($userDb)){
			return false;
		}
		\Session::remove('curUser');
		\Session::set('curUser',['id'=>$userDb['id'],'passwordHash'=>$userDb['passwordHash']]);
		return true;
	}
	
	public static function logout(){
		self::$cur=false;
		\Session::remove('curUser');
	}
	
	public static function hash($pass){
		return hash('sha256',hash('sha256', $pass));
	}
}