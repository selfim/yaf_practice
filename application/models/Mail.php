<?php
/**
 * @name MailModel
 * @desc Mail数据获取类, 可以访问数据库，文件，其它系统等
 * @author leo
 */
#require_once(__DIR__.'/../../vendor/autoload.php');
use Nette\Mail\Message;
class MailModel {
	public $code =0;
	public $msg = "";
	private $_db = null;
    public function __construct() {
		$this->_db = new PDO("mysql:host=127.0.0.1;dbname=yafdemo;","root","root");
    }

	public function send($uid,$title,$content){
		$sql = $this->_db->prepare("select `email` from `user` where `id`= ? ");
		$sql->execute(array(intval($uid)));
		$rst = $sql->fetchAll();
		if(!$rst||count($rst)!=1){
			$this->code = -107;
			$this->msg = "用户邮箱查找失败！";
			return FALSE;
		}
		$userEmail = $rst[0]['email'];
		if(!filter_var($userEmail,FILTER_VALIDATE_EMAIL)){
			$this->code = -108;
			$this->msg = "邮箱错误！邮箱地址为：".$userEmail;
			return FALSE;
		}
		$mail = new Message;
		$mail->setFrom('这是一个测试邮件 <276856674@qq.com>')
			->addTo( $userEmail )
			->setSubject( $title )
			->setBody( $content );
		
		$mailer = new Nette\Mail\SmtpMailer([
				'host' => 'smtp.qq.com',
				'username' => '276856674@qq.com',
				'password' => 'bphststxxgnccacj', /* smtp独立密码 */
				'secure' => 'ssl',
		]);
		$rep = $mailer->send($mail);
		return false;
	}


}