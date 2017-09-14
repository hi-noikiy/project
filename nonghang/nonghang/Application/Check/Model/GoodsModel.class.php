<?php
// +----------------------------------------------------------------------
// | 用户模型
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Check\Model;
use Think\Model;

class GoodsModel extends Model {

   /**
    * 获取兑换码数据
    * @param unknown $map
    * @param number $start
    * @param number $limit
    * @return string
    */
    function getCodes($map,$start=0,$limit=9999){
    	$cinemaList=D('cinema')->getCinemasStr();
		$map['cinemaCode']=array('in',$cinemaList);
    	$map['status']=1;
    	$codes=M('orderGoods')->where($map)->order('id')->limit($start,$limit)->select();
    	foreach ($codes as $k=>$v){
    		$details=D('goods')->getOrder(array('orderid'=>$v['id']));
    		$name='';
    		foreach ($details as $val){
    			$name.='+'.$val['goodsName'];
    		}
    		$codes[$k]['goodsName']=substr($name, 1);
    		$codes[$k]['price']=$v['price'];
    		$codes[$k]['cinemaName']=$v['cinemaName'];
    		$codes[$k]['orderTime']=date('Y-m-d H:i',$v['orderTime']);
    		if($v['exstatus']=='1'){
    			$codes[$k]['statustr']='已兑换';
    		}else{
    			$codes[$k]['statustr']='未兑换';
    		}
    	}
    	return $codes;
    }
    
    function getCountCodes($map){
    	$cinemaList=D('cinema')->getCinemasStr();
    	$map['cinemaCode']=array('in',$cinemaList);
    	$map['status']=1;
    	$codes=M('orderGoods')->where($map)->count();
    	return $codes;
    }
    
    
    function getOrder($map){
    	$detail=D('orderDetail')->where($map)->select();
    	foreach($detail as $k=>$v){
    		$detail[$k]['price']=$v['number']*$v['price'];
    	}
    	return $detail;
    }
    
    function checkCode($code,$adminName){
    	$order=M('orderGoods')->where(array('convcode'=>$code))->find();
    	if(!empty($order)&&$order['exstatus']=='1'){
    		$data['status']=0;
    		$data['text']='该券码已兑换';
    	}else{
    		$orderarr['id']=$order['id'];
    		$orderarr['exstatus']=1;
    		$orderarr['gotTime']=time();
    		$orderarr['gotMan']=$adminName;
    		if(M('orderGoods')->save($orderarr)){
    			$data['status']=1;
    			$data['text']='兑换成功';
    		}else{
    			$data['status']=0;
    			$data['text']='兑换失败';
    		}
    		
    	}
    	return $data;
    }
    /**
     * 获取操作人员
     */
    function getOpUser(){
    	$cinemaList=D('cinema')->getCinemasStr();
    	$map['cinemaCode']=array('in',$cinemaList);
    	$map['exstatus']=1;
    	$men=M('orderGoods')->field('distinct(gotMan) gotMan')->where($map)->select();
		return $men;
    }
    
}