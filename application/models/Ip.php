<?php
/**
 * @name IpModel
 * @desc Ip地址查询功能
 * @author leo
 */
class IpModel{
    public $errno =0;
    public $errmsg ="";
    public function get($ip){
        $res = ThirdParty_IP::find($ip);
        return $res;
    }
}