<?php
/**
 * @name QuerylistModel
 * @desc Querylist数据获取类, 可以访问数据库，文件，其它系统等
 * @author leo
 */
#require_once(__DIR__.'/../../vendor/autoload.php');
use QL\QueryList;
class QuerylistModel
{
	
    public function test()
    {
        //采集某页面所有的图片
        //$data = QueryList::Query('http://cms.querylist.cc/bizhi/453.html',['image' => ['img','src']])->data;
        //打印结果
        //print_r($data);
        #return $data;
    }
    public function yzm()
    {
        // 请求地址
        $url="http://www.yzmedu.com";

        // 请求规则
        $reg=array(

            // 获取网站title
            "title"=>array("title",'text'),

        );
        // 爬取数据
        $data=QueryList::Query($url,$reg);

        // 输出结果

        echo "<pre>";
        print_r($data);
    }
}