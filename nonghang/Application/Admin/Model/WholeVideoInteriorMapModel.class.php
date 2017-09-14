<?php

namespace Admin\Model;
use Think\Model;

class WholeVideoInteriorMapModel extends Model {
	var $tablename="whole_video_interior_map";
    /**
     *
     *功能：添加信息
     *
     */
    private function add_basedata($dataarray,$flag="1") {
        $userbase = M($this->tablename);
        switch ($flag) {
            case 1:
                $ret = $userbase->add($dataarray);
                break;
            case 2:
                $ret = $userbase->addAll($dataarray);
                break;
            default:
                $ret = $userbase->add($dataarray);
                break;
        }
        if($ret) {
            return $ret;//返回用户id
        }else {
            return false;
        }
    }
    /**
     *
     *功能：删除信息
     *返回：成功：true
     *           失败：false
     *
     */
    private function delete_basedata($dataarray) {
    	if(isset($dataarray['id'])) {
    		$wherearray['id']=array('EQ',$dataarray['id']);
    		$data=array();
    		$data['id']=$dataarray['id'];
    		$ret1=$this->getlist($data,4);
    		unlink($ret1['storePath']);
    	}
    	if(isset($dataarray['videoId'])) {
    		$wherearray['videoId']=array('EQ',$dataarray['videoId']);
    		$data=array();
    		$data['videoId']=$dataarray['videoId'];
    		$ret1=$this->getlist($data);
    		foreach($ret1 as $v) {	
    			unlink($v['storePath']);  		
    		}
    	}
        $userbase = M($this->tablename);
        $ret=$userbase->where($wherearray)->delete();
        if($ret) {
            return 1;//返回删除成功
        }else {
            return false;
        }
    }
    /**
     *
     *功能：修改信息
     *返回：成功：true
     *           失败：false
     *
     */
    private function update_basedata($dataarray) {
        $wherearray['id']=array('EQ',$dataarray['id']);
        $userbase = M($this->tablename);
        $ret=$userbase->where($wherearray)->save($dataarray);
        if($ret) {
            return 1;
        }else {
            return false;
        }
    }

    /**
     *
     *功能：添加信息
     *
     */
    public function add_model($dataarray) {
    	$targetFolder = C('WHOLE_UPLOAD'); // Relative to the root
    	$relativePath =$targetFolder.'/Video/'.$dataarray['videoId'].'/';
    	$oldstorePath = $_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/'.CPUID.'/';  	
    	$newstorePath =	$_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/Video/'.$dataarray['videoId'].'/';
	    if(!is_dir($newstorePath)) {
	       mkdir($newstorePath,0777,true);
	    }
    	$datas=array();
    	if(is_array($dataarray['hallImage']))
    	foreach($dataarray['hallImage'] as $v) {
    		$data=array();
    		$data['videoId']=$dataarray['videoId'];
    		$data['storePath']=$newstorePath.$v;
    		$data['relativePath']=$relativePath.$v;
    		$datas[]=$data;
    		copy($oldstorePath.$v,$newstorePath.$v);   		
    		unlink($oldstorePath);   	
    	}
        $ret=$this->add_basedata($datas,2);
        return $ret;
    }
    /**
     *
     *删除信息
     *
     */
    public function delete_model($dataarray) {
        $ret=$this->delete_basedata($dataarray);
        return $ret;
    }
    /**
     *
     *修改信息
     *
     */
    public function update_model($dataarray) {
        $ret=$this->update_basedata($dataarray);
        return $ret;
    }
    /**
     *
     *功能：查询信息
     *返回：成功：详细信息
     *           失败：false
     *
     */
    public function getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['videoId'])) {    
        	$wherearray['videoId']=array('eq',$dataarray['videoId']);            
        }
    	if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
        $database = M($this->tablename);
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
            default:
                $info=$database->where($wherearray)->field($getField)->order($sort)->select();
                break;
        }
//      echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
    /**
     *
     *功能：构造获取的内容级获取电影名称
     *返回：成功：详细信息
     *           失败：false
     *
     */
    public function getalllist($dataarray) {
    	$database = M('film');
    	$ret=$this->getlist($dataarray);
    	$list=array();
    	foreach ($ret as $key=>$volue) {
    		$data=array();
    		if(!$volue['filmNo']) {
    			continue;
    		}
    		$data['filmNo']=array('eq',$volue['filmNo']); 
    		$info=$database->where($data)->field('filmName')->find();
//    		echo $database->getlastsql();
    		$volue['filmName']=$info['filmName'];
    		$list[]=$volue;
    	} 	
    	return $list;
    	
      	
    }
    
}