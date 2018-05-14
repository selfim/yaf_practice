<?php
/**
 * @name UserleModel
 * @desc User数据获取类, 可以访问数据库，文件，其它系统等
 * @author leo
 */
require_once(__DIR__.'/../library/ThirdParty/PHPExcel.php');
class UserModel {
	public $errno =0;
	public $errmsg = "";
	//private $_db = null;
	private $_dao = null;
    public function __construct() {
		//$this->_db = new PDO("mysql:host=127.0.0.1;dbname=yafdemo;","root","root");
        $this->_dao = new Db_User();
    }
	public function login($name,$pwd){

		$userInfo = $this->_dao->find($name);
		if(!$userInfo){
		    $this->errno = $this->_dao->errno();
		    $this->errmsg = $this->_dao->errmsg();
		    return false;
        }
		if(Common_Password::pwdEncode($pwd)!=$userInfo['pwd']){
			$this->errno = -105;
			$this->errmsg = "密码错误！";
			return FALSE;
		}
		return intval($userInfo[1]);
	}
	public function register($name,$pwd){
        if(!$this->_dao->checkExists($name)){
            $this->errno = $this->_dao->errno();
            $this->errmsg = $this->_dao->errmsg();
            return false;
        }
		if(strlen($pwd)<8){
			$this->errno = -102;
			$this->errmsg = "密码至少八位！";
			return FALSE;
		}else{
			//$password = $this->_genPass($pwd);
			$password = Common_Password::pwdEncode($pwd);
		}
        if(!$this->_dao->addUser($name,$password,date("Y-m-d H:i:s"))){
            $this->errno = $this->_dao->errno();
            $this->errmsg = $this->_dao->errmsg();
            return false;
        }
        return true;

	}

    /**
     * @param $password
     * @return string
     */
	//private function _genPass($password){
	//	return md5("sUA78$^P0".$password);
	//}

	//PHPExcel导出用户
	public function exportUser()
    {
        $phpexcelModel = new PHPExcel();
        $sql = $this->_db->prepare("select * from `user`  ");
        $sql->execute();
        $rst = $sql->fetchAll();
        #var_dump($rst);
        $objSheet = $phpexcelModel->getActiveSheet();
        $objSheet->setTitle('demo');
        /**
        $objSheet->setCellValue("A1","分数")->setCellValue("B1","年级")->setCellValue("C1","姓名");
        $objSheet->setCellValue("A2",90)->setCellValue("B2","六年级")->setCellValue("C2","张三");
        $objSheet->setCellValue("A3",70)->setCellValue("B3","二年级")->setCellValue("C3","关羽");
        $objSheet->setCellValue("A4",95)->setCellValue("B4","四年级")->setCellValue("C4","刘备");
        $objSheet->setCellValue("A5",80)->setCellValue("B5","六年级")->setCellValue("C5","曹操");
        */
        $i=1;
        foreach ($rst as $k=>$v){
            $objSheet->setCellValue("A".$i,$v['id'])->setCellValue("B".$i,$v['name'])->setCellValue("C".$i,$v['email'])
                ->setCellValue("D".$i,$v['mobile'])->setCellValue("E".$i,$v['reg_time'])->setCellValue("F".$i,$v['update_time']);
            $i++;
        }
        $objWriter=PHPExcel_IOFactory::createWriter($phpexcelModel,'Excel5');//生成excel文件
        #var_dump($rst);
        $objWriter->save(dirname(__FILE__)."/export_2.xls");//保存文件
        //$csv = $header . $content;
    }
}
