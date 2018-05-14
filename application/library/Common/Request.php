<?php

class Common_Request{
    /**
     * 通用请求处理方法
     * @param $key
     * @param null $default
     * @param null $type
     * @return null|string
     */
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

    /**
     * get方式请求
     * @param $key
     * @param null $default
     * @return null|string
     */
    static public function getRequest($key,$default=null){
       return self::request($key,$default,'get');
    }

    /**
     * post方式请求
     * @param $key
     * @param null $default
     * @return null|string
     */
    static public function postRequest($key,$default=null)
    {
        return self::request($key,$default,'post');
    }

    /**
     * 统一响应方法
     * @param int $errno
     * @param string $errmsg
     * @param null $data
     * @return mixed|string
     */
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