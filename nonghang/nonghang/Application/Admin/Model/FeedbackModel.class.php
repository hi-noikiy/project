<?php

namespace Admin\Model;
use Think\Model;

class FeedbackModel extends Model {
	function getList($map=array(),$order='time desc',$start=0,$limit=999999999){
		 $feedbacks= M('Feedback')->where($map)->order($order)->select();
		 foreach ($feedbacks as $k=>$v){
		 	if(!empty($v['img'])){
		 		$feedbacks[$k]['img']=C('IMG_URL').'Uploads/'.$v['img'];
		 	}
		 	$nf=M('Feedback')->where('pid='.$v['id'])->order($order)->select();
		 	if(!empty($nf)){
		 		$feedbacks[$k]['answer']=$nf;
		 	}
		 }
		 return $feedbacks;
	}

	function getUserFbs($map){
		$fbs=M('Feedback')->field('uid,count(id) as c')->where($map)->group('uid')->select();
		foreach ($fbs as $k=>$v){
			$member=M('member')->find($v['uid']);
			$map['uid']=$v['uid'];
			$fb=M('feedback')->where($map)->order('time desc')->find();
			$fbs[$k]['cinemaName']=$member['businessName'];
			$fbs[$k]['time']=$fb['time'];
			$fbs[$k]['content']=$fb['content'];
			if(empty($fb['content'])){
				$fbs[$k]['img']=C('IMG_URL').'Uploads/'.$fb['img'];
			}
		}
		return $fbs;
	}
	
	
	function feedback_getlist($dataarray=array(),$flag="1"){
	  	$wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
  		if(isset($dataarray['mobile'])) {    
        	$wherearray['mobile']=array('eq',$dataarray['mobile']);            
        }
		if(isset($dataarray['neqmobile'])) {    
        	$wherearray['mobile']=array('neq',$dataarray['neqmobile']);            
        }
		if(isset($dataarray['status'])) {    
        	$wherearray['status']=array('eq',$dataarray['status']);            
        }
       	$database = M('Feedback');
     	$sort='';
    	if(isset($dataarray['sort'])) {  
    		$sort=$dataarray['sort'];  	         
        }       
     	$firstRow='0';
    	if(isset($dataarray['firstRow'])) {  
    		$firstRow=$dataarray['firstRow'];  	         
        }
        $listRows=20;
    	if(isset($dataarray['listRows'])) {  
    		$listRows=$dataarray['listRows'];  	         
        }       
		$getField='*';
    	if(isset($dataarray['getField'])) {  
    		$getField=$dataarray['getField'];  	         
        }
     	$groupByField='id';
    	if(isset($dataarray['groupByField'])) {  
    		$groupByField=$dataarray['groupByField'];  	         
        } 
        switch($flag) {
            case '1':
                $info=$database->where($wherearray)->field($getField)->order($sort)->select();
                break;
            case '2'://分页操作
                $info=$database->where($wherearray)->field($getField)->order($sort)->limit($firstRow.','.$listRows)->select();
                break;
            case '3'://获取个数
                $info=$database->where($wherearray)->count();
                break;
            case '4'://获取单条
                $info=$database->where($wherearray)->field($getField)->find();
                break;
            case '5'://分组排列查找
                $info=$database->where($wherearray)->field($getField)->group($groupByField)->select();
                break;
            default:
                $info=$database->where($wherearray)->select();
                break;
        }
//     echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
	
	
	
	}
	
	
}