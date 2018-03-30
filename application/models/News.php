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
     * 新增
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

    /**
     * 删除操作
     * @param $newsId
     * @return bool
     */
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

    /**
     * 更新操作
     * @param $artId
     * @param string $status
     * @return bool
     */
	public function status( $artId, $status="offline" ){
		$query = $this->_db->prepare("update `art` set `status`=? where `id`=? ");
		$ret = $query->execute( array( $status, intval($artId)) );
		if( !$ret ) {
			$this->errno = -2008;
			$this->errmsg = "更新文章状态失败, ErrInfo:".end($query->errorInfo());
			return false;
		}
		return true;
	}

    /**
     * 获取详情
     * @param $newsId
     * @return array|bool
     */
    public function get($newsId){
	    $sql = $this->_db->prepare("SELECT `title`,`contents`,`author`,`cate`,`ctime`,`mtime`,`status` FROM `art` WHERE `id` = ?");
	    $status = $sql->execute(array(intval($newsId)));
	    $ret = $sql->fetchAll();
	    if(!$status||!$ret){
	        $this->errno = -2009;
	        $this->errmsg = "查询失败, ErrInfo:".end($sql->errorInfo());
	        return false;

        }
        $newsInfo = $ret[0];
	    //获取分类
        $query = $this->_db->prepare("SELECT `name` FROM `cate` WHERE `id`=?");
        $query->execute(array($newsInfo['cate']));
        $ret = $query->fetchAll();
        if(!$ret){
            $this->errno = -2010;
            $this->errmsg ="获取分类信息失败, ErrInfo:".end($query->errorInfo());
            return false;
        }
        $newsInfo['cateName'] = $ret[0]['name'];
        $data = array(
            'id'=>intval($newsId),
            'title'=>$newsInfo['title'],
            'contents'=>$newsInfo['contents'],
            'author' =>$newsInfo['author'],
            'cateName'=>$newsInfo['cateName'],
            'cateId'=>intval($newsInfo['cate']),
            'ctime'=>$newsInfo['ctime'],
            'mtime'=>$newsInfo['mtime'],
            'status'=>$newsInfo['status'],
        );
        return $data;

    }

    /**
     * 列表分页功能
     * @param int $pageNo
     * @param int $pageSize
     * @param int $cate
     * @param string $status
     * @return array|bool
     */
    public function listfunc($pageNo=0, $pageSize=10, $cate=0, $status='online'){
        $start = $pageNo * $pageSize + ($pageNo==0?0:1);
        if( $cate == 0 ) {
            $filter = array( $status, intval($start), intval($pageSize) );
            $query = $this->_db->prepare("SELECT `id`, `title`,`contents`,`author`,`cate`,`ctime`,`mtime`,`status` FROM `art` WHERE `status`=? order by `ctime` desc limit ?,?  ");
        } else {
            $filter = array( intval($cate), $status, intval($start), intval($pageSize) );
            $query = $this->_db->prepare("SELECT `id`, `title`,`contents`,`author`,`cate`,`ctime`,`mtime`,`status` FROM `art` WHERE `cate`=? and `status`=? order by `ctime` desc limit ?,?  ");
        }
        $stat = $query->execute( $filter );
        $ret = $query->fetchAll();
        if( !$ret ) {
            $this->errno = -2011;
            $info =$query->errorInfo();
            $this->errmsg = "获取文章列表失败, ErrInfo:".end($info);
            return false;
        }

        $data = array();
        $cateInfo = array();

        foreach( $ret as $item ) {
            //分类信息获取
            if( isset($cateInfo[$item['cate']]) ){
                $cateName = $cateInfo[$item['cate']];
            } else {
                $query = $this->_db->prepare("SELECT `name` FROM `cate` WHERE `id`=?");
                $query->execute( array( $item['cate']) );
                $retCate = $query->fetchAll();
                if( !$retCate ) {
                    $this->errno = -2010;
                    $info = $query->errorInfo();
                    $this->errmsg = "获取分类信息失败, ErrInfo:".end($info);
                    return false;
                }
                $cateName = $cateInfo[$item['cate']] = $retCate[0]['name'];
            }

            //切割文本
            $contents = mb_strlen($item['contents'])>30 ? mb_substr($item['contents'], 0, 30)."..." : $item['contents'];

            $data[] = array(
                'id' => intval($item['id']),
                'title'=> $item['title'],
                'contents'=> $contents,
                'author'=> $item['author'],
                'cateName'=> $cateName,
                'cateId'=> intval($item['cate']),
                'ctime'=> $item['ctime'],
                'mtime'=> $item['mtime'],
                'status'=> $item['status'],
            );
        }
        return $data;
    }
	
}
