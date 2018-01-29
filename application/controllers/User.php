<?php
/**
 * @name IndexController
 * @author limeng\limeng
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class UserController extends Yaf_Controller_Abstract {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/yaf_app/index/index/index/name/limeng\limeng 的时候, 你就会发现不同
     */
	public function RegAction($name = "User Reg") {
		//1. fetch query
		$get = $this->getRequest()->getQuery("get", "default value");

		//2. fetch model
		$model = new SampleModel();
		var_dump($name);
		/**
		//3. assign
		$this->getView()->assign("content", $model->selectSample());
		$this->getView()->assign("name", $name);
		*/
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        //return TRUE;
		
		return false;
	}

	public function indexAction(){
		return $this->loginAction();
	}

	public function loginAction(){
		
	}

	public function registerAction(){
		//获取参数
		$name = $this->getRequest()->getPost('name',false);
		$pwd = $this->getRequest()->getPost('pwd',false);
		if(!$name || !$pwd){
			echo json_encode(array("code"=>-100,"msg"=>"用户名和密码必填"));
			return false;
		}

		//调用model 登录验证
		$model = new UserModel();
		$info = $model->register(trim($name),trim($pwd));
		if($info){
			echo json_encode(array(
				"code"=>0,
				"msg"=>"",
				"data"=>array(
				  "name"=>$name
				)
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
