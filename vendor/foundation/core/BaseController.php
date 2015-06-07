<?php
namespace system;

class BaseController{
	private $viewData=[];
	private $jsFiles=[];
	private $cssFiles=[];
	
	public function __construct(){
		
	}
	
	public function isAdminMode(){
		return false;
	}
	
	public function isFrontMode(){
		return !$this->isAdminMode();
	}
	
	public function render($file,$data=[],$return=false,$templatesDir=''){
		$this->setViewData('templateJsFiles', $this->jsFiles);
		$this->setViewData('templateCssFiles', $this->cssFiles);
		$data=array_merge($this->viewData,$data);
		if(empty($templatesDir)){
			$templatesDir=$this->isAdminMode()?'templates/admin/':'templates/front/';
		}
		return 
			View::render($templatesDir.'header',$data,$return).
			View::render($file,$data,$return).
			View::render($templatesDir.'footer',$data,$return);
	}
	
	public function renderNoTemplate($file,$data=[],$return=false){
		$data=array_merge($this->viewData,$data);
		return View::render($file,$data,$return);
	}
	
	public function setViewData($key,$val){
		$this->viewData[$key]=$val;
		return $this;
	}
	
	public function error($error,$renderNow=false){
		$this->setViewData('templateError',$error);
		if($renderNow){
			$this->render('templates/empty');
		}
		return $this;
	}

	public function message($message,$renderNow=false){
		$this->setViewData('templateMessage',$message);
		if($renderNow){
			$this->render('templates/empty');
		}
		return $this;
	}	
	
	public function addJs($file){
		if(stripos($file,'//')===false) 
			$file=\Url::base().'assets/js/'.$file;		
		$this->jsFiles[]=$file;
	}
	public function addCss($file){
		if(stripos($file,'//')===false) 
			$file=\Url::base().'assets/css/'.$file;		
		$this->cssFiles[]=$file;
	}	
}


