<?php
/**
 * @name UserleModel
 * @desc User数据获取类, 可以访问数据库，文件，其它系统等
 * @author leo
 */
require_once(__DIR__.'/../library/ThirdParty/PHPExcel.php');
class UserModel {
	public $code =0;
	public $msg = "";
	private $_db = null;
    public function __construct() {
		$this->_db = new PDO("mysql:host=127.0.0.1;dbname=yafdemo;","root","root");
    }
	public function login($name,$pwd){
		$sql = $this->_db->prepare("select `pwd`,`id` from `user` where `name`= ? ");
		$sql->execute(array($name));
		$rst = $sql->fetchAll();
		if(!$rst||count($rst)!=1){
			$this->code = -104;
			$this->msg = "用户查找失败！";
			return FALSE;
		}
		$userInfo = $rst[0];
		if($this->_genPass($pwd)!=$userInfo['pwd']){
			$this->code = -105;
			$this->msg = "密码错误！";
			return FALSE;
		}
		return intval($userInfo[1]);
	}
	public function register($name,$pwd){
		$sql = $this->_db->prepare("select count(*) as count from `user` where `name`= ? ");
		$sql->execute(array($name));
		$count = $sql->fetchAll();

		if($count[0]['count']!=0){
			$this->code = -101;
			$this->msg = "用户名已存在！";
			return FALSE;
		}
		if(strlen($pwd)<8){
			$this->code = -102;
			$this->msg = "密码至少八位！";
			return FALSE;
		}else{
			$password = $this->_genPass($pwd); 
		}

		$sql = $this->_db->prepare("insert into `user`(`id`,`name`,`pwd`,`reg_time`) VALUES(NULL,?,?,?)");
		$rst = $sql->execute(array($name,$password,date("Y-m-d H:i:s")));
		if(!$rst){
			$this->code = -103;
			$this->msg = "注册失败";
			return FALSE;
		}
		return TRUE;
	}
	private function _genPass($password){
		return md5("sUA78$^P0".$password);
	}
	//PHPExcel导出用户
	public function exportUser()
    {
        $phpexcelModel = new PHPExcel();

        var_dump($phpexcelModel);
    }
}
