<?php
/**
 * @name MailController
 * @author leo
 * @desc sendemail控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class MailController extends Yaf_Controller_Abstract {

	public function indexAction(){
		
	}

	public function sendAction(){
		//防爬虫
		$submit = $this->getRequest()->getQuery("submit","0");//通过submit入参 默认值为0
		if($submit!="1"){
			echo json_encode(array('code'=>-104,'msg'=>"请通过正确方式提交！"));
			return FALSE;
		}

		//获取参数
		$uid = $this->getRequest()->getPost('uid',false);//发给谁
		$title = $this->getRequest()->getPost('title',false);//标题
		$content = $this->getRequest()->getPost('content',false);//内容
		if(!$uid || !$title||!$content){
			echo json_encode(array("code"=>-106,"msg"=>"用户名和标题、内容必填"));
			return false;
		}

		//调用model 登录验证
		$model = new MailModel();
		$res = $model->send(trim($uid),trim($title),trim($content));
		if($res){
				echo json_encode(array(
				"code"=>0,
				"msg"=>"",
			));
		}else{
				echo json_encode(array(
				"code"=>$model->code,
				"msg"=>$model->msg,
			));
		}
		return TRUE;
	}

}