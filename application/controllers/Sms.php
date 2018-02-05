<?php
/**
 * @name SmsController
 * @author leo
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class SmsController extends Yaf_Controller_Abstract {

	public function sendAction(){
		//防爬虫
		$submit = $this->getRequest()->getQuery("submit","0");//通过submit入参 默认值为0
		if($submit!="1"){
			echo json_encode(array('code'=>-104,'msg'=>"请通过正确方式提交！"));
			return FALSE;
		}

		//获取参数
		$uid = $this->getRequest()->getPost('uid',false);
		//$contents = $this->getRequest()->getPost('contents',false);
		$templateId = $this->getRequest()->getPost('templateId',false);
		if(!$uid||!$templateId){
			echo json_encode(array("code"=>-100,"msg"=>"用户ID、短信模板必填"));
			return false;
		}

		//调用model
		$model = new SmsModel();
		$info = $model->send(intval($uid),intval($templateId));
		if($info){
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
		return false;
	}

}
