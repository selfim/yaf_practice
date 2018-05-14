<?php

/**
 * 用户信息dao层
 * Class Db_User
 */
class Db_User extends Db_Base {
    /**
     * 查找用户
     * @param $uname
     * @return bool
     */
    public function find($uname){
        $query = self::getDb()->prepare("select `pwd`,`id` from `user` where `name`= ? ");
        $query->execute(array($uname));
        $rst = $query->fetchAll();
        if(!$rst||count($rst)!=1){
            self::$errno = -104;
            self::$errmsg = "用户查找失败！";
            return FALSE;
        }
       return $rst[0];
    }

    /**
     * 检测用户是否存在
     * @param $uname
     * @return bool
     */
    public function checkExists($uname){
        $sql = self::getDb()->prepare("select count(*) as count from `user` where `name`= ? ");
        $sql->execute(array($uname));
        $count = $sql->fetchAll();
        if($count[0]['count']!=0){
            self::$errno = -101;
            self::$errmsg = "用户名已存在！";
            return FALSE;
        }
        return true;
    }

    /**
     * 新增用户
     * @param $uname
     * @param $password
     * @param $datetime
     * @return bool
     */
    public function addUser($uname,$password,$datetime){
        $sql = self::getDb()->prepare("insert into `user`(`id`,`name`,`pwd`,`reg_time`) VALUES(NULL,?,?,?)");
        $rst = $sql->execute(array($uname,$password,date("Y-m-d H:i:s")));
        if(!$rst){
            self::$errno = -103;
            self::$ermsg= "注册失败";
            return FALSE;
        }
        return TRUE;
    }
}