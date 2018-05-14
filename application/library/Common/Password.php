<?php

class Common_Password{
    const SALT ='sUA78$^P0';

    /**
     * 密码加密
     * @param $pwd
     * @return string
     */
    static public function pwdEncode($pwd){
        return md5(self::SALT.$pwd);
    }
}