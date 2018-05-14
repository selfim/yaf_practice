<?php

/**
 * dao层基类
 * Class Db_Base
 */
class Db_Base{

    public static $db = null;
    public static $errno = 0;
    public static $ermsg = '';

    /**
     * 单例模式
     * return null|PDO
     */
    public static function getDb(){
        if(self::$db==null){
            self::$db = new PDO("mysql:host=127.0.0.1;dbname=yafdemo;","root","root");
        }
        return self::$db;
    }

    /**
     * 错误码
     * @return int
     */
    public static function errno()
    {
        return self::$errno;
    }

    /**
     * 错误信息
     * @return string
     */
    public static function errmsg()
    {
        return self::$ermsg;
    }

}