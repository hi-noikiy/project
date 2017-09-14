<?php

namespace Admin\Model;
use Think\Model;

class WholeReserveModel extends Model {
	var $tablename="whole_reserve";
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
    	
        $ret=$this->add_basedata($dataarray);
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
    		$data['id']=$dataarray['id'];
    		$ret=$this->getlist($data,4);
    		
//    		dump($ret);
    		
    		$data=array();
    		$data['reserveId']=$ret['id'];
    		$data['filmNoOld']=$ret['filmNo'];
    		$data['filmNo']=$dataarray['filmNo'];
    		$data['viewingDateOld']=$ret['viewingDate'];
    		$data['viewingDate']=$dataarray['viewingDate'];
    		$data['totalOld']=$ret['total'];
    		$data['total']=$dataarray['total'];
    		$data['endTimeOld']=$ret['endTime'];
    		$data['endTime']=$dataarray['endTime']; 
    		$data['filmNameOld']=$ret['filmName'];
    		$data['filmName']=$dataarray['filmName'];  

//    		dump($data);
    		M('whole_reserve_log')->add($data);
    	
    	
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
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
   		if(isset($dataarray['eltstate'])) {    
        	$wherearray['state']=array('elt',$dataarray['eltstate']);            
        }
    	if(isset($dataarray['state'])) {    
        	$wherearray['state']=array('eq',$dataarray['state']);            
        }
        if(isset($dataarray['start_time'])&&isset($dataarray['end_time'])) {
            $starttime=$dataarray['start_time'];
            $endtime=$dataarray['end_time']+86399;
            $wherearray['viewingDate']=array('exp','>='.$starttime.' and viewingDate <= '.$endtime);
        }elseif(isset($dataarray['start_time'])) {
            $starttime=$dataarray['start_time'];
            $wherearray['viewingDate']=array('exp','>='.$starttime);
        }elseif(isset($dataarray['end_time'])) {
            $endtime=$dataarray['end_time']+86399;
            $wherearray['viewingDate']=array('exp','<= '.$endtime);
        }
        
   		if(isset($dataarray['start_paymentTime'])&&isset($dataarray['end_paymentTime'])) {
            $starttime=$dataarray['start_paymentTime'];
            $endtime=$dataarray['end_paymentTime']+86399;
            $wherearray['paymentTime']=array('exp','>='.$starttime.' and paymentTime <= '.$endtime);
        }elseif(isset($dataarray['start_paymentTime'])) {
            $starttime=$dataarray['start_paymentTime'];
            $wherearray['paymentTime']=array('exp','>='.$starttime);
        }elseif(isset($dataarray['end_paymentTime'])) {
            $endtime=$dataarray['end_paymentTime']+86399;
            $wherearray['paymentTime']=array('exp','<= '.$endtime);
        }
     	if(isset($dataarray['tel'])) {    
        	$wherearray['tel']=array('like','%'.$dataarray['tel'].'%');            
        }
     	if(isset($dataarray['videoId'])) {    
        	$wherearray['videoId']=array('eq',$dataarray['videoId']);            
        }
    	if(isset($dataarray['paymentState'])) {    
        	$wherearray['paymentState']=array('eq',$dataarray['paymentState']);            
        }
    	if(isset($dataarray['neqpaymentState'])) {
			$wherearray['paymentState']=array('neq',$dataarray['neqpaymentState']);
		}
    	if(isset($dataarray['eltpaymentState'])) {    
        	$wherearray['paymentState']=array('elt',$dataarray['eltpaymentState']);            
        }

    	if($dataarray['begintime']&&$dataarray['endtime']) {
			$wherearray['viewingDate']=array(array('exp','<= '.$dataarray['begintime'].' and endTime >= '.$dataarray['begintime']),array('exp','<='.$dataarray['endtime'].' and endTime  >= '.$dataarray['endtime']),array('exp', "BETWEEN '".$dataarray['begintime']."'and'".$dataarray['endtime']."'  and `endTime` between '".$dataarray['begintime']."'and'".$dataarray['endtime']."'"),'or');
		}
    	if(isset($dataarray['nequid'])) {
			$wherearray['uid']=array('neq',$dataarray['nequid']);
		}
    	if(isset($dataarray['neqid'])) {
			$wherearray['id']=array('neq',$dataarray['neqid']);
		}
//		$database = D('WholeReserveView');
		$database = M('whole_reserve');
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
                $info=$database->where($wherearray)->order($sort)->select();
                if(is_array($info))
        		foreach($info as $k=> $v) {
               		if($v['filmName']===null){
               			$data=array();
               			$data['filmNo']=array('eq',$v['filmNo']);
               			$ret=M('cinema_plan')->where($data)->find();
               			$info[$k]['filmName']=$ret['filmName'];
               			$info[$k]['cast']='';
               			$info[$k]['version']=$ret['copyType']; 
               		}   	               	
               	}
                break;
            case '2'://分页操作
                $info=$database->where($wherearray)->order($sort)->limit($firstRow.','.$listRows)->select();
                break;
            case '3'://获取个数
                $info=$database->where($wherearray)->count();
                break;
            case '4'://获取单条
                $info=$database->where($wherearray)->find();
                 if($info['filmName']===null){
               			$data=array();
               			$data['filmNo']=array('eq',$info['filmNo']);
               			$ret=M('cinema_plan')->where($data)->find();
               			$info['filmName']=$ret['filmName'];
               			$info['cast']='';
               			$info['version']=$ret['copyType']; 
               	}  
                break;
            case '5'://选择字段查询操作
                $info=$database->where($wherearray)->field($getField)->select();
                break;
            default:
                $info=$database->where($wherearray)->select();
                break;
        }
//    echo $database->getlastsql();
        if($info||$info==0) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
	/**
     *
     *功能：查询信息
     *返回：成功：详细信息
     *           失败：false
     *
     */
    public function reserve_relation_package_getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
    	if(isset($dataarray['type'])) {    
        	$wherearray['type']=array('eq',$dataarray['type']);            
        }
    	if(isset($dataarray['reserveId'])) {    
        	$wherearray['reserveId']=array('eq',$dataarray['reserveId']);            
        }
       	$database = D('WholeReservePackageView'); 
//        $database = M($this->tablename);		
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
//     echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
    /**
     *
     *功能：查询信息
     *返回：成功：详细信息
     *           失败：false
     *
     */
    public function reserve_relation_service_getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
    	if(isset($dataarray['type'])) {    
        	$wherearray['type']=array('eq',$dataarray['type']);            
        }
    	if(isset($dataarray['reserveId'])) {    
        	$wherearray['reserveId']=array('eq',$dataarray['reserveId']);            
        }
//        $database = M($this->tablename);	
		$database = D('WholeReserveServiceView');	
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
//     echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
	/**
     *
     *功能：查询信息
     *返回：成功：详细信息
     *           失败：false
     *
     */
    public function reserve_log_getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
    	if(isset($dataarray['type'])) {    
        	$wherearray['type']=array('eq',$dataarray['type']);            
        }
    	if(isset($dataarray['reserveId'])) {    
        	$wherearray['reserveId']=array('eq',$dataarray['reserveId']);            
        }
    	$database = M('whole_reserve_log');
//        $database = M($this->tablename);		
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
//     echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
/**
     *
     *功能：查询信息
     *返回：成功：详细信息
     *           失败：false
     *
     */
    public function film_getlist($dataarray=array(),$flag="1") {
        $wherearray=array();

    	if(isset($dataarray['filmNo'])) {    
        	$wherearray['filmNo']=array('eq',$dataarray['filmNo']);            
        }
    	
        $database = M('film');
//        $database = M($this->tablename);		
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
//     echo $database->getlastsql();
        if($info||$info==0) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
    
    /**
     *
     *功能：查询信息
     *返回：成功：详细信息
     *           失败：false
     *
     */
    public function admin_getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
        $database = M('admin');
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
//     echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
	/**
	 *
	 *功能：影片的查询信息
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function cinema_plan_getlist($dataarray=array(),$flag="1") {
		$wherearray=array();
		if(isset($dataarray['filmNo'])) {
			$wherearray['filmNo']=array('eq',$dataarray['filmNo']);
		}
		if(isset($dataarray['time'])) {
			$starttime=$dataarray['time'];
			$endtime=$dataarray['time']+86399;
			$wherearray['startTime']=array('exp','>='.$starttime.' and startTime <= '.$endtime);
		}
		if(isset($dataarray['egtstartTime'])) {
		
			$wherearray['startTime']=array('egt',$dataarray['egtstartTime']);
		}
		$database = M('cinema_plan');
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
				
			case '5'://拖过group获取内容
				$info=$database->where($wherearray)->field($getField)->group('filmNo')->select();
				
				
				if(isset($info)) {
					foreach($info as $k => $v) {
					
						$data=array();
						$data['filmNo']=array('eq',$v['filmNo']);
						$ret=M('film')->where($data)->find();
						if($ret){
							$info[$k]['duration']=$ret['duration'];												
						}else{						
							$info[$k]['duration']='90';						
						}
					
					
					}	
				}
				
				
				break;	
				
			default:
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
		}
//    echo $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}
    
    
    
    
    
}