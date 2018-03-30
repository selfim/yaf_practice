<?php
/**
 * @name NewsModel
 * @desc News数据获取类, 可以访问数据库，文件，其它系统等
 * @author leo
 */
class NewsModel {
	
	public $errno =0;
	public $errmsg = "";
	private $_db = null;
    public function __construct() {
		$this->_db = new PDO("mysql:host=127.0.0.1;dbname=yafdemo;","root","root");
		#防止PDO在拼SQL时候，把int 0转成string 0
		$this->_db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
		
    }

    /**
     * @param $title
     * @param $contents
     * @param $author
     * @param $cate
     * @param int $newsId
     * @return int
     */
	public function add($title,$contents,$author,$cate,$newsId = 0){
	    $isEdit = false;
	    if($newsId!=0&&is_numeric($newsId)){
	       $sql = $this->_db->prepare("SELECT COUNT(*) FROM `art` WHERE `id`=?");
	       $sql ->execute(array($newsId));
	       $ret = $sql->fetchAll();
	       if(!$ret||count($ret)!=1){
	           $this->errno = -2004;
	           $this->errmsg ="未找到该文章，无法编辑！";
	           return false;
           }
           $isEdit = true;
        }else{
	        //add 并且检查cate存在否
            $sql = $this->_db->prepare("SELECT COUNT(*) FROM `cate` WHERE `id`=?");
            $sql->execute(array($cate));
            $ret = $sql->fetchAll();
            if(!$ret||$ret[0][0]==0){
                $this->errno = -2005;
                $this->errmsg = "找不到对应ID的分类信息，cate id:".$cate.", 请先创建该分类!";
                return false;
            }
        }
        //插入或者更新文章
        $data = array($title,$contents,$author,intval($cate));
	    if(!$isEdit){
	        $sql = $this->_db->prepare("INSERT INTO `art`(`title`,`contents`,`author`,`cate`) VALUES ( ?, ?, ?, ? )");
        }else{
	        $sql = $this->_db->prepare("UPDATE `art` SET `title`=?, `contents`=?, `author`=?, `cate`=? WHERE `id`= ?");
	        $data[] = $newsId;
        }
        $ret = $sql->execute($data);
	    if(!$ret){
            $this->errno = -2006;
            $this->errmsg = "操作文章数据表失败, ErrInfo:".end($sql->errorInfo());
            return false;
        }
        //返回文章id
        if(!$isEdit){
	        return intval($this->_db->lastInsertId());
        }else{
            return intval($newsId);
        }

	}
	
	#删除操作
	public function del($newsId){
		$sql = $this->_db->prepare("DELETE FROM `art` WHERE `id` =?");
		$ret = $sql->execute(array(intval($newsId)));
		if(!$ret){
			$this->errno = -2007;
			$this->errmsg = "删除失败！ErrorInfo:".end($sql->errorInfo());
			return false;
		}
		return true;
	}
	
	#
	public function status($newsId,$status='offline')
	{
		$sql = $this->_db->prepare("UPDATE `art` SET `status`=？ WHERE `id` =?");
		$ret = $sql->execute(array($status,intval($newsId)));
		if(!$ret){
			$this->errno = -2008;
			$this->errmsg = "更新文章状态失败！ErrorInfo:".end($sql->errorInfo());
			return false;
		}
		return true;
		
	}
	
}
