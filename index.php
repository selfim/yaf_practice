<?php
//加上下面这一句话，用于自动加载QueryList
require 'vendor/autoload.php';
define('APPLICATION_PATH', dirname(__FILE__));

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
?>
