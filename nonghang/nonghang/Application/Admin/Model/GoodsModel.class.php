<?php

namespace Admin\Model;
use Think\Model;

class GoodsModel extends Model {
	
	
	/**
	 * 影院商品列表
	 * @param unknown $map
	 * @param string $order
	 * @param number $start
	 * @param number $limit
	 * @return \Think\mixed
	 */
	function getCinemaGoods($map=array(),$start=0,$limit=9999999,$order='priority desc'){
		$cinemaList=D('cinema')->getCinemasStr();
		$map['_string'] = 'cinemaCode in('.$cinemaList.')';
		$goods=M('goods')->where($map)->order($order)->limit($start,$limit)->select();
		foreach ($goods as $k=>$v){
			$cinema[$k]=D('cinema')->find($v['cinemaCode']);
			$goods[$k]['cinemaName']=$cinema[$k]['cinemaName'];
		}
		return $goods;
	}
	function countGoods($map=array()){
		$cinemaList=D('cinema')->getCinemasStr();
		$map['_string'] = 'cinemaCode in('.$cinemaList.')';
		return M('goods')->where($map)->count();
	}
	

	/**
	 * 影院周边商品列表
	 * @param unknown $map
	 * @param string $order
	 * @param number $start
	 * @param number $limit
	 * @return \Think\mixed
	 */
	function getRoundGoods($map=array(),$start=0,$limit=999999999,$order='priority desc'){
		$sellerList=D('goods')->getCinemaSellers();
		$map['_string'] = 'sellerNo in('.$sellerList.')';
		$goods=M('goodsRound')->where($map)->limit($start,$limit)->order($order)->select();
		foreach ($goods as $k=>$v){
			$seller=M('goodsSeller')->find($v['sellerNo']);
			$cinema=D('cinema')->find($seller['cinemaCode']);
			$goods[$k]['cinemaName']=$cinema['cinemaName'];
			$goods[$k]['sellerName']=$seller['sellerName'];
			unset($seller);
			unset($cinema);
		}
		return $goods;
	}
	function countRound($map=array()){
		$sellerList=D('goods')->getCinemaSellers();
		$map['_string'] = 'sellerNo in('.$sellerList.')';
		return M('goodsRound')->where($map)->count();
	}
	/**
	 * 获取周边物品
	 * @param unknown $map
	 * @param string $order
	 * @param number $start
	 * @param number $limit
	 * @return Ambigous <\Think\mixed, unknown>
	 */
	function getRoundGood($map=array()){
		$goods=M('goodsRound')->where($map)->find();
		$details=explode(';', $goods['detail']);
		foreach ($details as $k=>$v){
			$goods['details'][$k]=explode(',', $v);
		}
		return $goods;
	}
	/**
	 * 获取可以查看的商户
	 */
	function getCinemaSellers(){
		$cinemaList=D('cinema')->getCinemasStr();
		$map['cinemaCode']=array('in',$cinemaList);
		$sellers=M('goodsSeller')->where($map)->select();
		$str='';
		foreach ($sellers as $k=>$v){
			$str.=','.$v['id'];
		}
		return substr($str, 1);
	}
	/**
	 * 商户列表
	 * @param unknown $map
	 * @param string $order
	 * @param number $start
	 * @param number $limit
	 * @return \Think\mixed
	 */
	function getSellers($map=array(),$start=0,$limit=999999999,$order='id'){
		$cinemaList=D('cinema')->getCinemasStr();
		$map['_string']='cinemaCode in ('.$cinemaList.')';
		$sellers=M('goodsSeller')->where($map)->order($order)->limit($start,$limit)->select();
		foreach ($sellers as $k=>$v){
			$cinema[$k]=D('cinema')->find($v['cinemaCode']);
			$sellers[$k]['cinemaName']=$cinema[$k]['cinemaName'];
		}
		return $sellers;
	}
	
	function countSellers($map){
		$cinemaList=D('cinema')->getCinemasStr();
		$map['_string']='cinemaCode in ('.$cinemaList.')';
		$sellers=M('goodsSeller')->where($map)->count();
		return $sellers;
	}
	
	function countGoodsReport($map){
		$cinemaList=D('cinema')->getCinemasStr();
		if(!empty($map['_string'])){
			$map['_string'].=' and cinemaCode in ('.$cinemaList.')';
		}else{
			$map['_string']='cinemaCode in ('.$cinemaList.')';
		}
		return M('orderGoods')->where($map)->count();
	}
	
	
	function getGoodsSum($field, $map=''){
		$cinemaList=D('cinema')->getCinemasStr();
		if(!empty($map['_string'])){
			$map['_string'].=' and cinemaCode in ('.$cinemaList.')';
		}else{
			$map['_string']='cinemaCode in ('.$cinemaList.')';
		}
		$orderSum=M('orderGoods')->where($map)->sum($field);
		return $orderSum;
	}
	/**
	 * 卖品订单
	 * @param unknown $map
	 * @param string $order
	 * @param number $start
	 * @param number $limit
	 */
	function goodsReport($field='*',$map=array(),$start=0,$limit=999999999,$order='downTime desc'){
		$cinemaList=D('cinema')->getCinemasStr();
		if(!empty($map['_string'])){
			$map['_string'].=' and cinemaCode in ('.$cinemaList.')';
		}else{
			$map['_string']='cinemaCode in ('.$cinemaList.')';
		}
		$orders=M('orderGoods')->field($field)->where($map)->order($order)->limit($start,$limit)->select();
		foreach ($orders as $k=>$v){
			if(!empty($v['mobileNum'])){
				$orders[$k]['showName']=$v['mobileNum'];
			}else{
				$orders[$k]['showName']=$v['cardNum'];
			}
			$orders[$k]['voucher']='';
			$voucher[$k]=json_decode($v['otherPayInfo'],true);
			if(!empty($voucher[$k][2])){
				foreach ($voucher[$k][2] as $key=>$val){
					$orders[$k]['voucher']=$val[0];
				}
			}
			if($v['payType']=='weixinpay'){
				$orders[$k]['payType']='微信';
			}elseif($v['payType']=='alipay'){
				$orders[$k]['payType']='支付宝';
			}elseif($v['payType']=='unionpay'){
				$orders[$k]['payType']='银联';
			}elseif($v['payType']=='otherpay'){
				$orders[$k]['payType']='直接支付';
			}
			if($v['status']=='0'){
				$orders[$k]['status']='未支付';
			}elseif($v['status']=='1'){
				$orders[$k]['status']='支付完成';
			}
			if($v['exstatus']=='0'){
				$orders[$k]['exstatus']='未兑换';
			}elseif($v['status']=='1'){
				$orders[$k]['exstatus']='已兑换';
			}
			
			$detail[$k]=M('orderDetail')->where(array('orderid'=>$v['id']))->select();
			$str='';
			$str1='';
			foreach ($detail[$k] as $v){
				$str1.='+'.$v['goodsName'];
				$str.='+'.$v['goodsName'].'x'.$v['number'];
			}
			$orders[$k]['goodsName']=substr($str1, 1);
			$orders[$k]['goodsDetail']=substr($str, 1);
		}
		return $orders;
	}
	
	
	function countRoundReport($map){
		$cinemaList=D('cinema')->getCinemasStr();
		$map['_string']='cinemaCode in ('.$cinemaList.')';
		$count=M('orderRound')->where($map)->count();
		return $count;
	}
	
	
	function getRoundSum($field, $map=''){
		$cinemaList=D('cinema')->getCinemasStr();
		$map['_string']='cinemaCode in ('.$cinemaList.')';
		$orderSum=M('orderRound')->where($map)->sum($field);
		return $orderSum;
	}
	/**
	 * 周边订单
	 * @param unknown $map
	 * @param string $order
	 * @param number $start
	 * @param number $limit
	 */
	function roundReport($field='',$map=array(),$start=0,$limit=999999999,$order='downTime desc'){
		$cinemaList=D('cinema')->getCinemasStr();
		$map['_string']='cinemaCode in ('.$cinemaList.')';
		$orders=M('orderRound')->field($field)->where($map)->order($order)->limit($start,$limit)->select();
		foreach ($orders as $k=>$v){
			if($v['payType']=='weixinpay'){
				$orders[$k]['payType']='微信';
			}elseif($v['payType']=='alipay'){
				$orders[$k]['payType']='支付宝';
			}elseif($v['payType']=='unionpay'){
				$orders[$k]['payType']='银联';
			}
			if($v['status']=='0'){
				$orders[$k]['status']='未支付';
			}elseif($v['status']=='1'){
				$orders[$k]['status']='支付完成';
			}
			if(!empty($v['mobileNum'])){
				$orders[$k]['showName']=$v['mobileNum'];
			}else{
				$orders[$k]['showName']=$v['cardNum'];
			}
		}
		return $orders;
	}
	
	function hasSeller($account){
		$seller=D('goodsSeller')->where(array('account'=>$account))->find();
		if(!empty($seller)){
			return true;
		}else{
			return false;
		}
	}
	
}