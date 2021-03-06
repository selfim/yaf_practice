<?php
/**
 * @name NewsController
 * @author leo
 * @desc 文章控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class NewsController extends Yaf_Controller_Abstract {
	#引入文章列表
	public function indexAction(){
		return $this->listAction();
	}
	#新增文章
	public function addAction( $newsId=0 ){
		if(!$this->_isAdmin()){//权限校验
			echo json_encode(array("errno"=>-2000,"errmsg"=>"非管理员不能操作！"));
			return FALSE;
		}
		//提交方式检测
		$submit = $this->getRequest()->getQuery("submit","0");//通过submit入参 默认值为0
		if($submit!="1"){
			echo json_encode(array('code'=>-2001,'msg'=>"请通过正确方式提交！"));
			return FALSE;
		}
		//获取参数
		$title = $this->getRequest()->getPost('title',false);
		$contents = $this->getRequest()->getPost('contents',false);
		$author = $this->getRequest()->getPost('author',false);
		$cate = $this->getRequest()->getPost('cate',false);
		if(!$title || !$contents||!$author||!$cate){
			echo json_encode(array("code"=>-2002,"msg"=>"标题、内容、作者、分类信息必填"));
			return FALSE;
		}
		//调用模型
		$model = new NewsModel();
		if($lastId = $model->add(trim($title),trim($contents),trim($author),trim($cate),$newsId)){
			echo json_encode(array(
				"errno"=>0,
			    "errmsg"=>"",
				"data"=>array("lastId"=>$lastId)
			));
		}else{
			echo json_encode(array(
				"errno"=>$model->errno,
			    "errmsg"=>$model->errmsg,
			));
		}
		
		return FALSE;
	}
	#编辑文章 复用model里的add方法 id存在 编辑 id不存在 新增
	public function editAction(){
		if(!$this->_isAdmin()){//权限校验
			echo json_encode(array("errno"=>-2000,"errmsg"=>"非管理员不能操作！"));
			return FALSE;
		}
		//获取参数
		$newsId = $this->getRequest()->getQuery('newsId',false);
		if(is_numeric($newsId)&&$newsId){
			return $this->addAction($newsId);
		}else{
			echo json_encode(array("code"=>-2003,"msg"=>"参数错误"));
			return FALSE;
		}
		return FALSE;
	}
	#删除文章
	public function delAction(){
		if(!$this->_isAdmin()){//权限校验
			echo json_encode(array("errno"=>-2000,"errmsg"=>"非管理员不能操作！"));
			return FALSE;
		}
		//传入参数
		$newsId = $this->getRequest()->getQuery('newsId',false);
		if(is_numeric($newsId)&&$newsId){
			$model = new NewsModel();
			if($model->del($newsId)){
				echo json_encode(array(
					"errno"=>0,
					"errmsg"=>"",
				));
			}else{
				echo json_encode(array(
					"errno"=>$model->errno,
					"errmsg"=>$model->errmsg,
				));
			}
		
		}else{
			echo json_encode(array("code"=>-2003,"msg"=>"参数错误"));
		
		}

		return FALSE;
	}
	#发布文章
	public function statusAction(){
		//权限校验
		if(!$this->_isAdmin()){
			echo json_encode(array("errno"=>-2000,"errmsg"=>"非管理员不能操作！"));
			return FALSE;
		}
		//传入参数
		$newsId = $this->getRequest()->getQuery('newsId','0');#url get方式获取
		$status = $this->getRequest()->getQuery('status','offline');
		if(is_numeric($newsId)&&$newsId){
			$model = new NewsModel();
			if($model->status($newsId,$status)){
				echo json_encode(array(
					"errno"=>0,
					"errmsg"=>"",
				));
			}else{
				echo json_encode(array(
					"errno"=>$model->errno,
					"errmsg"=>$model->errmsg,
				));
			}
		
		}else{
			echo json_encode(array("code"=>-2003,"msg"=>"参数错误"));
		
		}
		return FALSE;
	}
	//详情
	public function getAction(){
        #传入参数
		$newsId = $this->getRequest()->getQuery('newsId','0');#url get方式获取

		if(is_numeric($newsId)&&$newsId){
            $model = new NewsModel();
            if($data = $model->get($newsId)){
                echo json_encode(array(
                    "errno"=>0,
                    "errmsg"=>"",
                    "data"=>$data,
                ));
            }else{
                echo json_encode(array(
                    "errno"=>$model->errno,
                    "errmsg"=>$model->errmsg,
                ));
            }

        }else{
            echo json_encode(array("code"=>-2003,"msg"=>"参数错误"));

        }
		return FALSE;
	}
	//分页 列表
	public function listAction(){
	    //参数
        $pageNo = $this->getRequest()->getQuery( "pageNo", "0" );
        $pageSize = $this->getRequest()->getQuery( "pageSize", "10" );
        $cate = $this->getRequest()->getQuery( "cate", "0" );
        $status = $this->getRequest()->getQuery( "status", "online" );

        $model = new NewsModel();
        if($data = $model->listfunc($pageNo,$pageSize,$cate,$status)){
            echo json_encode( array(
                "errno"=>0,
                "errmsg"=>"",
                "data"=>$data,
            ));

        }else{
            echo json_encode(array(
                "errno"=>$model->errno,
                "errmsg"=>$model->errmsg,
            ));

        }
		return FALSE;
	}
	//检测是否是管理员
	private function _isAdmin()
	{
		return true;
	}
	//PHPExceltest
	public function testAction()
	{
		
	}
}
