<?php

namespace Web\Model;
use Think\Model;

class RechargeModel extends Model {
	function getList($map=array(),$order='createTime desc'){
		 $tops=M('OrderRecharge')->where($map)->order($order)->select();
		 foreach ($tops as $k=>$val){
		 	if($val['status']=='0'){
		 		$tops[$k]['statustr']='充值中或已取消';
		 	}elseif($val['status']=='1'){
		 		$tops[$k]['statustr']='完成';
		 	}elseif($val['status']=='2'){
		 		$tops[$k]['statustr']='异常';
		 	}
		 }
		 return $tops;
	}
    function findObj($map=array(),$order='createTime desc'){
    	$top=M('orderRecharge')->where($map)->order($order)->find();
    	return $top;
    }
    function saveObj($map=''){
    	return M('OrderRecharge')->save($map);
    }
}