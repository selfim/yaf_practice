<?php
//引入自动加载类
require 'vendor/autoload.php';
define('APPLICATION_PATH', dirname(__FILE__));

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
?>
