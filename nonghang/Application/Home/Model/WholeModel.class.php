<?php

namespace Home\Model;
use Think\Model;

/**
 * 包场数据库操作
 * @author jcjtim
 *
 */
class WholeModel extends Model {
	/**
	 *
	 *功能：首页广告
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function ads_getlist($dataarray=array(),$flag="1") {
		$wherearray=array();
		$database = M('whole_home_ads');
		switch($flag) {
			case '1':
				$info=$database->where($wherearray)->order($dataarray['sort'])->select();
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->order($dataarray['sort'])->limit($dataarray['firstRow'].','.$dataarray['listRows'])->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->find();
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($dataarray['getField'])->select();
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
	/**
	 *
	 *功能：套餐展示
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function package_getlist($dataarray=array(),$flag="1") {
		$wherearray=array();
		if(isset($dataarray['status'])) {
			$wherearray['status']=array('eq',$dataarray['status']);
		}
		if(isset($dataarray['id'])) {
			$wherearray['id']=array('eq',$dataarray['id']);
		}
		$database = M('whole_package_information');
		switch($flag) {
			case '1':
				$info=$database->where($wherearray)->order($dataarray['sort'])->select();
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->order($dataarray['sort'])->limit($dataarray['firstRow'].','.$dataarray['listRows'])->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->find();
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($dataarray['getField'])->select();
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
	/**
	 *
	 *功能：附加服务展示
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function service_getlist($dataarray=array(),$flag="1") {
		$wherearray=array();
		if(isset($dataarray['status'])) {
			$wherearray['status']=array('eq',$dataarray['status']);
		}
		if(isset($dataarray['id'])) {
			$wherearray['id']=array('eq',$dataarray['id']);
		}
		$database = M('whole_accessorial_service');
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
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->find();
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->select();
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
	/**
	 *
	 *功能：场次展示
	 *返回：成功：详细信息
	 *           失败：false
	 *
	 */
	public function plan_number_getlist($dataarray=array(),$flag="1") {
		$wherearray=array();
		if(isset($dataarray['time'])) {
			$wherearray['time']=array('eq',$dataarray['time']);
		}
		if(isset($dataarray['state'])) {
			$wherearray['state']=array('eq',$dataarray['state']);
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
		$database = M('whole_plan_number');
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
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->find();
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->select();
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
	
	public function plan_number_getlist_for_indx($dataarray) {
		
		$list=$this->plan_number_getlist($dataarray);
		
		foreach($list as $k=>$v) {
			if(isset($v['time']))
			$list[$k]['timeflag']=date('H:i',$v['time']);		
			if(isset($v['endTime']))
			$list[$k]['endTimeflag']=date('H:i',$v['endTime']);
			
			$data=array();
			$data['time']=$v['time'];
			$data['endTime']=$v['endTime'];
			if(isset($dataarray['videoId'])) {
				$data['videoId']=$dataarray['videoId'];
				$ret=$this->find_video_notinid($data);
				if($ret){				
					$list[$k]['classesd']='disabled';	
				}
			}else{				
				$ret=$this->find_video_do($data);
				if(!$ret){					
					$list[$k]['classesd']='disabled';						
				}		
			}	
		}
		
		
		return $list;
	
	
	}
	/**
	 * 通过表单内容获取影片内容
	 * @param array $dataarray 查询条件
	 * @param int $flag 调用方式
	 * @return list|count|boolean
	 */
	public function getlist_from_filmNo($dataarray=array(),$flag="1") {

		if(isset($dataarray['filmNo'])) {
			$wherearray['filmNo']=array('eq',$dataarray['filmNo']);
		}
		 
		if(isset($dataarray['time'])) {
			$wherearray['time']=array('eq',$dataarray['time']);
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
		//       $Model = D("BlogView");
		$database = D('WholePlanView');
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
					//               			echo '11111----'.$v['filmNo'];
					$data=array();
					$data['filmNo']=array('eq',$info['filmNo']);

					$ret=M('cinema_plan')->where($data)->find();


					 
					$info['filmName']=$ret['filmName'];
					$info['cast']='';
					$info['version']=$ret['copyType'];
					//               			dump($info[$k]);
				}
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->select();
				break;

			case '6'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->select();
				break;
		}
		//       echo  $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
		 
		 
	}

	/**
	 * //获取主题影院内容
	 * @param unknown_type $dataarray
	 * @param int $flag
	 * @return unknown|boolean
	 */
	public function video_getlist($dataarray=array(),$flag="1") {
		if(isset($dataarray['id'])) {
			$wherearray['id']=array('eq',$dataarray['id']);
		}
		if(isset($dataarray['state'])) {
			$wherearray['state']=array('eq',$dataarray['state']);
		}
		if(isset($dataarray['notinid'])) {
			$wherearray['id']=array('not in',$dataarray['notinid']);
		}
		$database = D('whole_video_information');
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
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->find();
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->select();
				break;

			case '6'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->select();
				break;
		}
		//       echo  $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}
	
	//获取主题影厅内容
	public function video_getlist_for_theme($dataarray) {
		$data=array();
		$data['time']=$dataarray['time'];
		$data['endTime']=$dataarray['endTime'];
		$notinid=$this->find_video_notinid($data);
		if($notinid)
		$dataarray['notinid']=$notinid;		
		$list=$this->video_getlist($dataarray);
		foreach($list as $k=>$v){
			$datav=array();
			$datav['videoId']=$v['id'];
			$datav['getField']='relativePath';			
			$ret=$this->video_map_getlist($datav);
			if($ret) {
				$list[$k]['relativePath']=$ret;
			}
		}
		return $list;
	}
	//获取主题影院图片内容
	public function video_map_getlist($dataarray=array(),$flag="1") {
		if(isset($dataarray['videoId'])) {
			$wherearray['videoId']=array('eq',$dataarray['videoId']);
		}
		$database = D('whole_video_interior_map');
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
				$info=$database->where($wherearray)->find();
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->select();
				break;

			case '6'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->select();
				break;
		}
		//       echo  $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}
	//获取支付类型内容
	public function payment_type_getlist($dataarray=array(),$flag="1") {
		if(isset($dataarray['videoId'])) {
			$wherearray['videoId']=array('eq',$dataarray['videoId']);
		}
		$database = D('whole_payment_type');
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
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->find();
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->select();
				break;

			case '6'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->select();
				break;
		}
		//       echo  $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}
	/**
	 * 收货人添加，初次添加为默认
	 * @param array $dataarray
	 * @return unknown
	 */
	public function add_consignee($dataarray) {	
		$data=array();
		$data['uid']=$dataarray['uid'];
		$data['mark']=1;
		$ret=$this->consignee_getlist($data);
		if($ret){
			$dataarray['mark']=0;
		}else {
			$dataarray['mark']=1;
		}		
		$mode=M('whole_consignee');
		$ret=$mode->add($dataarray);
		return $ret;	
	}
	//收货人删除
	public function del_consignee ($dataarray){
		$mode=M('whole_consignee');
		$wherearray=array();
		$wherearray['id']=array('EQ',$dataarray['id']);
		$ret=$mode->where($wherearray)->delete();
		return $ret;
	}
	//获取收货人添加
	public function eidt_consignee($dataarray) {		
		$mode=M('whole_consignee');
		$wherearray=array();
		$wherearray['id']=array('EQ',$dataarray['id']);
		$ret=$mode->where($wherearray)->save($dataarray);
		return $ret;
	}
	//获取收货人内容
	public function consignee_getlist($dataarray=array(),$flag="1") {
		if(isset($dataarray['uid'])) {
			$wherearray['uid']=array('eq',$dataarray['uid']);
		}
		if(isset($dataarray['id'])) {
			$wherearray['id']=array('eq',$dataarray['id']);
		}
		if(isset($dataarray['mark'])) {
			$wherearray['mark']=array('eq',$dataarray['mark']);
		}
		$database = D('whole_consignee');
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
				break;
			case '2'://分页操作
				$info=$database->where($wherearray)->order($sort)->limit($firstRow.','.$listRows)->select();
				break;
			case '3'://获取个数
				$info=$database->where($wherearray)->count();
				break;
			case '4'://获取单条
				$info=$database->where($wherearray)->find();
				break;
			case '5'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->select();
				break;
			case '6'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->select();
				break;
		}
		//       echo  $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}		 
	}
	/**
	 * 获取订单内容
	 * @param array $dataarray
	 * @param array $flag
	 * @return mixd
	 */
	public function reserve_getlist($dataarray=array(),$flag="1") {
		if(isset($dataarray['viewingDate'])) {
			$wherearray['viewingDate']=array('eq',$dataarray['viewingDate']);
		}
		if(isset($dataarray['gtviewingDate'])) {
			$wherearray['viewingDate']=array('gt',$dataarray['gtviewingDate']);
		}
		if(isset($dataarray['egtviewingDate'])) {
			$wherearray['viewingDate']=array('egt',$dataarray['egtviewingDate']);
		}
		if(isset($dataarray['eltviewingDate'])) {
			$wherearray['viewingDate']=array('elt',$dataarray['eltviewingDate']);
		}
		if(isset($dataarray['ltendTime'])) {
			$wherearray['endTime']=array('lt',$dataarray['ltendTime']);
		}
		if(isset($dataarray['eltendTime'])) {
			$wherearray['endTime']=array('elt',$dataarray['eltendTime']);
		}
		if(isset($dataarray['egtendTime'])) {
			$wherearray['endTime']=array('egt',$dataarray['egtendTime']);
		}
		
		if($dataarray['begintime']&&$dataarray['endtime']) {
			$wherearray['viewingDate']=array(array('exp','<= '.$dataarray['begintime'].' and endTime >= '.$dataarray['begintime']),array('exp','<='.$dataarray['endtime'].' and endTime  >= '.$dataarray['endtime']),array('exp', "BETWEEN '".$dataarray['begintime']."'and'".$dataarray['endtime']."'  and `endTime` between '".$dataarray['begintime']."'and'".$dataarray['endtime']."'"),'or');
		}
		if(isset($dataarray['videoId'])) {
			$wherearray['videoId']=array('eq',$dataarray['videoId']);
		}
		if(isset($dataarray['uid'])) {
			$wherearray['uid']=array('eq',$dataarray['uid']);
		}
		if(isset($dataarray['id'])) {
			$wherearray['id']=array('eq',$dataarray['id']);
		}
		if(isset($dataarray['neqid'])) {
			$wherearray['id']=array('neq',$dataarray['neqid']);
		}
		if(isset($dataarray['ltpaymentTime'])) {
			$wherearray['paymentTime']=array('lt',$dataarray['ltpaymentTime']);
		}
		if(isset($dataarray['gtpaymentTime'])) {
			$wherearray['paymentTime']=array('gt',$dataarray['gtpaymentTime']);
		}
		if(isset($dataarray['paymentState'])) {
			$wherearray['paymentState']=array('eq',$dataarray['paymentState']);
		}
		if(isset($dataarray['neqpaymentState'])) {
			$wherearray['paymentState']=array('neq',$dataarray['neqpaymentState']);
		}
		
		if(isset($dataarray['eltstate'])) {    
        	$wherearray['state']=array('elt',$dataarray['eltstate']);            
        }
		
		
		if(isset($dataarray['neqdelState'])) {
			$wherearray['delState']=array('neq',$dataarray['neqdelState']);
		}
		if(isset($dataarray['nequid'])) {
			$wherearray['uid']=array('neq',$dataarray['nequid']);
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
				if($info)
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
				if(is_array($info)&&$info['filmName']===null){
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
			case '6'://选择字段查询操作
				$info=$database->where($wherearray)->field($getField)->find();
				break;
			default:
				$info=$database->where($wherearray)->select();
				break;
		}
//    echo  $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}	 
	}
	 
	/**
	 * 修改订单操作
	 * @param array $dataarray
	 * @return mixd
	 */
	public function update_reserve($dataarray) {
		$wherearray['id']=array('EQ',$dataarray['id']);
		$userbase = M('WholeReserve');
		$ret=$userbase->where($wherearray)->save($dataarray);
		//        echo $userbase->getlastsql();
		if($ret) {
			return 1;
		}else {
			return false;
		}
	}
	/**
	 * 确认订单
	 * @param array $dataarray 订单内容
	 * @param array $packagerelation 套餐内容
	 * @param array $servicerelation 服务内容
	 * @param array $datainvoice 发票内容
	 * @return 订单号
	 */
	public function add_payoff($dataarray,$packagerelation,$servicerelation,$datainvoice) {
		$model=	M('whole_reserve');
		$id=$model->add($dataarray);
		
		
		//       	echo $model->getlastsql();
		wlog($model->getlastsql(),'bookwhole','订单过程');
		
		$mode2=	M('whole_reserve_relation');
		$data=array();
		if(is_array($packagerelation))
		foreach ($packagerelation as $v ) {
			$datac=array();
			$datac['reserveId']=$id;
			$datac['type']=1;
			$datac['relationId']=$v['id'];
			$datac['num']=$v['num'];
			$datapackage=array();
			$datapackage['id']=$v['id'];
			$packagelist=$this->package_getlist($datapackage,4);
			$datac['name']=$packagelist['name'];
			$datac['detail']=$packagelist['detail'];
			$datac['price']=$packagelist['price']; 
			$data[]=$datac;
		}
		if(is_array($servicerelation))
		foreach ($servicerelation as $v ) {
			$datac=array();
			$datac['reserveId']=$id;
			$datac['type']=2;
			$datac['relationId']=$v['id'];
			$datac['num']=  1;
			$dataservice=array();
			$dataservice['id']=$v['id'];
			$servicegelist=$this->service_getlist($dataservice,4);
			$datac['name']=$servicegelist['name'];
			$datac['detail']='';
			$datac['price']=$servicegelist['price'];
			$data[]=$datac;
		}
		$ret=$mode2->addAll($data);
		if($datainvoice){
			$mode3=	M('whole_invoice_details');
			$datainvoice['reserveId']=$id;
			$mode3->add($datainvoice);
		}
		return $id;
	}
	/**
	 *  付款订单
	 * @param unknown_type $dataarray
	 * @param unknown_type $packagerelation
	 * @param unknown_type $servicerelation
	 * @param unknown_type $datainvoice
	 * @return unknown
	 */
	public function eidt_payoff($dataarray,$packagerelation,$servicerelation,$datainvoice) {
		$model=	M('whole_reserve');
		$wherearray['id']=array('EQ',$dataarray['id']);
		$ret=$model->where($wherearray)->save($dataarray);
		$id=$dataarray['id'];
		$mode2=	M('whole_reserve_relation');
		$data=array();
		if(is_array($packagerelation))
		foreach ($packagerelation as $v ) {
			$datac=array();
			$datac['reserveId']=$id;
			$datac['type']=1;
			$datac['relationId']=$v['id'];
			$datac['num']=$v['num'];
			$data[]=$datac;
		}
		if(is_array($servicerelation))
		foreach ($servicerelation as $v ) {
			$datac=array();
			$datac['reserveId']=$id;
			$datac['type']=2;
			$datac['relationId']=$v['id'];
			$datac['num']=1;
			$data[]=$datac;
		}
		$ret=$mode2->addAll($data);
		if($datainvoice){
			$mode3=	M('whole_invoice_details');
			$datainvoice['reserveId']=$id;
			$this->add_invoice($datainvoice,isset($dataarray['uid'])?$dataarray['uid']:'');
			$mode3->add($datainvoice);
		}
		return $id;
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
	 *功能：影片的查询信息
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
//		if(isset($dataarray['time'])) {
//			$starttime=$dataarray['time'];
//			$endtime=$dataarray['time']+86399;
//			$wherearray['startTime']=array('exp','>='.$starttime.' and startTime <= '.$endtime);
//		}
		if(isset($dataarray['time'])) {
			$wherearray['startTime']=array('egt',$dataarray['time']);
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
				break;					
			default:
				$info=$database->where($wherearray)->field($getField)->order($sort)->select();
				break;
		}
//		    echo $database->getlastsql();
		if($info) {
			return $info;//返回用户id
		}else {
			return false;
		}
	}
	/**
	 * 用户删除订单操作
	 * @param array $data
	 */
	public function del_order($data) {
		$wherearray=array();
		$wherearray['id']=array('EQ',$data['id']);
		$database = M('whole_reserve');
		$dataarray=array();
		$dataarray['delState']=1;
		$database->where($wherearray)->save($dataarray);
	}
	/**
	 * 发票添加
	 * @param array $data
	 */
	public function add_invoice($data,$uid='') {
		$model=	M('whole_invoice_details');
		if($uid) {
			$dataarray=array();
			$dataarray['uid']=$uid;
			$dataarray['mark']=1;
			$ret=$this->consignee_getlist($dataarray,4);
			$data['nickname']=$ret['name'];
			$data['userPhone']=$ret['phone'];
		}
		$model->add($data);
	}
	/**
	 * 查询时间段可以选择的影厅
	 * @param array $data
	 */
	public function find_video_do($data) {		
		$notinid=$this->find_video_notinid($data);
		if($notinid) {
			$dataarray=array();
			$dataarray['notinid']=$notinid;			
			$dataarray['state']=1;			
			$rets=$this->video_getlist($dataarray);
			if($rets){			
				return true;
			}else {			
				return false;
			}	
		}else{
			
			$dataarray=array();		
			$dataarray['state']=1;			
			$rets=$this->video_getlist($dataarray);
			if($rets){			
				return true;
			}else {			
				return false;
			}
		
		}
		return true;
//		dump($ret);
	}
	/**
	 * 获取通过时间判断去除的影厅id
	 * @param array $data
	 */
	public function find_video_notinid($data) {
		$dataarray=array();
		$dataarray['begintime']=$data['time'];
		$dataarray['endtime']=$data['endTime'];
		$dataarray['neqpaymentState']=3;
		$dataarray['eltstate']=2;		
		if(isset($data['videoId'])) {
			$dataarray['videoId']=$data['videoId'];
		}
    	$dataarray['getField']='videoId';
		$ret=$this->reserve_getlist($dataarray,5);
//		dump($ret);
		if($ret) {
			$notinid='';
			foreach($ret as $v) {	
				$notinid.=$v['videoId'].',';
			}
			return $notinid;
		}
		return false;
//		dump($ret);
	}
	
	
	/**
	 * 获取15分钟内未支付成功的订单并设置成支付失败
	 */
	public function get_onpay() {
		$data=array();
		$data['paymentState']=1;
		$data['ltpaymentTime']=time()-15*60;
		$list=$this->reserve_getlist($data);
		if(is_array($list))
		foreach($list as $v) {
			$data=array();
			$data['id']=$v['id'];
			$data['paymentState']=3;
			$this->update_reserve($data);
		}
	}
	
	/**
	 * 周期数字转换
	 * @param int $flag 周期数字
	 * @return string
	 */
	public function week_over($flag) {
		$return='日';
		switch ($flag) {
			case 0:
				$return='日';
				break;
			case 1:
				$return='一';
				break;
			case 2:
				$return='二';
				break;
			case 3:
				$return='三';
				break;
			case 4:
				$return='四';
				break;
			case 5:
				$return='五';
				break;
			case 6:
				$return='六';
				break;
			default:
				$return='日';
				break;
		}
		return $return;
	}





	 



}