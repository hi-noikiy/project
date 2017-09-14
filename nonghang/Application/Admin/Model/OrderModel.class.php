<?php

namespace Admin\Model;
use Think\Model;

class OrderModel extends Model {
	/**
	 * 获取订单状态
	 * @param unknown $orderCode
	 * @return string
	 */
	function queryOrderStatus($orderCode){
		$order=M('orderFilm')->find($orderCode);
		$queryOrderStatusarr=array(
    			'cinemaCode'=>$order['cinemaCode'],
    			'orderCode'=>$order['orderCode'],
    			'serialNum'=>$order['serialNum'],
    			'ticketCount'=>$order['seatCount'],
    	);
		$b=D('ZMMove')->queryOrderStatus($queryOrderStatusarr);   //查状态
		
        if($b['ResultCode']=='0'){
			if($b['OrderStatus']=='0'){
				$b['OrderStr']='订单成功';
			}elseif($b['OrderStatus']=='1'){
				$b['OrderStr']='订单失败';
			}elseif($b['OrderStatus']=='2'){
				$b['OrderStr']='已退票';
			}
			if(!empty($b['printNo'])){
				$b['PrintNo']=substr($b['PrintNo'],-8,8);
			}else{
				$b['PrintNo']=!empty($b['VerifyCode'])?$b['VerifyCode']:0;
			}
		}else{
			$b['OrderStr']='查询失败';
		}
		return $b;
	}
	
	
	function getOrderList($field = '*', $map = '', $limit = '', $order = 'lockTime desc'){


        $userInfo = session('adminUserInfo');
        if (!empty($userInfo)&&$userInfo['cinemaGroup'] != '-1'  ) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }
        }

        if ( !empty($userInfo)&&$userInfo['cinemaCodeList'] != '-1' ) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList']);
            }
        }


		 $orders= M('OrderFilm')->field($field)->limit($limit)->where($map)->order($order)->select();
		// echo M('OrderFilm')->_sql();
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


    public function getOrderCount($field, $map=''){

        $userInfo = session('adminUserInfo');
        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }
        }

        if ($userInfo['cinemaCodeList'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList']);
            }
        }
        return M('OrderFilm')->where($map)->count($field);
    }


    public function getOtherOrderCount($field, $map='')
    {
        return M('OrderFilmOther')->where($map)->count($field);
    }
    
    public function getOtherOrderList($field = '*', $map = '', $limit = '', $order = 'orderTime desc')
    {
        $orders = M('OrderFilmOther')->field($field)->limit($limit)->where($map)->order($order)->select();
        return $orders;
    }


    function getOrderSum($field, $map=''){

        $userInfo = session('adminUserInfo');
        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }
        }

        if ($userInfo['cinemaCodeList'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList']);
            }
        }
        $orderSum = M('OrderFilm')->where($map)->sum($field);
        return $orderSum;
    }



    function findObj($map=''){
    	$order=M('OrderFilm')->where($map)->find();
    	if($order['status']=='0'){
    		$str='等待支付';
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
    	$payinfos=json_decode($order['otherPayInfo'],true);
    	foreach ($payinfos as $value){
    		foreach ($value as $k=>$v){
    			if(is_numeric($k)){
    				$order['vstr']=implode(',', $v);
    			}
    		}
    	}
		if(!empty($order['printNo'])){
    		$order['printNo']=substr($order['printNo'],-8,8);
    	}else{
    		$order['printNo']=$order['verifyCode'];
    	}
    	$order['status']=$str;
    	return $order;
    }
    function save($map=''){
    	return M('OrderFilm')->save($map);
    }
    function add($map=''){
    	return M('OrderFilm')->add($map);
    }
    function delete($map=''){
    	return M('OrderFilm')->where($map)->delete();
    }
    
    /**
     * 退款
     */
    function backMoney($orderid){
    	$order=M('orderFilm')->find($orderid);
    	if(empty($order)){
    		die('订单不存在');
    	}
    	if($order['status']=='9'){
    		die('订单状态不可退');
    	}
    	$cinema=D('cinema')->find($order['cinemaCode']);
    	$order['link']=$cinema['link'];
    	$user=M('member')->find($order['uid']);
    	$iuser = D('ZMUser');
    	$imove = D('ZMMove');
    	if(!empty($order['cardId'])){
    		if($cinema['interfaceType']!='hfh'){
    			if($order['payType']=='account'){
    				$memberTransactionCancelarr=array(
    						'cinemaCode'=>$user['businessCode'],
    						'oldTransactionNo'=>$order['transactionNo'],
    						'transactionNo'=>create_uuid(),
    						'channelType'=>'退款',
    						'cardId'=>$order['cardId'],
    						'passWord'=>decty($user['pword']),
    						'traceNo'=>$order['cxTransactionNo'],
    						'tracePrice'=>0,
    						'price'=>number_format($order['amount'],2,'.',''),
    				);
    				if($user['businessCode']!=$order['cinemaCode']){
    					$memberTransactionCancelarr['sellcinemaCode']=$order['cinemaCode'];
    				}
    				wlog('[开始退款]'.arrayeval($memberTransactionCancelarr), 'order');
    				$i=1;
    				while($i<6){
    					$c=$iuser->memberTransactionCancel($memberTransactionCancelarr);//退款
    					if($c['ResultCode']=='0'){
    						wlog('[第'.$i.'次退款成功]'.arrayeval($c), 'order');
    						break;
    					}else{
    						wlog('[第'.$i.'次退款失败]'.arrayeval($c), 'order');
    					}
    					$i++;
    				}
    			}else{
    				$c=getBackOrder($order,$user,$cinema,$order['amount'],'order');
    			}
    			if($c['ResultCode']=='0'){
    				$loginResult = $iuser->verifyMemberLogin(array('cinemaCode'=>$user['businessCode'],'loginNum'=>$user['cardNum'],'password'=>decty($user['pword']),'link'=>$cinema['link'],'cinemaName'=>$cinema['cinemaName']));
    				if($loginResult['ResultCode'] == 0){//登录成功
    					D('member')->loginMember($loginResult,$user['businessCode'],decty($user['pword']));
    				}
    			}
    		}else{
    			$c['ResultCode']='0';
    		}
    	}else{
    		M('member')->where(array('id'=>$order['uid']))->setInc('mmoney',$order['amount']);
    		$c['ResultCode']='0';
    	}
    	$orderarr['orderCode']=$order['orderCode'];
    	if($c['ResultCode']=='0'){
    		$app=M('cinemaGroup')->find($user['cinemaGroupId']);
    		M('member')->where(array('id'=>$order['uid']))->setInc('integral',-$order['amount']*$app['getProportion']);
    		$integral=$order['integral'];
    		backUserMoney($orderid,$user,$integral,'order');
    		wlog('[退款成功]'.$order['orderCode'], 'order');
    		$logarr=array(
    				'uid'=>$order['uid'],
    				'type'=>1,
    				'money'=>$order['amount'],
    				'createTime'=>time(),
    				'cinemaCode'=>$order['cinemaCode'],
    				'filmName'=>$order['filmName'],
    				'integral' =>-$integral,
    				'incIntegral'=>-$order['amount']*$app['getProportion'],
    				'cinemaGroupId'=>$user['cinemaGroupId'],
    		);
    		if(!empty($order['cardId'])){
    			$logarr['cardId']=$order['cardId'];
    		}else{
    			$logarr['mobile']=$order['mobileNum'];
    		}
    		M('Money_log')->add($logarr);
    		$orderarr['status']=9;
    		M('OrderFilm')->save($orderarr);
    	}	else{
    		$orderarr['status']=10;
    		M('OrderFilm')->save($orderarr);
    	}
    	return $c;
    }
    /**
     * 取消订单并退款
     * @param unknown $order
     * @return string
     */
    public function cancelOrder($orderid){
    	$order=M('orderFilm')->find($orderid);
    	$cinema=D('cinema')->find($order['cinemaCode']);
    	$order['link']=$cinema['link'];
    	$user=M('member')->find($order['uid']);
    	$iuser = D('ZMUser');
    	$imove = D('ZMMove');
    	if(($order['startTime']<time()+30*60)||$order['status']!='3'){
    		$result['ResultCode']=1;
    		$result['Message']='订单不可退';
    	}else{
            $order['desc'] = '测试，后台退票';
    		$result=D('ZMMove')->backTicket($order);
    		if($result['ResultCode']=='0'){
    			if(!empty($order['cardId'])){
    					if($cinema['interfaceType']!='hfh'){
    						if($order['payType']=='account'){
    							$memberTransactionCancelarr=array(
    									'cinemaCode'=>$user['businessCode'],
    									'oldTransactionNo'=>$order['transactionNo'],
    									'transactionNo'=>create_uuid(),
    									'channelType'=>'退款',
    									'cardId'=>$order['cardId'],
    									'passWord'=>decty($user['pword']),
    									'traceNo'=>$order['cxTransactionNo'],
    									'tracePrice'=>0,
    									'price'=>number_format($order['amount'],2,'.',''),
    							);
    							if($user['businessCode']!=$order['cinemaCode']){
    								$memberTransactionCancelarr['sellcinemaCode']=$order['cinemaCode'];
    							}
    							wlog('[开始退款]'.arrayeval($memberTransactionCancelarr), 'order');
    							$i=1;
    							while($i<6){
    								$c=$iuser->memberTransactionCancel($memberTransactionCancelarr);//退款
    								if($c['ResultCode']=='0'){
    									wlog('[第'.$i.'次退款成功]'.arrayeval($c), 'order');
    									break;
    								}else{
    									wlog('[第'.$i.'次退款失败]'.arrayeval($c), 'order');
    								}
    								$i++;
    							}
    						}else{
    							$c=getBackOrder($order,$user,$cinema,$order['amount'],'order');
    						}
    						if($c['ResultCode']=='0'){
    							$loginResult = $iuser->verifyMemberLogin(array('cinemaCode'=>$user['businessCode'],'loginNum'=>$user['cardNum'],'password'=>decty($user['pword']),'link'=>$cinema['link'],'cinemaName'=>$cinema['cinemaName']));
    							if($loginResult['ResultCode'] == 0){//登录成功
    								D('member')->loginMember($loginResult,$user['businessCode'],decty($user['pword']));
    							}
    						}
    					}else{
    						$c['ResultCode']='0';
    					}
    				}else{
    					M('member')->where(array('id'=>$order['uid']))->setInc('mmoney',$order['amount']);
    					$c['ResultCode']='0';
    				}
    		}
    		if($result['ResultCode']=='0'){
    			$orderarr['orderCode']=$order['orderCode'];
    			if($c['ResultCode']=='0'){
    				$app=M('cinemaGroup')->find($user['cinemaGroupId']);
    				M('member')->where(array('id'=>$order['uid'],'cinemaGroupId'=>$order['cinemaGroupId']))->setInc('integral',-$order['amount']*$app['getProportion']);
    				$integral=$order['integral'];
    				backUserMoney($orderid,$user,$integral,'order');
    				wlog('[退款成功]'.$order['orderCode'], 'order');
    				$logarr=array(
    						'uid'=>$order['uid'],
    						'type'=>1,
    						'money'=>$order['amount'],
    						'createTime'=>time(),
    						'cinemaCode'=>$order['cinemaCode'],
    						'filmName'=>$order['filmName'],
    						'integral' =>-$integral,
    						'incIntegral'=>-$order['amount']*$app['getProportion'],
    						'cinemaGroupId'=>$user['cinemaGroupId'],
    				);
    				if(!empty($order['cardId'])){
    					$logarr['cardId']=$order['cardId'];
    				}else{
    					$logarr['mobile']=$order['mobileNum'];
    				}
    				M('Money_log')->add($logarr);
    				$orderarr['status']=9;
    				M('OrderFilm')->save($orderarr);
    			}	else{
    				$orderarr['status']=10;
    				M('OrderFilm')->save($orderarr);
    			}
    		}
    	}
    	wlog('[取消状态]'.arrayeval($result), 'order');
    	return $result;
    }
    /**
     * 单独退票
     */
    function backticket($orderid){
    	$order=M('orderFilm')->find($orderid);
    	$imove = D('ZMMove');
    	if(($order['startTime']<time()+30*60)||$order['status']!='3'){
    		$result['ResultCode']=1;
    		$result['Message']='订单不可退';
    	}else{
    		$result=D('ZMMove')->backTicket($order);
    	}
    	return $result;
    }
    
    /**
     * 手动充值
     */
    function backin($cardId,$money){
    	$logPath='recharge';
    	$user=M('member')->where(array('cardNum'=>$cardId))->find();
    	$memberChargearr = array (
    			'cinemaCode' => $user['businessCode'],
    			'loginNum' => $user['cardNum'],
    			'chargeType' => 0, // 回充
    			'channelType' => '购票失败回充金额',
    			'payment' =>  'Ali',
    			'passWord' => decty($user['pword']),
    			'transactionNo' => create_uuid(),
    			'chargeAmount' => number_format ( $money, 2 ,'.','' )
    	); // 金额
    	$iuser = D('ZMUser');
    	$i=1;
    	while ( $i < 6 ) {
    		$b = $iuser->memberCharge ( $memberChargearr ); // 充值
    		if ($b ['ResultCode'] == '0') {
    			wlog ( '第' . $i . '次充值成功' . arrayeval ( $b ), $logPath );
    			break;
    		} else {
    			wlog ( '第' . $i . '次充值失败' . arrayeval ( $b ), $logPath );
    		}
    		$i ++;
    	}
    	if ($b ['ResultCode'] == '0') {
    		if(!empty($user['cardNum'])){
    			wlog ( '更新用户前金额:' . ($user['basicBalance']+$user ['donateBalance']) , $logPath );
    			$loginResult = $iuser->verifyMemberLogin(array('cinemaCode'=>$user['businessCode'],'loginNum'=>$user['cardNum'],'password'=>decty($user['pword'])));
    			if($loginResult['ResultCode'] == 0){//登录成功
    				wlog ( '用户当前数据:' . arrayeval ( $loginResult ), $logPath );
    				$result=D('member')->loginMember($loginResult,$user['businessCode'],decty($user['pword']),$user['cinemaGroupId']);
    				$cxUserInfo=$result['user'];
    				wlog ( '更新用户数据:' . arrayeval ( $result ), $logPath );
    			}
    		}
    	} else {
    		wlog ( '充值失败' .arrayeval ( $b ), $logPath );
    	}
    	return $b;
    }
}