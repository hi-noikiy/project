<?php

namespace Web\Model;
use Think\Model;

class GoodsModel extends Model {
	
	/**
	 * Enter description here ...
	 * @param unknown_type $map
	 * @param unknown_type $start
	 * @param unknown_type $limit
	 * @param unknown_type $order
	 * @return unknown
	 */
	function getCinemaGoods($map=array(),$start=0,$limit=999999999,$order='priority desc'){
		$map['visible']=1;
		$goods=M('goods')->where($map)->order($order)->limit($start,$limit)->select();
		foreach ($goods as $k=>$v){	
			if(file_exists('./Uploads/'.$v['goodsImg'])&&$v['goodsImg']){
				$goods[$k]['goodsImg']=C('IMG_URL').'Uploads/'.$v['goodsImg'];			
			}else{
				$goods[$k]['goodsImg']=C('GOODS_IMG_URL');
			}
		}
		return $goods;
	}
	
	/**
	 * 
	 * @param unknown $cinemaCode
	 * @param string $order
	 * @param number $start
	 * @param number $limit
	 * @return string
	 */
	function getRoundGoods($cinemaCode,$start=0,$limit=999999999,$order='priority desc'){
		$sellerList=D('goods')->getCinemaSellers(array('cinemaCode'=>$cinemaCode));
		$map['_string'] = 'sellerNo in('.$sellerList.')';
		$map['visible']=1;
		$goods=M('goodsRound')->field('id,goodsName,introduce,detail,price,goodsImg,saleNumber,sellerNo')->where($map)->order($order)->limit($start,$limit)->select();
		foreach ($goods as $k=>$v){
			$goods[$k]['goodsImg']=C('IMG_URL').'Uploads/'.$v['goodsImg'];
			$detailss=explode(';', $v['detail']);
			$seller=M('goodsSeller')->find($v['sellerNo']);
			$goods[$k]['sellerName']=$seller['sellerName'];
			$price=0;
			foreach ($detailss as $val){
				$details=explode(',', $val);
				$price+=$details[1]*$details[2];
			}
			$goods[$k]['showPrice']=$price;
			unset($goods[$k]['detail']);
			unset($goods[$k]['sellerNo']);
		}
		return $goods;
	}
	/**
	 * 获取可以查看的商户
	 */
	function getCinemaSellers($map){
		$sellers=M('goodsSeller')->where($map)->select();
		$str='';
		foreach ($sellers as $k=>$v){
			$str.=','.$v['id'];
		}
		return substr($str, 1);
	}
	
	/**
	 * 生成影院卖品订单
	 */
	function setCinemaOrder($data,$goods){
		$showPrice=$price=0;
		$goodss=explode(',', $goods);
		foreach ($goodss as $k=>$v){
			$good=explode(':', $v);
			$mygoods=D('goods')->find($good[0]);
			if(empty($mygoods)){
				die('物品'.$good[0].'信息出错');				
				wlog('物品'.$good[0].'信息出错', 'order');
			}
			$detail[$k]['goodsId']=$mygoods['id'];
			$detail[$k]['goodsName']=$mygoods['goodsName'];
			$detail[$k]['goodsImg']=$mygoods['goodsImg'];
			$detail[$k]['showPrice']=$mygoods['showPrice'];
			$detail[$k]['price']=$mygoods['price'];
			$detail[$k]['detail']=$mygoods['detail'];
			$detail[$k]['number']=$good[1];
			$detail[$k]['cinemaCode']=$data['cinemaCode']=$mygoods['cinemaCode'];
			$showPrice+=$mygoods['showPrice']*$good[1];
			$price+=$mygoods['price']*$good[1];
			unset($good);
			unset($mygoods);
		}
		$data['showPrice']=$showPrice;
		$data['price']=$price;
		$data['downTime']=time();
		$cinema=D('cinema')->find($data['cinemaCode']);
		$data['cinemaName']=$cinema['shortName'];
		$data['way']='www';
		$orderid=D('orderGoods')->add($data);
		if($orderid){
			foreach ($detail as $k=>$v){
				$v['orderid']=$orderid;
				D('orderDetail')->add($v);
			}
		}
		return $orderid;
	}
	
	/**
	 * 生成影院卖品订单
	 */
	function setRoundOrder($data){
		$goods=D('goodsRound')->find($data['goodsId']);
		$data['goodsName']=$goods['goodsName'];
		$data['goodsImg']=$goods['goodsImg'];
		$data['goodsDetail']=$goods['detail'];
		$details=explode(';', $goods['detail']);
		$money=0;
		foreach ($details as $v){
			$mygoods=explode(',', $v);
			$money+=$mygoods[2]*$mygoods[1];
		}
		$data['showPrice']=$money;
		$data['price']=$goods['price'];
		$data['otherpay']=$goods['price']*$data['number'];
		$data['downTime']=time();
		$data['orderTime']=time();
		$seller=D('goodsSeller')->find($goods['sellerNo']);
		$data['sellerNo']=$seller['id'];
		$data['sellerName']=$seller['sellerName'];
		$cinema=D('cinema')->find($seller['cinemaCode']);
		$data['cinemaCode']=$cinema['cinemaCode'];
		$data['cinemaName']=$cinema['cinemaName'];
		$data['way']='wap';
		$orderid=D('orderRound')->add($data);
		return $orderid;
	}
	
	/**
	 * 查询周边订单状态
	 */
	function getRoundStatus($orderid){
		$order=D('orderRound')->find($orderid);
		if($order['status']=='1'){
			$order['codes']=M('orderCode')->field('code,status')->where(array('orderid'=>$orderid))->select();
		}
		return $order;
	}
	
	/**
	 * 获取卖品订单列表
	 * @param unknown $map
	 */
	function getMyGoods($uid,$start=0,$limit=99999){
		$map['uid']=$uid;
		$map['status']=1;
		$map['visible']=0;
		$orders=D('orderGoods')->where($map)->order('orderTime desc')->limit($start,$limit)->select();
		foreach ($orders as $k=>$v){
			$num=0;
			$details=D('orderDetail')->where(array('orderid'=>$v['id']))->select();
			foreach ($details as $key=>$val){
				$num+=$val['number'];
				$details[$key]['goodsImg']=C('IMG_URL').'Uploads/'.$val['goodsImg'];
			}
			$orders[$k]['details']=$details;
			$orders[$k]['number']=$num;
			$orders[$k]['ctime']=date('Y-m-d H:i:s',$v['orderTime']);
		}
		return $orders;
	}
	
	/**
	 * 获取我的周边订单列表
	 * @param unknown $map
	 */
	function getMyRound($uid,$start=0,$limit=99999){
		$map['uid']=$uid;
		$map['status']=1;
		$map['visible']=0;
		$orders=D('orderRound')->where($map)->order('orderTime desc')->limit($start,$limit)->select();
		foreach ($orders as $k=>$v){
			$orders[$k]['details']=D('orderCode')->field('code,status')->where(array('orderid'=>$v['id']))->select();
			$orders[$k]['goodsImg']=C('IMG_URL').'Uploads/'.$v['goodsImg'];
			$orders[$k]['ctime']=date('Y-m-d H:i:s',$v['orderTime']);
		}
		return $orders;
	}

	/**
	 * 获取卖品订单信息
	 * @param unknown $map
	 */
	public function getGoodsOrderInfo($field, $map)
	{
		$orderInfo = D('OrderGoods')->field($field)->where($map)->find();
		return $orderInfo;
	}

	/**
	 * 更新卖品订单
	 * @param unknown $map
	 */
	public function updateGoodsOrder($data, $map)
	{
		$mod = M('OrderGoods');
        if (!$mod->create($data)){
            return false;
        }else{
            $id = $mod->where($map)->data($data)->save();
            if($id){
                return $id;
            }else{
                return false;
            }
        }
	}
	
   public function order_goods_getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
   		if(isset($dataarray['uid'])) {    
        	$wherearray['uid']=array('eq',$dataarray['uid']);            
        }
   		if(isset($dataarray['status'])) {    
        	$wherearray['status']=array('eq',$dataarray['status']);            
        }
    	if(isset($dataarray['cinemaCode'])) {    
        	$wherearray['cinemaCode']=array('eq',$dataarray['cinemaCode']);            
        }
   		if(isset($dataarray['visible'])) {    
        	$wherearray['visible']=array('eq',$dataarray['visible']);            
        }
       $database = M('order_goods');
     	//条件处理
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
     	$groupByField='id';
    	if(isset($dataarray['groupByField'])) {  
    		$groupByField=$dataarray['groupByField'];  	         
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
            case '5'://分组排列查找
                $info=$database->where($wherearray)->field($getField)->group($groupByField)->select();
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
    
 public function order_detail_getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
   		if(isset($dataarray['orderid'])) {    
        	$wherearray['orderid']=array('eq',$dataarray['orderid']);            
        }
    	if(isset($dataarray['cinemaCode'])) {    
        	$wherearray['cinemaCode']=array('eq',$dataarray['cinemaCode']);            
        }
       $database = M('order_detail');
     	//条件处理
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
     	$groupByField='id';
    	if(isset($dataarray['groupByField'])) {  
    		$groupByField=$dataarray['groupByField'];  	         
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
            case '5'://分组排列查找
                $info=$database->where($wherearray)->field($getField)->group($groupByField)->select();
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
    
    public function order_goods_update($dataarray){
      	$wherearray['id']=array('EQ',$dataarray['id']);
        $userbase =M('order_goods');
        $ret=$userbase->where($wherearray)->save($dataarray);
        if($ret) {
            return 1;
        }else {
            return false;
        }
    	
    	
    	
    
    
    
    
    }
    
	
	
	
	
	
	
	

}