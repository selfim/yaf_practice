<?php
/**
 * @name QrcodeModel
 * @desc Qrcode数据获取类, 可以访问数据库，文件，其它系统等
 * @author leo
 */
#require_once(__DIR__.'/../../vendor/autoload.php');
use \PHPQRCode\QRcode;
class QrcodeModel
{
    public function test()
    {
       $tmp = \PHPQRCode\QRcode::png("Test", "./qrcode.png", 'L', 4, 2);
    }
}