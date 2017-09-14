<?php

namespace Admin\Model;
use Think\Model;

class WholePlanNumberModel extends Model {
	var $tablename="whole_plan_number";
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
        $wherearray['id']=array('EQ',$dataarray['id']);
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
//        echo $userbase->getlastsql();
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
    	$timearray=$dataarray['time'];
    	$endTimearray=$dataarray['endTime'];
    	$pricearray=$dataarray['price'];
    	$oriPricearray=$dataarray['oriPrice'];
    	$data=array();
    	foreach ($timearray as $key => $value) {
    		$year=date('Y',$key);
			$m=date('m',$key);
			$d=date('j',$key);
	    	foreach ($value as $key1 => $imet) {
	    		$data1=array();
	    		$ls=explode(':',$imet);
	    		$time=mktime($ls['0'], $ls['1'], 0, $m, $d, $year);
	    		$ls1=explode(':',$endTimearray[$key][$key1]);
	    		$endTime=mktime($ls1['0'], $ls1['1'], 0, $m, $d, $year);
	    		$price=$pricearray[$key][$key1];
    			$oriPrice=$oriPricearray[$key][$key1];
	    		$data1['state']=1;
	    		if($time){
	    			$data1['time']=$time;
	    		}else{
	    		
	    			return 4;
	    		}
		    	if($endTime){
		    		$data1['endTime']=$endTime;
	    		}else{
	    			return 4;
	    		}
	    		if($price){
	    			$data1['price']=$price;
	    		}else{
	    		
	    			return 4;
	    		}
	    		if($oriPrice){
	    			$data1['oriPrice']=$oriPrice;
	    		}else{	    		
	    			return 4;
	    		}    		
	    		$data1['duration']=($endTime-$time)/60;
	    		if($data1['duration']<0){
	    			
	    			return 5;
	    		
	    		
	    		}
	    		$data[]=$data1;
	    	}
    	}
    	
    	$ret=$this->pddata($data);
    	if(!$ret){
    		return 5;
    	}
    	
        $ret=$this->add_basedata($data,2);
        return $ret;
    }
    private function pddata($data) { 	
    	foreach ($data as $k1 => $v1) {
			$dataarray=array();
			$dataarray['begintime']= $v1['time'];
			
			$dataarray['endtime']= $v1['endTime'];
			$ret=$this->getlist($dataarray,4);
			if($ret){
				return false;			
			}
	    	foreach ($data as $k2 => $v2) {
				if($k1==$k2){
					continue;
				}
				if($v1['time']>=$v2['time']&&$v1['time']<=$v2['endTime']){
					return false;
				}
	    		if($v1['endTime']>=$v2['time']&&$v1['endTime']<=$v2['endTime']){		
					return false;
				}
	    		if($v1['time']<=$v2['time']&&$v1['endTime']>=$v2['endTime']){		
					return false;
				}

	    	}
    	}
    	
    	return true;
    }
    
    
    
    
    
    
    /**
     * 排期的添加
     * @param unknown_type $dataarray
     * @return Ambigous <boolean, unknown>
     */
    public function add_model_v2($dataarray) {
    	$data=array();
    	foreach ($dataarray as $key => $value) {
    		$year=date('Y',$key);
			$m=date('m',$key);
			$d=date('j',$key);
	    	foreach ($value as $imet) {
	    		$data1=array();
	    		$days_array=explode('-',$imet);    		
	    		$ls=explode(':',$days_array['0']);
	    		$ls1=explode(':',$days_array['1']);	    		
	    		$time=mktime($ls['0'], $ls['1'], 0, $m, $d, $year);
	    		$endtime=mktime($ls1['0'], $ls1['1'], 0, $m, $d, $year);	    		
	    		$data1['state']=1;
	    		$data1['time']=$time;
	    		$data1['endTime']=$endtime;	    		
	    		$data1['duration']=($endtime-$time)/60;	    		
	    		$data[]=$data1;
	    	}
    	}
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
    	$data=array();
    	$data['neqid']=$dataarray['id'];
    	$data['begintime']=$dataarray['time'];
    	$data['endtime']=$dataarray['endTime'];   	
    	$ret=$this->getlist($data);
    	if($ret){  		
    		return 4;   	
    	}
 		
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
        if(isset($dataarray['time'])) {    
        	$wherearray['time']=array('eq',$dataarray['time']);            
        }
        if(isset($dataarray['elttime'])) {
        	$wherearray['time']=array('elt',$dataarray['elttime']);
        }
    	if(isset($dataarray['egttime'])) {
        	$wherearray['time']=array('egt',$dataarray['egttime']);
        }
        if(isset($dataarray['egtendTime'])) {
        	$wherearray['endTime']=array('egt',$dataarray['egtendTime']);
        }     	
        if(isset($dataarray['eltendTime'])) {
        	$wherearray['endTime']=array('elt',$dataarray['eltendTime']);
        }
     	if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
    	if(isset($dataarray['neqid'])) {    
        	$wherearray['id']=array('neq',$dataarray['neqid']);            
        }     
    	if(isset($dataarray['start_time'])&&isset($dataarray['end_time'])) {
			$starttime=$dataarray['start_time'];
			$endtime=$dataarray['end_time']+86399;
			$wherearray['time']=array('exp','>='.$starttime.' and time <= '.$endtime);
		}elseif(isset($dataarray['start_time'])) {
			$starttime=$dataarray['start_time'];
			$wherearray['time']=array('exp','>='.$starttime);
		}elseif(isset($dataarray['end_time'])) {
			$endtime=$dataarray['end_time']+86399;
			$wherearray['time']=array('exp','<= '.$endtime);
		} 
		
//		dump($dataarray);
		
    	if($dataarray['begintime']&&$dataarray['endtime']) {
			$wherearray['time']=array(array('exp','<= '.$dataarray['begintime'].' and endTime >= '.$dataarray['begintime']),array('exp','<='.$dataarray['endtime'].' and endTime  >= '.$dataarray['endtime']),array('exp', "BETWEEN '".$dataarray['begintime']."'and'".$dataarray['endtime']."'  and `endTime` between '".$dataarray['begintime']."'and'".$dataarray['endtime']."'"),'or');
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
        if($info||$info==0) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
}