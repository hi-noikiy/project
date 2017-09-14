<?php
namespace Web\Controller;
use Think\Controller;
class activityController extends InitController {
    public function activitylist(){
    	
    	$model=D('Banner');
    	$data=array();
    	$data['type']='2';
    	$data['act']='0';
//    	$list=$model->banner_getlist($data);
    	
    	$limit=$this->limit;
//		$limit=3;
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
        $showlist=array();
        $count=$model->banner_getlist($data,3);
    	$allPage = ceil ( $count / $limit);
        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $limit;
//        if ($count > $limit) {
            $showPage = $this->getPageList ( $count, $limit, $data );
//        }
        $this->assign('page', $showPage);
        $data['firstRow']=$startLimit;
        $data['listRows']=$limit;
	 	$list=$model->banner_getlist($data,2);	
    	$data=array();
    	$data['type']='2';
    	$data['act']='1';
    	$list1=$model->banner_getlist($data);
//    	dump($list1);
    	
//    	dump();
    	$this->assign('list', $list);
    	$this->assign('list1', $list1);
    	
		$this->assign('pageId', 'page-activitylist');
       $this->display('activitylist');
    }
	 public function oldactivitylist(){
    	
    	$model=D('Banner');
    	$data=array();
    	$data['type']='2';
    	$data['act']='1';
//    	$list=$model->banner_getlist($data);
    	
    	$limit=$this->limit;
//		$limit=3;
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
        $showlist=array();
        $count=$model->banner_getlist($data,3);
    	$allPage = ceil ( $count / $limit);
        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $limit;
//        if ($count > $limit) {
            $showPage = $this->getPageList ( $count, $limit, $data );
//        }
        $this->assign('page', $showPage);
        $data['firstRow']=$startLimit;
        $data['listRows']=$limit;
	 	$list=$model->banner_getlist($data,2);	
    	$this->assign('list', $list);
		$this->assign('pageId', 'page-activitylist');
       $this->display('oldactivitylist');
    }
    
	 /**
	  *活动详细内容展示
	  */
	 public function activity(){
	 	$model=D('Banner');
	 	$data=array();
	 	$data['type']='2';
	 	$data['act']='0';
	 	$data['neqid']=I('id');
    	$list=$model->banner_getlist($data);
//    	dump($list);
    	$this->assign('list', $list);
	 	$data=array();
	 	$data['id']=I('id');
    	$list1=$model->banner_getlist($data);

    	$this->assign($list1[0]);	 	
		$this->assign('pageId', 'page-activitylist');
       $this->display('activity');
    }
 	/**
	  *活动详细内容展示
	  */
	 public function oldactivity(){
	 	$model=D('Banner');
	 	$data=array();
	 	$data['type']='2';
	 	$data['act']='1';
	 	$data['neqid']=I('id');
    	$list=$model->banner_getlist($data);
//    	dump($list);
    	$this->assign('list', $list);
	 	$data=array();
	 	$data['id']=I('id');
    	$list1=$model->banner_getlist($data);

    	$this->assign($list1[0]);	 	
		$this->assign('pageId', 'page-activitylist');
       $this->display('oldactivity');
    }

}