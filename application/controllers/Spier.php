<?php
use QL\QueryList;
require_once '../../vendor/workerman/workerman/Autoloader.php';
use Workerman\Worker;
class SpierController extends Yaf_Controller_Abstract {
    public function indexAction(){
        // 爬取的地址

        $url="https://www.pintu360.com/";

        // 爬取的规则

        $reg=array(
            "title"=>array(".article-list .article-text .article-title a","html"),
            "img"=>array(".article-list .article-photo img","src"),
            "info"=>array(".article-list .article-text .article-content","html"),
            "url"=>array(".article-list .article-photo a",'href'),
        );
        #$data=QueryList::Query($url,$reg)->data;

        // 输出结果

       # echo "<pre>";
        #print_r($data);
        // 进行数据获取
        $data=QueryList::Query($url,$reg)->getData(function($item){

            // 获取的地址

            $newUrl="https://www.pintu360.com/".$item['url'];

            // 新闻内容的获取规则

            $newReg=array(

                "text"=>array(".mixin-article-v3",'html'),
            );
            // 发送请求

            $newData=QueryList::Query($newUrl,$newReg)->data;

            // 可以入库操作

            $pdo=new PDO('mysql:dbname=spider;host=127.0.0.1',"root",'root');

            // 准备sql语句

            $html=htmlspecialchars($newData[0]['text']);
            $date = date('Y-m-d H:i:s',time());
            $sql="insert into spider_news value(null,'$item[title]','$item[info]','$html','$item[img]','$date')";

            $pdo->exec($sql);
        });
        return false;
    }
    public function taskAction(){
        // #### http worker ####
        $http_worker = new Worker("http://0.0.0.0:2345");

        // 4 processes
        $http_worker->count = 4;

        // Emitted when data received
        $http_worker->onMessage = function($connection, $data)
        {
            // $_GET, $_POST, $_COOKIE, $_SESSION, $_SERVER, $_FILES are available
            var_dump($_GET, $_POST, $_COOKIE, $_SESSION, $_SERVER, $_FILES);
            // send data to client
            $connection->send("hello world \n");
        };

        // run all workers
        Worker::runAll();
    }
}

