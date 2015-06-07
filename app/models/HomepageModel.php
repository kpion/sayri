<?php
namespace app\models;

class HomepageModel extends \app\core\Model{
	public function test(){
		\Input::test();
		echo 'test in model';
	}
}
