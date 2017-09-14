<?php
// +----------------------------------------------------------------------
// | 首页控制器
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Check\Controller;
use Think\Controller;
class IndexController extends HomeController {
 
	/**
	 * 卖品订单列表
	 */
    public function sale(){
    	$data['code']=I('code');
    	$map['convcode']=$data['code'];
    	$this->assign('data',$data);
    	$count = D ( 'goods' )->getCountCodes($map);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $data);
    		$this->assign('page',$showPage);
    	}
    	$codes=D('goods')->getCodes($map,$startLimit,$this->limit);
    	$this->assign('codes',$codes);
    	$this->display();
    }
/**
 * 卖品订单详情
 */
    function salefrom(){
    	$orderid=I('orderid');
    	$map['orderid']=$orderid;
    	$detail=D('goods')->getOrder($map);
    	$this->assign('detail',$detail);
    	$this->display();
    }
 	/**
     * 卖品订单兑换
     */
    function checkGoodsCode(){
    	$id=I('id');
    	$order=D('orderGoods')->find($id);
    	$data=D('goods')->checkCode($order['convcode'],$this->user['username']);
    	echo json_encode($data);
    }
	
	/**
	 * 卖品订单兑换记录
	 */
    public function salelist(){
    	$data['code']=I('code');
    	$data['gotMan']=I('gotMan');
    	$data['start']=I('start');
    	$data['end']=I('end');
    	if(!empty($data['code'])){
    		$map['convcode']=$data['code'];
    	}
    	if(!empty($data['gotMan'])){
    		$map['gotMan']=$data['gotMan'];
    	}
    	$map['exstatus']=1;
    	$start=$data['start'];
    	$end=$data['end'];
    	if(!empty($start)&&!empty($end)){
    		$map['gotTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60));
    	}elseif(!empty($start)){
    		$map['gotTime']= array(array('egt',strtotime($start)));
    	}elseif(!empty($end)){
    		$map['gotTime']= array(array('elt',strtotime($end)+24*60*60));
    	}

    	$count = D ( 'goods' )->getCountCodes($map);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $data);
    		$this->assign('page',$showPage);
    	}
    	$this->assign('data',$data);
    	$codes=D('goods')->getCodes($map,$startLimit,$this->limit);
    	$men=D('goods')->getOpUser();
    	$this->assign('men',$men);
    	$this->assign('codes',$codes);
    	$this->display();
	}
	/**
	 * 票券订单列表
	 */
	public function voucher(){
		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName');
		$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup);
		$voucherTypeMap['cinemaGroupId'] = array('in', $arrayCinemaGroupId);
		$voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, cinemaGroupId, typeName, typeValue, typeClass', $voucherTypeMap);
		foreach ($voucherTypeList as $key => $value) {
			$newVoucherTypeList[$value['typeId']] = $value;
		}
		$this->voucherTypeList = $newVoucherTypeList;
		$data['voucherNumber']=I('voucherNumber');
    	$map['voucherNumber']=$data['voucherNumber'];
    	$this->assign('data',$data);
    	$codes=D('voucher')->getVouchers($map,$startLimit,$this->limit);
    	$this->assign('codes',$codes);
    	$this->display();
	}
	function checkVoucher(){
		$id=I('id');
		$result=D('voucher')->checkVoucher($id,$this->user['username']);
		echo  json_encode($result);
	}
	/**
	 * 票券订单列表
	 */
	public function voucherlist(){
		$this->cinemaGroup = D('Cinema')->getGroup('id, groupName');
		$arrayCinemaGroupId = array_map('array_shift', $this->cinemaGroup);
		$voucherTypeMap['cinemaGroupId'] = array('in', $arrayCinemaGroupId);
		$voucherTypeList = D('Voucher')->getVoucherTypeList('typeId, cinemaGroupId, typeName, typeValue, typeClass', $voucherTypeMap);
		foreach ($voucherTypeList as $key => $value) {
			$newVoucherTypeList[$value['typeId']] = $value;
		}
		$this->voucherTypeList = $newVoucherTypeList;
		$data['voucherNumber']=I('voucherNumber');
		if(!empty($data['voucherNumber'])){
			$map['voucherNumber']=$data['voucherNumber'];
		}
		$data['start']=I('start');
		$data['end']=I('end');
		$map['status']=3;
		//$map['checkuser']=array('neq','');
		$start=$data['start'];
		$end=$data['end'];
		if(!empty($start)&&!empty($end)){
			$map['useTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60));
		}elseif(!empty($start)){
			$map['useTime']= array(array('egt',strtotime($start)));
		}elseif(!empty($end)){
			$map['useTime']= array(array('elt',strtotime($end)+24*60*60));
		}
		$count = D ( 'voucher' )->getCount($map);
		$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
		$allPage = ceil ( $count / $this->limit);
		$curPage = $this->curPage ( $nowPage, $allPage );
		$startLimit = ($curPage - 1) * $this->limit;
		if ($count > $this->limit) {
			$showPage = $this->getPageList ( $count, $this->limit, $data);
			$this->assign('page',$showPage);
		}
    	$this->assign('pageData',$data);
    	$codes=D('voucher')->getVouchers($map,$startLimit,$this->limit);
    	$this->assign('codes',$codes);
    	$this->display();
	}
	
}