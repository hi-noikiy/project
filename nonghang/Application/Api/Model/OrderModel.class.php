<?php

namespace Api\Model;
use Think\Model;

class OrderModel extends Model {
	
	function getList($field='',$map=array(),$start=0,$limit=9999999,$order='lockTime desc'){
		$map['visible']=0;
		 $orders= M('OrderFilm')->field($field)->where($map)->order($order)->limit($start,$limit)->select();
		 foreach ($orders as $k=>$value) {
		 	$orders[$k]['allprice']=$value['myPrice']*$value['seatCount'];
		 	if($value['status']=='0'){
		 		if($value['lockTime']<time()){
		 			$orderarr['orderCode']=$value['orderCode'];
		 			$orderarr['status']=5;
		 			M('OrderFilm')->save($orderarr);
		 			$str='超时关闭';
		 		}else{
		 			$str='等待支付';
		 		}
		 	}elseif($value['status']=='2'){
		 		$str='支付异常';
		 	}elseif($value['status']=='3'){
		 		$str='支付完成';
		 		if(!empty($value['printNo'])){
		 			$orders[$k]['printNo']=substr($value['printNo'],-8,8);
		 		}else{
		 			$orders[$k]['printNo']=$value['verifyCode'];
		 		}
		 	}elseif($value['status']=='5'){
		 		$str='超时关闭';
		 	}elseif($value['status']=='6'){
		 		$str='订单取消';
		 	}elseif($value['status']=='7'){
		 		$str='订单异常';
		 	}elseif($value['status']=='8'){
		 		$str='购票失败退款';
		 	}elseif($value['status']=='9'){
		 		$str='退款退票';
		 	}elseif($value['status']=='10'){
		 		$str='退款失败';//10
		 	}
		 	$orders[$k]['str']=$str;
		 }
		 return $orders;
	}
    function findObj($orderid){
    	
    	$map['orderCode|otherorderCode']=$orderid;
    	$order=M('OrderFilm')->where($map)->find();
    	if($order['status']=='0'){
    		if($order['lockTime']<time()){
    			$orderarr['orderCode']=$order['orderCode'];
    			$orderarr['status']=5;
    			M('OrderFilm')->save($orderarr);
    			$str='超时关闭';
    		}else{
    			$str='等待支付';
    		}
    	}elseif($order['status']=='2'){
    		$str='支付异常';
    	}elseif($order['status']=='3'){
    		$str='支付完成';
    	}elseif($order['status']=='5'){
    		$str='超时关闭';
    	}elseif($order['status']=='6'){
    		$str='订单取消';
    	}elseif($order['status']=='7'){
    		$str='订单异常';
    	}elseif($order['status']=='8'){
    		$str='购票失败退款';
    	}elseif($order['status']=='9'){
    		$str='退款退票';
    	}elseif($order['status']=='10'){
    		$str='退款失败';//10
    	}
		if(!empty($order['printNo'])){
    		$order['printNo']=substr($order['printNo'],-8,8);
    	}else{
    		$order['printNo']=$order['verifyCode'];
    	}
    	$order['statustr']=$str;
    	return $order;
    }
    function saveObj($map=''){
    	return M('OrderFilm')->save($map);
    }
    function addObj($lock,$plan,$user,$str,$mobile,$seatInfo,$fead,$cinemaGroupId){

    	$price=D('cinema')->getseatprice(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo']),$fead['findSectionId']);
    	$tc=explode(',',$str);
    	$arr['orderCode']=$lock['OrderCode'];
    	$arr['uid']=$user['id'];
    	$arr['featureAppNo']=$plan['featureAppNo'];
    	$arr['seatIntroduce']=$str;
    	$arr['price']=$plan['listingPrice'];
    	$arr['seatCount']=count($tc);
    	$arr['copyType']=$plan['copyType'];
    	$arr['copyLanguage']=$plan['copyLanguage'];
    	$arr['cinemaCode']=$plan['cinemaCode'];
    	$arr['cinemaName']=$plan['cinemaName'];
    	$arr['hallNo']=$plan['hallNo'];
    	$arr['seatInfo']=$seatInfo;
    	$arr['hallName']=$plan['hallName'];
    	$arr['filmNo']=$plan['filmNo'];
    	$arr['filmName']=$plan['filmName'];
    	$arr['mobile']=$mobile;
    	$arr['totalTime']=$plan['totalTime'];
    	$arr['downTime']=time();
    	$arr['otherfilmNo']=$plan['otherfilmNo'];
    	if(!empty($user['cardNum'])){
    		$arr['cardId']=$user['cardNum'];
    	}else{
    		$arr['mobileNum']=$user['mobile'];
    	}
    	$arr['myPrice']=$plan['memberPrice']+$price;
    	$arr['servicePrice']=$price;
    	$arr['sectionId']=$fead['findSectionId'];
    	$arr['sectionName']=$fead['findSectionName'];
    	$arr['serialNum']=$lock['serialNum'];
    	$arr['lockTime']=strtotime($lock['AutoUnlockDatetime']);
    	$arr['startTime']=$plan['startTime'];
    	$arr['way']='app'; //微信
        $arr['featureNo']=$plan['featureNo']; 
    	$arr['cinemaGroupId']=$cinemaGroupId; //购票系统
    	if(M('OrderFilm')->add($arr)){
    		$order=M('OrderFilm')->where(array('uid'=>$user['id'],'cinemaCode'=>$plan['cinemaCode'],'filmNo'=>$plan['filmNo'],'serialNum'=>$lock['serialNum'],'startTime'=>$plan['startTime']))->find();
    		return $order['orderCode'];
    	}else{
    		return 0;
    	}
    }
    function deleteObj($map=''){
    	return M('OrderFilm')->where($map)->delete();
    }
    function countObj($map=''){
    	return M('OrderFilm')->where($map)->count();
    }
    /**
     * 取消订单
     *
     * @param unknown $orderid
     */
    function cancelOrder($orderid) {
    	if (empty ( $orderid )) {
    		$release['ResultCode']='1';
    		$release['Message']='订单号不能为空';
    		return $release;
    		die();
    	}
    	$order = M ( 'OrderFilm' )->find ( $orderid );
    	$cinema = M ( 'cinema' )->find ( $order['cinemaCode'] );
    	if ($order ['status'] != '3') {
    		$seatin=json_decode($order['seatInfo'],true);
    		foreach ($seatin as $val){
    			$a[]=$val['seatNo'];
    		}
    		$releasearr = array (
    				'cinemaCode' => $order ['cinemaCode'],
    				'link' => $cinema ['link'],
    				'orderCode' => $order ['orderCode'],
    				'featureAppNo' => $order ['featureAppNo'],
    				'seatInfos' =>$a,
    				'ticketCount' =>$order['seatCount'],
    		);
    		$release =D('ZMMove')->releaseSeat ( $releasearr );
    		if ($release ['ResultCode'] == '0') {
    			$orderarr ['orderCode'] = $orderid;
    			$orderarr ['status'] = 6;
    			M ( 'OrderFilm' )->save ( $orderarr );
    		}else{
    			if(empty($release['Message'])){
    				$release['Message']='取消订单失败';
    			}
    		}
            return $release;
    		
    	}
    }
    /**
     * 刷新订单状态
     *
     * @return mixed
     */
    public function updateOrder($uid){
    	$myOrderFilm=M('OrderFilm')->where('uid='.$uid.' and status=0 and way="app"')->find();
    	$t=0;
    	if(!empty($myOrderFilm)){
    		if($myOrderFilm['lockTime']<time()){
    			$orderarr['orderCode']=$myOrderFilm['orderCode'];
    			$orderarr['status']=5;//超时关闭
    			if(!M('OrderFilm')->save($orderarr)){
    				$t=$myOrderFilm['orderCode'];
    			}
    		}else{
    			$t=$myOrderFilm['orderCode'];
    		}
    	}
    	return $t;
    }
    /**
     * 座位数
     */
    function seatcount($uid,$featureAppNo){
    	$scount=M('OrderFilm')->field('sum(seatCount) as acount')->where('status=3 and uid='.$uid.' and featureAppNo='.$featureAppNo)->find();
    	return $scount['acount'];
    }
    /**
     * 获取取票密码
     */
    function getCodes(){
    	$orders= M('OrderFilm')->where('status=3 and uid='.UID)->order('startTime desc')->select();
    	foreach ($orders as $k=>$order){
    		$orders[$k]['allprice']=$order['myPrice']*$order['seatCount'];
    		if(!empty($order['printNo'])){
    			$orders[$k]['printNo']=substr($order['printNo'],-8,8);
    		}else{
    			$orders[$k]['printNo']=$order['verifyCode'];
    		}
    	}
    	return $orders;
    }
    /**
     * 等待支付
     */
    function getpaying($status){
    	$order= M('OrderFilm')->where('status='.$status.' and uid='.UID)->find();
    	return $order;
    }

}