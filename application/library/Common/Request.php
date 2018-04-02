<?php

class Common_Request{
    static public function request($key,$default=null,$type=null){
        if($type=='get'){
            $result = isset($_GET[$key])?trim($_GET[$key]):null;
        }elseif ($type=='post'){
            $result = isset($_POST[$key])?trim($_POST[$key]):null;
        }else{
            $result = isset($_REQUEST[$key])?trim($_REQUEST[$key]):null;
        }
        if($default!=null&&$result==null)
        {
            $result = $default;
        }
        return $result;
    }
    static public function getRequest($key,$default=null){
       return self::request($key,$default,'get');
    }
    static public function postRequest($key,$default=null)
    {
        return self::request($key,$default,'post');
    }

    static public function response($errno=0,$errmsg='',$data=null){
        $response = array(
            'errno'=>$errno,
            'errmsg'=>$errmsg
        );
        if($data!=null){
            $response['data'] = $data;
        }
        return json_encode($response);
    }
}