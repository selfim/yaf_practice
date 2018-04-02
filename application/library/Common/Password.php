<?php
class Common_Password{
    const SALT ='sUA78$^P0';
    static public function pwdEncode($pwd){
        return md5(self::SALT.$pwd);
    }
}