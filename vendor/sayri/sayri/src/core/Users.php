<?php
namespace sayri;
class Users implements \ArrayAccess{
	//filled after calling ->load, this is only one user
	public $loaded=[];
	
	public function get($where){
		$whereQuery='';
		if(!empty($where)){
			$whereQuery='WHERE '.App::$db->buildWhere($where);
		}
		$q="
			SELECT users.*,users_roles.id roleId, roles.name as roleName
			FROM `users` 
			left join `users_roles` on users_roles.userId=users.id 
			left join `roles` on roles.id=users_roles.roleId 
			$whereQuery";
		//echo $q.'<br>';
		$users0=App::$db->query($q)->result();
		$users=[];
		foreach($users0 as &$u){
			$uid=$u['id'];
			if(empty($users[$uid])){
				$users[$uid]=$u;
				$users[$uid]['roles']=[];
			}
			$users[$uid]['roles'][]=['id'=>$u['roleId'],'name'=>$u['roleName']];
		}
		//printr($users);
		return $users;
	}
	public function getOne($where){
		$users=$this->get($where);
		if(empty($users))
			return false;
		//
		return reset($users);
	}
	
	public function getById($id){
		return $this->getOne(['users.id'=>$id]);
	}
	
	public function load($where){
		$this->loaded=$this->getOne($where);
		return $this->loaded;
	}
	
	public function isLoaded(){
		return !empty($this->loaded);
	}
	
	public function getLoaded(){
		return $this->loaded;
	}
	
	/**
	 * Checks if users has given role
	 * @param type $roleName
	 * @return boolean
	 */
	public function is($roleName){
		if(empty($this->loaded))
			return false;
		$roleName=strtolower($roleName);
		foreach($this->loaded['roles'] as $role){
			$role=strtolower($role['name']);
			if($role==$roleName)
				return true;
		}
		return false;
	}
	//ArrayAccess implementation
	public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->loaded[] = $value;
        } else {
            $this->loaded[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->loaded[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->loaded[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->loaded[$offset]) ? $this->loaded[$offset] : null;
    }
	
}
