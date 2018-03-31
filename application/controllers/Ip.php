<?php
/**
 * @name IpController
 * @author leo
 * @desc IP地址查询功能
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IpController extends Yaf_Controller_Abstract {
    public function indexAction()
    {

    }
    public function getAction()
    {
        //获取参数
        $ip = $this->getRequest()->getQuery("ip","");
        if(!$ip||!filter_var($ip,FILTER_VALIDATE_IP)){
            echo json_encode(array("errno"=>-5001,"errmsg"=>"请输入正确的ip地址"));
            return false;
        }
        //调用model
        $model = new IpModel();
        if($data = $model->get(trim($ip))){
            echo json_encode(array(
               'errno'=>0,
                'errmsg'=>'',
                'data'=>$data,
            ));
        }else{
            echo json_encode(array(
               "errno"=>$model->errno,
                "errmsg"=>$model->errmsg,
            ));
        }
        return false;
    }
}