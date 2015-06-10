<?php
namespace app\core;
use Utils;
/**
 * Used only when we enter the 'admin' panel, e.g. example.com/admin/something
 */
class AdminController extends \app\core\BaseController{
	public function __construct(){
		//full access to all controllers for admin role
		$this->allow('admin','*')->allow('moderator');
		//no access for guests
		//$this->setAccess('?','');
		Utils::vardump($this->accessRules);
		
		parent::__construct();
	}
	

}
