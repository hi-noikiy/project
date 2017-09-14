<?php

namespace Check\Model;

use Think\Model;

class VoucherModel extends Model {
	
	public function checkVoucher($id,$name){
		$voucher=M('voucherTicket')->find($id);
		if(empty($voucher)){
			$data['status']=1;
			$data['text']='该券码不存在';
		}elseif($voucher['status']=='0'){
			$data['status']=1;
			$data['text']='该券码未出售';
		}elseif($voucher['status']=='3'){
			$data['status']=1;
			$data['text']='该券码已使用';
		}else{
			$orderarr['voucherId']=$id;
			$orderarr['useTime']=time();
			$orderarr['checkuser']=$name;
			$orderarr['status']=3;
			if(M('voucherTicket')->save($orderarr)){
				$data['status']=0;
				$data['text']='使用成功';
			}else{
				$data['status']=1;
				$data['text']='使用失败';
			}
		}
		return $data;
	}

	/**
	 * 获取票券类型列表
	 * @param null;
	 * @return null
	 * @author 宇
	 */
	public function getVoucherTypeList($field = '*', $map = '', $limit = '', $order = '')
	{
		$voucherTypeList = M('VoucherType')->field($field)->limit($limit)->where($map)->order($order)->select();
		//echo M('VoucherType')->_sql();
		return $voucherTypeList;
	}
	function getCount($map){
		return M ( 'voucherTicket' )->where($map)->count();
	}
	
	function getVouchers($map){
    	$voucherTicket=M('voucherTicket')->where($map)->order('voucherId')->limit($start,$limit)->select();
    	foreach ($voucherTicket as $k=>$v){
    		$voucherTicket[$k]['startTime']=date('Y-m-d H:i:s',$v['startTime']);
    		$voucherTicket[$k]['endTime']=date('Y-m-d H:i:s',$v['endTime']);
    		$voucherTicket[$k]['useTime']=date('Y-m-d H:i:s',$v['useTime']);
    		if(!empty($v['belongCinemaCode'])){
    			$cinema[$k]=D('cinema')->find($v['belongCinemaCode']);
    			$voucherTicket[$k]['belongCinemaName']=$cinema[$k]['cinemaName'];
    		}
    		if(!empty($v['cinemaCode'])){
    			$str='';
    			$cinemaCodes=explode(',', $v['cinemaCode']);
    			foreach ($cinemaCodes as $key=>$val){
    				$cinema1[$key]=D('cinema')->find($val);
    				$str.=','.$cinema1[$key]['cinemaName'];
    			}
    			$voucherTicket[$k]['cinemaName']=substr($str, 1);
    		}else{
    			$voucherTicket[$k]['cinemaName']='全部影城';
    		}
    		if($v['status']=='0'){
    			$voucherTicket[$k]['statuStr']='未出售';
    		}elseif($v['status']=='1'){
    			$voucherTicket[$k]['statuStr']='未使用';
    		}else{
    			$voucherTicket[$k]['statuStr']='已使用';
    		}
    	}
    	return $voucherTicket;
	}

	
}