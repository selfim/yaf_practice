<?php
/**
 * @name IndexController
 * @author leo
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
	//登录api实现
	public function loginAction(){
		//防爬虫
		$submit = $this->getRequest()->getQuery("submit","0");//通过submit入参 默认值为0
		if($submit!="1"){
			echo json_encode(array('code'=>-104,'msg'=>"请通过正确方式提交！"));
			return FALSE;
		}

		//获取参数
		$name = $this->getRequest()->getPost('name',false);
		$pwd = $this->getRequest()->getPost('pwd',false);
		if(!$name || !$pwd){
			echo json_encode(array("code"=>-100,"msg"=>"用户名和密码必填"));
			return false;
		}

		//调用model 登录验证
		$model = new UserModel();
		$info = $model->login(trim($name),trim($pwd));
		if($info){
			//SESSION
			
			session_start();
			$_SESSION['user_token'] = md5("SALT".$_SERVER['REQUEST_TIME'].$info);
			$_SESSION['user_token_time'] = $_SERVER['REQUEST_TIME'];
			$_SESSION['user_id'] = $info;
			
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
		return false;
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
		return false;
	}
}
