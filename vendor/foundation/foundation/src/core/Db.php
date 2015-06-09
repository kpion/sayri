<?php
namespace foundation;
use \PDO;
class Db{
	public $pdo=null;
	//result of $->query(), ->exec and others;
	private $result=[];
	//result of ->insert and ->update
	private $affectedRows=0;
	//here we'll store the ->where('someting')
	private $where='';
	//here we'll store the ->orderBy('someting')
	private $orderBy='';
	
	//database configurations
	private static $configs=[];
	private static $initialized=false;
	
	public function __construct($dsn,$user,$password){
		self::initialize();
		$this->connect($dsn,$user,$password);
	}
	
	public static function initialize(){
		if(self::$initialized){
			return;
		};
		self::$initialized=true;
		//self::$configs=require_once($appPath."config/Db.php");
		self::$configs=Config::getFile('db');
	}
	
	public function connect($dsn,$user,$password){
		self::initialize();
		try{
		$this->pdo=new PDO($dsn, $user, $password,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
		}catch(PDOException $e){
			echo 'Can\'t connect to database, please modify app/config/Db.php file';die();
		}
	}
	
	public static function getConfig($dbConfigName='default'){
		self::initialize();
		return self::$configs[$dbConfigName];
	}
	
	public function reset(){
		$this->where=$this->orderBy='';
		$this->result=[];
	}
	
	public function quote($str){
		return $this->pdo->quote($str);
	}
	/**
	 * Will convert for example users.id to `users`.`id` - used e.g. by ->buildWhere
	 * @param type $column
	 * @return string
	 */
	public function prepareColumn($column){
		$exploded=explode('.',$column);
		$ret='';
		$glue='';
		foreach($exploded as $ex){
			$ret.=$glue.'`'.$ex.'`';
			$glue='.';
		}
		return $ret;
	}	
	public function buildWhere($where, $operator='AND'){
		if(empty($where))
			return '';
		if(is_string($where))
			return $where;
		$ret='';
		$glue='';
		foreach($where as $column=>&$value){
			$value=$this->pdo->quote($value);
			$column=$this->prepareColumn($column);
			$ret.="$glue $column = {$value}";
			$glue=' '.$operator.' ';
		}
		//echo '<br>ret:'.$ret.'<br>';
		return $ret;
	}
	
	public function where($where,$operator='AND'){
		if(empty($where)){
			return $this;
		}
		if(!empty($this->where)){
			$this->where.=' '.$operator.' ';
		}
		$this->where.=$this->buildWhere($where);
		return $this;
	}
	
	private function getQueryWhere(){
		if(!empty($this->where)){
			return 'WHERE '.$this->where;
		};
		return '';
	}
	
	
	public function orderBy($orderBy){
		$this->orderBy=$orderBy;
	}
	
	private function getQueryOrderBy(){
		if(!empty($this->orderBy)){
			return 'ORDER BY '.$this->orderBy;
		};
		return '';
	}
	
	private function bindValues($data,&$sth){
		foreach($data as $column=>&$value){
			$sth->bindValue(':'.$column,$value,PDO::PARAM_STR);
		}
		return $this;
	}

	public function result(){
		$r=$this->result;
		$this->reset();
		return $r;
	}
	
	public function resultOne(){
		$r=$this->result;
		$this->reset();
		if(!empty($r[0]))
			return $r[0];
		return false;
	}
	
	public function lastInsertId(){
		return $this->pdo->lastInsertId();
	}
	
	public function affectedRows(){
		return $this->affectedRows;
	}
			
	public function query($query){
		try{
			$sth=$this->pdo->query($query);
		}catch(PDOException $e){
			echo $e->getMessage();
			print_r($e->getTrace());
			die();
		}
		$this->result=false;
		if($sth===false)
			return $this;
		try{
			$this->result=$sth->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException	$e){
			//we do nothing, because this will be also thrown when there is nothing to fetch,
			//for example, when there is a 'DELETE' query.
		}
		$sth->closeCursor();
		return $this;
	}
	
	public function exec($query){
		$this->pdo->exec($query);
	}

	
	public function get($table,$where=null){
		/*$queryWhere=$this->buildWhere($where);
		if(!empty($where)){
			$queryWhere='WHERE '.$queryWhere;
		}*/
		$queryWhere='';
		if(!empty($where)){
			$this->where($where);
			$queryWhere=$this->getQueryWhere();
		}
		$queryOrderBy=$this->getQueryOrderBy();
		$query="SELECT * FROM `{$table}` $queryWhere $queryOrderBy";
		$sth=$this->pdo->prepare($query);
		$this->affectedRows=$sth->execute();
		$this->result=$sth->fetchAll(PDO::FETCH_ASSOC);
		return $this;
	}
	
	public function insert($table,$data){
		$columns='(';
		$values=' VALUES (';
		$glue='';
		foreach($data as $column=>&$value){
			$columns.="{$glue}`$column`";
			$values.="{$glue}:$column";
			$glue=',';
		}
		$columns.=')';
		$values.=')';
		$query="INSERT INTO `$table` {$columns} {$values}";
		$sth=$this->pdo->prepare($query);
		$this->bindValues($data,$sth);
		$this->affectedRows=$sth->execute();
		return $this;
	}
	
	public function update($table, $data, $where=''){
		$this->where($where);
		$queryWhere=$this->getQueryWhere();		
		$values='';
		$glue='';
		foreach($data as $column=>&$value){
			$values.="{$glue}`$column` = :{$column}";
			$glue=',';
		}		
		$query="UPDATE `{$table}` SET {$values} $queryWhere";
		
		$sth=$this->pdo->prepare($query);
		$this->bindValues($data,$sth);
		$this->affectedRows=$sth->execute();
		$this->reset();
		return $this;
		
	}
	
	public function delete($table,$where=''){
		$this->where($where);
		$queryWhere=$this->getQueryWhere();	
		$query="DELETE FROM `{$table}` $queryWhere";
		return $this->query($query);
	}
	
	
}
