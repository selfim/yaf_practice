<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2018/4/1
 * Time: 20:03
 */
$dir = dirname(__FILE__);
require_once $dir.'/../vendor/autoload.php';
$host = "http://yafdemo.com";
$curl  = new \Curl\Curl();
$uname = 'testcase_uname'.mt_rand();
$pwd = 'testcae_pwd'.mt_rand();

/**
 * 注册接口验证
 */
$curl->post($host."/user/register",
        array("name"=>$uname,"pwd"=>$pwd)
    );
if($curl->error){
    exit("Error:".$curl->code.":".$curl->msg."\n");
}else{
    $resp =json_decode($curl->response,true);
    #var_dump($resp);exit;
    if($resp['code']!==0){
        exit("Error:注册用户失败,注册接口异常！错误信息：".$resp['msg']."\n");
    }
    echo "注册用户接口测试成功！注册新用户：".$uname."\n";
}

/**
 * 登录接口验证
 */
$curl->post(
    $host."/user/login?submit=1",
    array("name"=>$uname,"pwd"=>$pwd)
);
if($curl->error){
    exit("Error:".$curl->code.":".$curl->msg."\n");
}else{
    $resp =json_decode($curl->response,true);
    if($resp['code']!==0){
        exit("Error:注册用户失败,注册接口异常！错误信息：".$resp['msg']."\n");
    }
    echo "登录用户接口测试成功！登录用户：".$uname."密码：".$pwd."\n";
}
echo "Check Done！";