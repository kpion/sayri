<?php
namespace app\core;
/**
 * Used only when we enter the 'admin' panel, e.g. example.com/admin/something
 */
class AdminController extends \app\core\BaseController{
	public function __construct(){
		parent::__construct();
	}
	
	public function access(){
		return [
			//guests have no access to this panel:
			'?'=>'',
			//admins have access to all the controllers:
			'admin'=>'*',
		];
	}

}
