<?php
/**
 * @author 王涛
 */

namespace Refresh\Model;
use Think\Model;

class OrderModel extends Model {
	
    public function orderRefresh(){
    	wlog(date('Y-m-d H:i:s').'开始执行orderRefresh：', 'order');
    	$orders=M('OrderFilm')->where('startTime>='.time()." and (status=3 or status=7)")->order('startTime desc')->select();
    	wlog(M('OrderFilm')->_sql(), 'order');
    	$d=$a=$e=$f=0;
    	$iuser = D('ZMUser');
		$imove = D('ZMMove');
    	foreach ($orders as $order) {
    		$orderarr['orderCode']=$order['orderCode'];
    		$queryOrderStatusarr=array(
    				'cinemaCode'=>$order['cinemaCode'],
    				'orderCode'=>$order['orderCode'],
    				'serialNum'=>$order['serialNum'],
    				'ticketCount'=>$order['seatCount'],
    		);
    		wlog('[查询订单状态]'.arrayeval($queryOrderStatusarr), 'order');
    		$i=1;
    		while($i<6){
    			$b=$imove->queryOrderStatus($queryOrderStatusarr);//查状态
    			if($b['ResultCode']=='0'){
    				break;
    			}
    			$i++;
    		}
    		wlog('[订单状态]'.arrayeval($b), 'order');
    		if($b['ResultCode']=='0'){
    			if($b['OrderStatus']=='2'){
    				$cinema=M('cinema')->find($order['cinemaCode']);
    				if(!empty($order['cardId'])){
    					if($cinema['interfaceType']!='hfh'){
    						$user=M('Member')->find($order['uid']);
    						$memberTransactionCancelarr=array(
    								'cinemaCode'=>$user['businessCode'],
    								'oldTransactionNo'=>$order['transactionNo'],
    								'transactionNo'=>create_uuid(),
    								'channelType'=>'退款',
    								'cardId'=>$order['cardId'],
    								'passWord'=>decty($user['pword']),
    								'traceNo'=>$e['cxTransactionNo'],
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
    								$loginResult = $iuser->verifyMemberLogin($user['businessCode'],$user['cardNum'],decty($user['pword']));
    								if($loginResult['ResultCode'] == 0){//登录成功
    									D('member')->loginMember($loginResult,$user['businessCode'],decty($user['pword']));
    								}
    								break;
    							}else{
    								wlog('[第'.$i.'次退款失败]'.arrayeval($c), 'order');
    							}
    							$i++;
    						}
    					}else{
    						$c['ResultCode']='0';
    					}
    				}else{
    					M('member')->where(array('id'=>$order['uid']))->setInc('mmoney',$order['amount']);
    					$c['ResultCode']='0';
    				}
    				if($c['ResultCode']=='0'){
    					$app=M('appAccount')->find($user['appid']);
    					M('member')->where(array('id'=>$order['uid']))->setInc('integral',-$order['amount']*$app['getProportion']);
    					$logarr=array(
    							'uid'=>$order['uid'],
    							'type'=>1,
    							'money'=>$order['amount'],
    							'createTime'=>time(),
    							'cinemaCode'=>$order['cinemaCode'],
    							'filmName'=>$order['filmName']
    					);
    					if(!empty($order['cardId'])){
    						$logarr['cardId']=$order['cardId'];
    					}else{
    						$logarr['mobile']=$order['mobileNum'];
    					}
    					M('Money_log')->add($logarr);
    					$orderarr['status']=9;
    					M('OrderFilm')->save($orderarr);
    					$e++;
    				}	else{
    					$orderarr['status']=10;
    					M('OrderFilm')->save($orderarr);
    					$f++;
    				}
    				$d++;
    			}
    		}
    		$a++;
    	}
    	$data['a']=$a;
    	$data['d']=$d;
    	$data['e']=$e;
    	$data['f']=$f;
    	return $data;
    }
}