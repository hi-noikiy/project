<?php

namespace Admin\Model;
use Think\Model;

class RechargeModel extends Model {


    public function getRechargeList($field = '*', $map = '', $limit = '', $order = 'createTime desc'){


        $userInfo = session('adminUserInfo');
        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
            
        }

        if ($userInfo['cinemaCodeList'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList'] . ',' . $map['cinemaCode']);
            }
            
        }

		 $tops=M('OrderRecharge')->field($field)->limit($limit)->where($map)->order($order)->select();
		 foreach ($tops as $k=>$val){
		 	if($val['status']=='0'){
		 		$tops[$k]['statustr']='充值中或已取消';
		 	}elseif($val['status']=='1'){
		 		$tops[$k]['statustr']='完成';
		 	}elseif($val['status']=='2'){
		 		$tops[$k]['statustr']='异常';
		 	}
		 	if(!empty($val['cardId'])){
		 		$tops[$k]['loginNum']=$val['cardId'];
		 	}else{
		 		$tops[$k]['loginNum']=$val['mobile'];
		 	}
		 }
		 return $tops;
	}


    public function getRechargeCount($field = '*', $map=''){

        $userInfo = session('adminUserInfo');
        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
            
        }

        if ($userInfo['cinemaCodeList'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList'] . ',' . $map['cinemaCode']);
            }
            
        }

        $rechargeCount = M('OrderRecharge')->where($map)->count($field);
        return $rechargeCount;
    }

    public function getRechargeSum($field, $map=''){

        $userInfo = session('adminUserInfo');
        if ($userInfo['cinemaGroup'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaList'] . ',' . $map['cinemaCode']);
            }
            
        }

        if ($userInfo['cinemaCodeList'] != '-1' && !empty($userInfo)) {
            if(empty($map['cinemaCode'])){
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList']);
            }else{
                $map['cinemaCode'] = array('IN', $userInfo['cinemaCodeList'] . ',' . $map['cinemaCode']);
            }
            
        }

        return M('OrderRecharge')->where($map)->sum($field);
    }


    // function find($map=''){
    // 	return M('OrderRecharge')->where($map)->find();
    // }
    // function save($map=''){
    // 	return M('OrderRecharge')->save($map);
    // }
    // function add($map=''){
    // 	return M('OrderRecharge')->add($map);
    // }
    // function delete($map=''){
    // 	return M('OrderRecharge')->where($map)->delete();
    // }
    
}