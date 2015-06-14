<?php
namespace app\models;

class HomepageModel extends \app\core\Model{
	public function test(){
		
		//echo 'test in model';
		\sayri\View::dump('test','iza');
	}
}
