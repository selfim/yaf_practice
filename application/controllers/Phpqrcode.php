<?php
/**
 * @name PhpqrcodeController
 * @author leo
 * @desc qrcode控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class PhpqrcodeController extends Yaf_Controller_Abstract {
    public function indexAction(){

    }
    public function testAction(){
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
        $model = new QrcodeModel();
        $res = $model->test();
        var_dump($res);
        return false;
    }
}