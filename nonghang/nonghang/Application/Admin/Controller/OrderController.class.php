<?php
/**
 * 首页
 */

namespace Admin\Controller;
use Think\Controller;
class OrderController extends AdminController {
	
	/**
	 * 订单列表
	 */
    public function orderlist(){
    	$pageData['cardId']=I('cardId');
    	$pageData['mobile']=I('mobile');
    	$pageData['payType']=I('payType');
    	$start=I('start');
    	$end=I('end');
    	$pageData['start']=$start;
    	$pageData['end']=$end;
    	$start1=I('start1');
    	$end1=I('end1');
    	$pageData['start1']=$start1;
    	$pageData['end1']=$end1;
    	if(!empty($start)&&!empty($end)){
    		$map['downTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60));
    	}elseif(!empty($start)){
    		$map['downTime']= array(array('egt',strtotime($start)));
    	}elseif(!empty($end)){
    		$map['downTime']= array(array('elt',strtotime($end)+24*60*60));
    	}
    	if(!empty($start1)&&!empty($end1)){
    		$map['startTime']= array(array('egt',strtotime($start1)),array('elt',strtotime($end1)+24*60*60));
    	}elseif(!empty($start1)){
    		$map['startTime']= array(array('egt',strtotime($start1)));
    	}elseif(!empty($end1)){
    		$map['startTime']= array(array('elt',strtotime($end1)+24*60*60));
    	}
    	if(!empty($pageData['cardId'])){
    		$map['_string']='cardId="'.$pageData['cardId'].'" or mobileNum="'.$pageData['cardId'].'"';
    	}
    	if(!empty($pageData['mobile'])){
    		$map['mobile']=$pageData['mobile'];
    	}
    	$pageData['orderCode']=I('orderCode');
    	$pageData['cinemaCode']=I('cinemaCode');
    	$pageData['status']=I('status');
    	$this->assign('pageData',$pageData);
    	if(!empty($pageData['orderCode'])){
    		$map['orderCode']=$pageData['orderCode'];
    	}
    	if(!empty($pageData['cinemaCode'])){
    		$map['cinemaCode']=$pageData['cinemaCode'];
    	}
    	if(($pageData['status']!='-1'&&!empty($pageData['status']))||$pageData['status']=='0'){
    		$map['status']=$pageData['status'];
    	}
    	if(!empty($pageData['payType'])){
    		$map['payType']=$pageData['payType'];
    	}
    	$limit=$this->limit;
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));

        $count = D ('Order')->getOrderCount('*',$map);
        $allPage = ceil ( $count / $this->limit);
        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $this->limit;
        if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit, $pageData );
        }
        $this->assign('page', $showPage);
        $orderlist = D('Order')->getOrderList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'lockTime desc');

        $allcount = D('Order')->getOrderSum('seatCount', $map);

        $allprice = D('Order')->getOrderSum('submitPrice * seatCount', $map);
        // print_r($allprice);
    	$show['count']=$count;
    	$show['allcount']=$allcount;
    	$show['allprice']=round($allprice, 2);
   
    	$this->assign('page',$showPage);
    	$this->assign('show',$show);
    	$this->assign('orderlist',$orderlist);


        $cinemaList = D('Cinema')->getCinemaList();
        $this->assign('cinemaList',$cinemaList);
        $this->display();
    }


    public function otherorderlist()
    {
        $count = D ('Order')->getOtherOrderCount('*',$map);

        $limit=$this->limit;
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));


        $orderlist = D('Order')->getOtherOrderList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'orderTime desc');
        $this->assign('orderlist',$orderlist);
        
        $this->display();
    }

    /**
     * 查询订单状态
     */
    public function queryOrderStatus(){
    	$data=D('order')->queryOrderStatus(I('orderCode'));   //查状态
    	echo json_encode($data);
    }

    /**
     * 取消订单
     *
     * @param string $orderid
     * @return void
     */
    public function cancelOrder(){
        $orderid=I('orderid');
        $user=session('cpuser');
        wlog($user['name'].'正在尝试取消订单'.$orderid, 'order');
        $result=D('Order')->cancelOrder($orderid);

        if ($result['ResultCode'] == 0) {
            $this->success('退票成功！');
        }else{
            $this->error(getMtxError($result['ResultCode']));
        }
    }
    /**
     * 单独退票
     */
    public function backticket(){
    	$orderid=I('orderid');
    	if(empty($orderid)){
    		die('请输入订单号');
    	}
    	$result=D('order')->backticket($orderid);
    	print_r($result);
    }
    
    /**
     * 退款
     */
    public function backMoney(){
        $orderid=I('orderid');
        if(empty($orderid)){
        	die('请输入订单号');
        }
        $result=D('order')->backMoney($orderid);
        print_r($result);
    }
    /**
     * 充值
     */
    public function backin(){
    	$cardId=I('cardId');
    	$money=I('money');
    	if(empty($cardId)||empty($money)){
    		die('参数错误');
    	}
    	$result=D('order')->backin($cardId,$money);
    	print_r($result);
    }
    /**
     * 订单详情页
     *
     * @param string $id
     * @return void
     */
    public function orderDetail(){
        $order=D('Order')->findObj(array('orderCode'=>I('id')));
        $order['time']=date('Y-m-d H:i:s',$order['lockTime']);
        $order['startTime']=date('Y-m-d H:i:s',$order['startTime']);
        echo json_encode($order);
    }



    public function rechargelist(){
    	
    	$pageData['id']=I('id');
    	$pageData['cardId']=I('cardId');
    	$pageData['mobile']=I('mobile');
    	$pageData['status']=I('status');
    	$pageData['cinemaCode']=I('cinemaCode');
    	$pageData['type']=I('type');
    	$start=I('start');
    	$end=I('end');
    	$pageData['start']=$start;
    	$pageData['end']=$end;
    	if(!empty($start)&&!empty($end)){
    		$map['createTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60));
    	}elseif(!empty($start)){
    		$map['createTime']= array(array('egt',strtotime($start)));
    	}elseif(!empty($end)){
    		$map['createTime']= array(array('elt',strtotime($end)+24*60*60));
    	}

    	if(!empty($pageData['id'])){
    		$map['id']=array('eq',$pageData['id']);
    	}
    	if(($pageData['type']!='-1'&&!empty($pageData['type']))||$pageData['type']=='0'){
    		$map['type']=array('eq',$pageData['type']);
    	}
    	if(!empty($pageData['cinemaCode'])){
    		$map['cinemaCode']=array('eq',$pageData['cinemaCode']);
    	}
    	if(!empty($pageData['cardId'])){
    		$map['cardId']=array('eq',$pageData['cardId']);
    	}
    	if(!empty($pageData['mobile'])){
    		$map['mobile']=array('eq',$pageData['mobile']);
    	}
    	if(($pageData['status']!='-1'&&!empty($pageData['status']))||$pageData['status']=='0'){
    		$map['status']=array('eq',$pageData['status']);
    	}
    	$this->assign('pageData',$pageData);
        $limit=$this->limit;
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
        $count = D ('Recharge')->getRechargeCount('*',$map);
        $allPage = ceil ( $count / $this->limit);
        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $this->limit;
        if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit, $pageData);
        }
        $this->assign('page', $showPage);
        $recharges = D('Recharge')->getRechargeList('', $map, ($nowPage - 1) * $this->limit . ',' . $this->limit, 'createTime desc');

        $allprice = D('Recharge')->getRechargeSum('money', $map);
        // print_r($allprice);
        $show['count']=$count;
        $show['allcount']=$allcount;
        $show['allprice']=round($allprice, 2);

        $this->assign('page',$showPage);
        $this->assign('show',$show);
        $this->assign('recharges',$recharges);

        $cinemaList = D('Cinema')->getCinemaList();
        $this->assign('cinemaList',$cinemaList);
        $this->display();

    }
    
    /**
     * 打印订单信息
     *
     * @param string $orderCode
     * @param string $mobile
     * @param string $cinemaCode
     * @param string $status
     * @param string $mobile
     */
    public function orderPort(){
    	$title=array("订单号","放映时间","影院名称","影厅名称","影片名称","接收手机","卡号","座位信息","下单时间","上报单价","票数","订单金额","状态");
    	$start=I('start');
    	$end=I('end');
    	$start1=I('start1');
    	$end1=I('end1');
    	if(!empty($start)&&!empty($end)){
    		$map['downTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60));
    	}elseif(!empty($start)){
    		$map['downTime']= array('egt',strtotime($start));
    	}elseif(!empty($end)){
    		$map['downTime']= array('elt',strtotime($end)+24*60*60);
    	}
    	if(!empty($start1)&&!empty($end1)){
    		$map['startTime']= array(array('egt',strtotime($start1)),array('elt',strtotime($end1)+24*60*60));
    	}elseif(!empty($start1)){
    		$map['startTime']= array(array('egt',strtotime($start1)));
    	}elseif(!empty($end1)){
    		$map['startTime']= array(array('elt',strtotime($end1)+24*60*60));
    	}
    	$pageData['cardId']=I('cardId');
    	$pageData['mobile']=I('mobile');
    	$pageData['orderCode']=I('orderCode');
    	$pageData['cinemaCode']=I('cinemaCode');
    	$pageData['status']=I('status');
    	$pageData['payType']=I('payType');
    	if(!empty($pageData['cardId'])){
    		$map['cardId']=$pageData['cardId'];
    	}
    	if(!empty($pageData['mobile'])){
    		$map['mobile']=$pageData['mobile'];
    	}
    	if(!empty($pageData['orderCode'])){
    		$map['orderCode']=$pageData['orderCode'];
    	}
    	if(!empty($pageData['cinemaCode'])){
    		$map['cinemaCode']=$pageData['cinemaCode'];
    	}
    	if(($pageData['status']!='-1'&&!empty($pageData['status']))||$pageData['status']=='0'){
    		$map['status']=$pageData['status'];
    	}
    	if(!empty($pageData['payType'])){
    		$map['payType']=$pageData['payType'];
    	}
        $orders = D('Order')->getOrderList("orderCode,FROM_UNIXTIME(startTime,'%Y-%m-%d %H:%i') as stime,myPrice,cinemaName,hallName,filmName,mobile,cardId,seatIntroduce,status,FROM_UNIXTIME(downTime,'%Y-%m-%d %H:%i') as ltime,submitPrice,seatCount,otherPayInfo", $map);
        foreach ($orders as $k=>$v){
            $otherPayInfo = json_decode($v['otherPayInfo'], true);

            // print_r($otherPayInfo);
            foreach ($otherPayInfo[0] as $key => $value) {
                // $orders[$k][$key] = count($value);
                $voucherTitle[$key] = true;
            }

            // unset($orders[$k]['otherPayInfo']);
            unset($orders[$k]['status']);
        	unset($orders[$k]['myPrice']);
        }

        foreach ($voucherTitle as $key => $value) {
            $voucherMap['typeId'] = $key;
            $voucherInfo = D('Voucher')->getVoucherType('*', $voucherMap);
            $title[] = $voucherInfo['typeName'];
        }


        foreach ($orders as $key => $value) {
            $otherPayInfo = json_decode($value['otherPayInfo'], true);
            // print_r($otherPayInfo[0]);
            foreach ($voucherTitle as $k => $v) {
                if (empty($otherPayInfo[0][$k])) {
                    $orders[$key][$k] = 0;
                }else{
                    $orders[$key][$k] = count($otherPayInfo[0][$k]);
                }
            }
            unset($orders[$key]['otherPayInfo']);
        }

        exportexcel($orders,$title);
    }
    /**
     * 打印充值订单信息
     *
     * @param string $id
     * @param string $cardId
     * @param string $cinemaCode
     * @param string $status
     */
    public function rechargePort(){
    	$title=array("0"=>"订单号","3"=>"充值金额","4"=>"余额","5"=>"充值时间","6"=>"充值流水号","7"=>"状态","8"=>"会员卡号/手机号",);
    	$pageData['id']=I('id');
    	$pageData['cardId']=I('cardId');
    	$pageData['mobile']=I('mobile');
    	$pageData['status']=I('status');
    	$pageData['cinemaCode']=I('cinemaCode');
    	$pageData['type']=I('type');
    	$start=I('start');
    	$end=I('end');
    	$pageData['start']=$start;
    	$pageData['end']=$end;
    	if(!empty($start)&&!empty($end)){
    		$map['createTime']= array(array('egt',strtotime($start)),array('elt',strtotime($end)+24*60*60));
    	}elseif(!empty($start)){
    		$map['createTime']= array(array('egt',strtotime($start)));
    	}elseif(!empty($end)){
    		$map['createTime']= array(array('elt',strtotime($end)+24*60*60));
    	}

    	if(!empty($pageData['id'])){
    		$map['id']=array('eq',$pageData['id']);
    	}
    	if(($pageData['type']!='-1'&&!empty($pageData['type']))||$pageData['type']=='0'){
    		$map['type']=array('eq',$pageData['type']);
    	}
    	if(!empty($pageData['cinemaCode'])){
    		$map['cinemaCode']=array('eq',$pageData['cinemaCode']);
    	}
    	if(!empty($pageData['cardId'])){
    		$map['cardId']=array('eq',$pageData['cardId']);
    	}
    	if(!empty($pageData['mobile'])){
    		$map['mobile']=array('eq',$pageData['mobile']);
    	}
    	if(($pageData['status']!='-1'&&!empty($pageData['status']))||$pageData['status']=='0'){
    		$map['status']=array('eq',$pageData['status']);
    	}
    	$this->assign('pageData',$pageData);
        $limit=$this->limit;
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
        $count = D ('Recharge')->getRechargeCount('*',$map);
        $allPage = ceil ( $count / $this->limit);
        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $this->limit;
        if ($count > $this->limit) {
            $showPage = $this->getPageList ( $count, $this->limit, array('cinemaGroupId' => $cinemaGroupId) );
        }
        $this->assign('page', $showPage);
        $topUps = D('Recharge')->getRechargeList("id,cardId,mobile,money,lastMoney,FROM_UNIXTIME(createTime,'%Y-%m-%d %H:%i') as stime,topNo,status", $map);
        foreach ($topUps as $k=>$value) {
    		unset($topUps[$k]['status']);
    		unset($topUps[$k]['cardId']);
    		unset($topUps[$k]['mobile']);
    	}
    	exportexcel($topUps,$title);
    }
}