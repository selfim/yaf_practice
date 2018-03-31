<?php
/**
 * @name SmsModel
 * @desc Sms数据获取类, 可以访问数据库，文件，其它系统等
 * @author leo
 */

class SmsModel {
	public $code =0;
	public $msg = "";
	private $_db = null;
    public function __construct() {
		$this->_db = new PDO("mysql:host=127.0.0.1;dbname=yafdemo;","root","root");
    }

	public function send($uid,$templateId){
		$sql = $this->_db->prepare("select `mobile` from `user` where `id`= ? ");
		$sql->execute(array(intval($uid)));
		$rst = $sql->fetchAll();
		if(!$rst||count($rst)!=1){
			$this->code = -109;
			$this->msg = "用户手机查找失败！";
			return FALSE;
		}
		$userMobile = $rst[0]['mobile'];
		if(!$userMobile || !is_numeric($userMobile)||strlen($userMobile)!=11){
			$this->code = -110;
			$this->msg = "用户手机不合法！手机号为：".(!$userMobile?"":$userMobile);
			return FALSE;
		}
		
		$smsUid ='leo1128';
		$smsPwd ='xxxx';
		$smsobj = new ThirdParty_Sms($smsUid,$smsPwd);
		
		$contentParam = array('code'=>mt_rand(1000,9999));
		//$template = '100006';
		$template = $templateId;
		$result = $smsobj->send($userMobile,$contentParam,$template);
		if($result['stat']=='100'){
		    //增加发送记录
            $query = $this->_db->prepare("insert into `sms_record` (`uid`,`contents`,`template`) VALUES ( ?, ?, ? )");
            $ret = $query->execute( array($uid, json_encode($contentParam), $template) );
            if( !$ret ){
                /**
                 * TODO 应该返回true还是false，有待商榷
                 */
                $this->errno = -112;
                $this->errmsg = '消息发送成功，但发送记录失败。';
                return false;
            }
			return TRUE;
		}else{
			$this->code = -111;
			$this->msg = "发送失败！".$result['stat'].'('.$result['message'].')';
			return FALSE;
		}
	}


}