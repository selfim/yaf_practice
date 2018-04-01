<?php
/**
 * Created by PhpStorm.
 * User: limeng
 * Date: 2018/3/31
 * Time: 23:12
 */
require_once(__DIR__.'/../../../library/ThirdParty/PHPExcel.php');
class UserController extends Yaf_Controller_Abstract
{
    public function testAction()
    {
        $phpexcelModel = new PHPExcel();

        var_dump($phpexcelModel);
        echo 'This is home module test action';
        return false;//Yaf默认会去加载视图，return false是为了不让它自动加载视图。
    }
}