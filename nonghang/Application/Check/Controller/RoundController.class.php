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
class RoundController extends InitController {
 
    /**
     * 周边订单列表
     */
    public function group() {
    	
//    	dump($_SESSION);
    	$data['code']=I('code');
    	$map['code']=$data['code'];
    	$count = D ( 'round' )->getCountCodes($map);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $data);
    		$this->assign('page',$showPage);
    	}
    	$this->assign('data',$data);
    	$codes=D('round')->getCodes($map,$startLimit,$this->limit);
    	$this->assign('codes',$codes);
    	$this->display();
    }
    /**
     * 周边订单兑换
     */
    function checkCode(){
    	$id=I('id');
    	$order=D('orderCode')->find($id);
    	$data=D('round')->checkCode($order['code'],$this->user['account']);
    	echo json_encode($data);
    }
    function grouplist(){
    	$data['code']=I('code');
    	$data['start']=I('start');
    	$data['end']=I('end');
    	if(!empty($data['code'])){
    		$map['code']=$data['code'];
    	}
    	$start=$data['start'];
    	$end=$data['end'];
    	if(!empty($start)&&!empty($end)){
    		$map['gotTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60));
    	}elseif(!empty($start)){
    		$map['gotTime']= array(array('egt',strtotime($start)));
    	}elseif(!empty($end)){
    		$map['gotTime']= array(array('elt',strtotime($end)+24*60*60));
    	}
    	$map['status']=1;
    	$count = D ( 'round' )->getCountCodes($map);
    	$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
    	$allPage = ceil ( $count / $this->limit);
    	$curPage = $this->curPage ( $nowPage, $allPage );
    	$startLimit = ($curPage - 1) * $this->limit;
    	if ($count > $this->limit) {
    		$showPage = $this->getPageList ( $count, $this->limit, $data);
    		$this->assign('page',$showPage);
    	}
    	$this->assign('data',$data);
    	$codes=D('round')->getCodes($map,$startLimit,$this->limit);
    	$this->assign('codes',$codes);
    	$this->display();
    }
	
}