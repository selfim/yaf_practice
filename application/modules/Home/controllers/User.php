<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2018/3/31
 * Time: 23:12
 */

class UserController extends Yaf_Controller_Abstract
{
    public function testAction()
    {

        $model = new UserModel();

        $rst = $model->exportUser();
        #var_dump($model);
        #echo 'This is home module test action';

        return false;//Yaf默认会去加载视图，return false是为了不让它自动加载视图。
    }
}