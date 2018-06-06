<?php
/**
 * Created by PhpStorm.
 * User: limeng
 * Date: 2018/6/6
 * Time: 23:15
 */
use PFinal\Excel\Excel;
class ExportModel{
    public $errno =0;
    public $errmsg ="";
    private $_db = null;
    public function __construct() {
        $this->_db = new PDO("mysql:host=127.0.0.1;dbname=datatest;","root","root");
    }
    public function selectdata($limit=100000){
        $sql = $this->_db->prepare("select `*` from `card` limit $limit ");
        $sql->execute();
        $rst = $sql->fetchAll(PDO::FETCH_ASSOC);
        $map = array(
            'title'=>[
                'card_id' => '编号',
                'card_number' => '卡号',
            ],
        );
        $file = 'user' . date('Y-m-d');

        Excel::exportExcel($rst, $map, $file, '用户信息');
    }
}