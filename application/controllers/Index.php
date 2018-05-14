<?php
/**
 * @name IndexController
 * @author limeng\limeng
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Yaf_Controller_Abstract {
    public  function  init(){

    }

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/yaf_app/index/index/index/name/limeng\limeng 的时候, 你就会发现不同
     */
	public function indexAction($name = "Stranger") {
		//1. fetch query
		$get = $this->getRequest()->getQuery("get", "default value");

		//2. fetch model
		$model = new SampleModel();

		//3. assign
		$this->getView()->assign("content", $model->selectSample());
		$this->getView()->assign("name", $name);

        $this->forward('Index','Index','show');
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return true;
	}

	public function testAction()
	{
		$submit = $this->getRequest()->getQuery("submit","0");//通过submit入参 默认值为0
        if($submit!="1"){
            echo json_encode(array('code'=>-104,'msg'=>"请通过正确方式提交！"));
            return FALSE;
        }
        $content = $this->getRequest()->getPost('test',false);//内容
        if(!$content){
            echo json_encode(array("code"=>-106,"msg"=>"测试内容必填"));
            return false;
        }
          //调用model 登录验证
        $model = new QuerylistModel();
        $res = $model->yzm();
        #var_dump($res);
		p($res);
        return false;
	}

	public function showAction(){
	    echo 'show';
        return false;
    }

    public function getconfigAction(){
	    $config =Yaf_Application::app()->getConfig();
	    print_r($config);return false;
    }

    public function pageAction(){
        #phpinfo();exit;
        $page = Yaf_Loader::import('Tools/Page.php');
		$config =Yaf_Application::app()->getConfig();
	   
		p($config);
        return false;
    }
    public function getparAction(){
        $params = $this->getRequest()->getParams();
        var_dump($params);

    }
}
