<?php
/**
 *后台用户权限控制类
 * 
 * @author 王涛
 * @package  admin
 */
namespace Admin\Controller;
use Think\Controller;
class ServiceController extends AdminController {
	public function feedbackfrom(){
		if(IS_AJAX){
			$data['pid']=I('pid');
			$feedback=M('feedback')->find($data['pid']);
			$data['content']=I('content');
			$data['uid']=CPUID;
			$data['puid']=$feedback['uid'];
			$data['cinemaGroupId']=$feedback['cinemaGroupId'];
			$data['time']=time();
			if(D('feedback')->add($data)){
				echo '1';
			}else{
				echo '0';
			}
		}else{
			$data['uid']=I('uid');
			$data['status']=0;
			$feedbacks=D('feedback')->getList($data);
			$this->assign('feedbacks',$feedbacks);
			$this->display();
		}
	}
	
	public function feedbacklist(){
//		$cinemaList=D('cinema')->getCinemaList();
//		$this->assign('cinemaList',$cinemaList);
//		$pageData['uid']=I('uid');
//		$pageData['cinemaCode']=I('cinemaCode');
//		if(!empty($pageData['cinemaCode'])){
//			$map['cinemaCode']=$pageData['cinemaCode'];
//		}
//		if(!empty($pageData['uid'])){
//			$map['uid']=$pageData['uid'];
//		}
//		$map['status']=0;
//		$map['pid']=0;
//		$this->assign('pageData',$pageData);
//		$feedbacks=D('feedback')->getUserFbs($map);
//		$this->assign('feedbacks',$feedbacks);
		$map=array();
		$pageData['mobile']=I('mobile');
		if(!empty($pageData['mobile'])){
			$map['mobile']=$pageData['mobile'];
		}else{
			$map['neqmobile']='';
		}
		
		$map['status']='0';
		$map['sort']='id desc';
		
		$feedbacks=D('feedback')->feedback_getlist($map);
//		dump($feedbacks);
		$this->assign('pageData',$pageData);
		$this->assign('feedbacks',$feedbacks);
		$this->display();
	}
	function del(){
		if(D('feedback')->delete(I('id'))){
			echo '1';
		}else{
			echo '0';
		}
	}
	function update(){
//		$data['uid']=I('uid');
//		$data['status']=0;
//		$feedbacks=D('feedback')->getList($data);
//		foreach ($feedbacks as $val){
//			$arr['id']=$val['id'];
//			$arr['status']=1;
//			D('feedback')->save($arr);
//		}
		$arr['id']=I('id');
		$arr['status']=1;
		D('feedback')->save($arr);
//		echo '1';
	}
}