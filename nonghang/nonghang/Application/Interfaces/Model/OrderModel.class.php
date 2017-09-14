<?php

namespace Interfaces\Model;
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
    
    function getOtherOrderStatus($order){
    	if($order['status']=='0'){
    		if($order['lockTime']<time()){
    			$orderarr['orderCode']=$order['orderCode'];
    			$orderarr['status']=5;
    			M('OrderFilmOther')->save($orderarr);
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
    		$order['verifyCode']=substr($order['printNo'],-8,8);
    	}
    	$order['statustr']=$str;
    	unset($order['printNo']);
    	return $order;
    }
    
    function saveObj($map=''){
    	return M('OrderFilm')->save($map);
    }
    function addObj($lock,$plan,$seats,$mobile,$cinemaGroupId){
    	$str='';
    	foreach ($seats as $value){
    		$seat=M('cinemaHallSeat')->where(array('seatCode'=>$value['seatNo'],'cinemaCode'=>$plan['cinemaCode']))->find();
    		$str.=';'.$seat['rowNum'].'排'.$seat['columnNum'].'座';
    	}
    	if(strstr($plan['hallName'],'VIP')&&$plan['cinemaCode']=='35013901'){
    		$price=C('OTHER_PRICE_VIP_35013901');
    	}else{
    		$price=C('OTHER_PRICE_'.$plan['copyType'].'_'.$plan['cinemaCode']);
    	}
    	$arr['price']=number_format ( $price, 2 );
    	$arr['orderCode']=$lock['OrderCode'];
    	$arr['featureAppNo']=$plan['featureAppNo'];
    	$arr['seatIntroduce']=substr($str, 1);
    	$arr['cinemaCode']=$plan['cinemaCode'];
    	$arr['cinemaName']=$plan['cinemaName'];
    	$arr['hallNo']=$plan['hallNo'];
    	$arr['seatInfo']=json_encode($seats);
    	$arr['seatCount']=count($seats);
    	$arr['hallName']=$plan['hallName'];
    	$arr['filmNo']=$plan['filmNo'];
    	$arr['filmName']=$plan['filmName'];
    	$arr['mobile']=$mobile;
    	$arr['downTime']=time();
    	$arr['serialNum']=$lock['serialNum'];
    	$arr['lockTime']=strtotime($lock['AutoUnlockDatetime']);
    	$arr['startTime']=$plan['startTime'];
    	$arr['cinemaGroupId']=$cinemaGroupId; //购票系统
    	if(M('OrderFilmOther')->add($arr)){
    		return array('orderCode'=>$arr['orderCode'],'lockTime'=>$arr['lockTime']);
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
    
    
    /**
     * 确认订单
     */
    function setOrderArray($order,$cinema,$logPath){
    	$imove = D('ZMMove');
    	$serviceCharge=0;
    	$money=$order['price'];
    	foreach ($seats as $key=>$value) {
    		$seatInfo[$key]['SeatCode']=$value;
    		$seatInfo[$key]['Price']=$money;  //提交广电价格，总价格
    		$seatInfo[$key]['ServiceCharge']=number_format($serviceCharge,2);  //服务费
    	}
    	$cinema=M('cinema')->find($order['cinemaCode']);
    	$interfaceConfig = json_decode($cinema['interfaceConfig'], true);
    	$submitOrderarr=array(
    			'orderCode'=>$order['orderCode'],
    			'cinemaCode'=>$order['cinemaCode'],
    			'mobilePhone'=>$order['mobile'],
    			'featureAppNo'=>$order['featureAppNo'],
    			'seatInfos'=>$seatInfo,
    			'serialNum'=>$order['serialNum'],
    			'amount'=>$money*count(json_decode($order['seatInfo'],true)),
    			'printpassword'=>create_uuid(),
    			'payType'=>$interfaceConfig['traceTypeNo'],
    	);
    	wlog('[订单确认]'.arrayeval($submitOrderarr), $logPath);
    	$i=1;
    	while($i<6){
    		$a=$imove->submitOrder($submitOrderarr);//确认订单交易
    		if($a['ResultCode']=='0'){
    			wlog('[第'.$i.'次订单确认成功]'.arrayeval($a), $logPath);
    			break;
    		}else{
    			wlog('[第'.$i.'次订单确认失败]'.arrayeval($a), $logPath);
    		}
    		$i++;
    	}
    	return $a;
    }
    /**
     * 查询订单状态
     */
    function getOrderStatus($order,$logPath){
    	$imove = D('ZMMove');
    	$queryOrderStatusarr=array(
    			'cinemaCode'=>$order['cinemaCode'],
    			'serialNum'=>$order['serialNum'],
    	);
    	$i=1;
    	while($i<6){
    		$b=$imove->queryOrderStatus($queryOrderStatusarr);//查状态
    		if($b['ResultCode']=='0' && $b['OrderStatus'] == 0){
    			wlog('[订单成功]'.arrayeval($b), $logPath);
    			$orderarr['orderCode']=$order['orderCode'];
    			$orderarr['status']=3;//订单完成
    			$orderarr['orderTime']=time();
    			M('orderFilmOther')->save($orderarr);
    			break;
    		}
    		$i++;
    	}
    	wlog('[查询订单状态]'.json_encode($queryOrderStatusarr) . json_encode($b), $logPath);
    	return $b;
    }
    
    /**
     * 订单完成修改订单状态并发送短信
     */
    function orderToSms($a,$order,$cinema,$logPath,$amount){
    	$orderarr['amount']=$amount;
    	$orderid=$orderarr['orderCode']=$order['orderCode'];
    	if($cinema['interfaceType']=='hfh'){
    		$orderarr['orderCode']=$a['orderNo'];
    		$orderarr['otherorderCode']=$orderid;
    	}
    	$orderarr['printNo']=$a['PrintNo'];
    	$orderarr['submitPrice']=$order['submitPrice'];
    	$orderarr['status']=3;//订单完成
    	$orderarr['orderTime']=time();
    	$orderarr['verifyCode']=$a['VerifyCode'];
    	wlog('orderarr:'.arrayeval($orderarr),$logPath);
    	D('Order')->saveObj($orderarr,array('orderCode'=>$orderid));
    	//smsajax($orderarr['orderCode'],'',$logPath);
    
    }

}