<?php

namespace Web\Model;
use Think\Model;

class BannerModel extends Model {
		
  public function getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
  		if(isset($dataarray['neqid'])) {    
        	$wherearray['id']=array('neq',$dataarray['neqid']);            
        }
  		if(isset($dataarray['type'])) {    
        	$wherearray['type']=array('eq',$dataarray['type']);            
        }
  		if(isset($dataarray['home'])) {    
        	$wherearray['home']=array('eq',$dataarray['home']);            
        }
  		if(isset($dataarray['act'])) {    
        	$wherearray['act']=array('eq',$dataarray['act']);            
        }
       	$database = M('Banner');
     	$sort='priority desc';
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
//    echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
      public function banner_getlist($dataarray=array(),$flag='1') {
      	
      	$list=$this->getlist($dataarray,$flag);
      	
      	foreach($list as $k =>$v) {
      		
      		$list[$k]['img']='/Uploads/'.$v['img'];
      	
      	
      	}
      	
      	return $list;
      }
    
    
}