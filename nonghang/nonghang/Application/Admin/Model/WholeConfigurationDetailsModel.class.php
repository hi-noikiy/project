<?php

namespace Admin\Model;
use Think\Model;

class WholeConfigurationDetailsModel extends Model {
	var $tablename="whole_configuration_details";
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
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
     	if(isset($dataarray['planNumberId'])) {    
        	$wherearray['planNumberId']=array('eq',$dataarray['planNumberId']);            
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
    	$data=array();

//dump($dataarray);
        $Unit=explode('||',$dataarray['Unit']);
//        dump($Unit);
    	foreach ($Unit as $key => $value) {

    		$ls=explode(',',$value);
    		
            if(!isset($ls[3])) {
                continue;
            }
//            dump($ls);
	    	$data1=array();
            $data1['planNumberId']=$dataarray['planNumberId'];

            $data1['filmNo']=$ls['0'];
            $data1['price']=$ls['1'];
            $data1['fullHousePrice']=$ls['2'];
            $data1['favorablePrice']=$ls['3'];
            $data1['serviceCharge']=$ls['4'];
	    	$data[]=$data1;
    	}
    	
    	
    	$mode=D('WholePlanNumber');	
    	
    	$dataplan=array();
    	$dataplan['id']=$dataarray['planNumberId'];
    	$dataplan['state']=2;
    	$mode->update_model($dataplan);
//    	dump($data);
        $ret=$this->add_basedata($data,2);
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
        if(isset($dataarray['planNumberId'])) {    
        	$wherearray['planNumberId']=array('eq',$dataarray['planNumberId']);            
        }
     	if(isset($dataarray['filmNo'])) {    
        	$wherearray['filmNo']=array('eq',$dataarray['filmNo']);            
        }
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
		
        $database = M($this->tablename);
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
     * 获取影片名称
     * @param array $dataarray
     * @return Ambigous <multitype:, unknown>
     */
    public function getalllist($dataarray) {
    	$database = M('film');
    	$database1 = M('cinema_plan');
    	$ret=$this->getlist($dataarray);
    	$list=array();
    	foreach ($ret as $key=>$volue) {
    		$data=array();
    		if(!$volue['filmNo']) {
    			continue;
    		}
    		$data['filmNo']=array('eq',$volue['filmNo']); 
    		$info=$database1->where($data)->field('filmName')->find();
    		$volue['filmName']=$info['filmName'];
    		$list[]=$volue;
    	} 	
    	return $list;
    }
    
}