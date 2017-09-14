<?php
/**
 * 包场排期列表展示
 * @author jcjtim
 * @package admin
 */
namespace Admin\Controller;
use Think\Controller;
class PrivateController extends AdminController {	
	 /**
	  * 订单展示
	  */
	 public function order(){
	 	$mode=D('WholeReserve');
	 	$mode1=D('WholeInvoiceDetails');	
		$data=array();
		$pageData=array();
		$data['paymentState']=2;//获取支付成功的订单	
		if(isset($_REQUEST['state'])&&$_REQUEST['state']!=''){
			$data['state']=$_REQUEST['state'];
			$pageData['state']=$_REQUEST['state'];
		}else{
			$data['eltstate']=3;
		}
	 	if(isset($_REQUEST['id'])&&$_REQUEST['id']!=''){
			$data['id']=$_REQUEST['id'];
			$pageData['id']=$_REQUEST['id'];
		}
		if(isset($_REQUEST['start'])){
			if($_REQUEST['start'])
			$data['start_time']=strtotime($_REQUEST['start']);
			$pageData['start']=$_REQUEST['start'];
		}
	 	if(isset($_REQUEST['end'])){
	 		if($_REQUEST['end'])
			$data['end_time']=strtotime($_REQUEST['end']);
			$pageData['end']=$_REQUEST['end'];
		}
	 	if(isset($_REQUEST['tel'])){
			if($_REQUEST['tel'])
			$data['tel']=$_REQUEST['tel'];
			$pageData['tel']=$_REQUEST['tel'];
		}
		$data['sort']='id desc';
		$this->assign('pageData',$pageData);
		$limit=$this->limit;
//		$limit=3;
        $nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
        $showlist=array();
        $count=$mode->getlist($data,3);
        $showlist['count']=$count;
//        $count = D ('Order')->getOrderCount('*',$map);
        $allPage = ceil ( $count / $limit);
        $curPage = $this->curPage ( $nowPage, $allPage );
        $startLimit = ($curPage - 1) * $limit;
//        if ($count > $limit) {
            $showPage = $this->getPageList ( $count, $limit, $data );
//        }
        $this->assign('page', $showPage);
        $data['firstRow']=$startLimit;
        $data['listRows']=$limit;
	 	$list=$mode->getlist($data,2);	
	 	$data['getField']='sum(total) as allprice ,count(id) as count';
		$retlist=$mode->getlist($data,5);
		foreach($list as $k=> $v){		
			$list[$k]['cc']=date('H:i',$v['viewingDate']);
			$list[$k]['ec']=date('H:i',$v['endTime']);
			$list[$k]['time']=date('Y-m-d',$v['viewingDate']);
			$list[$k]['paymentTimeflag']=date('Y-m-d',$v['paymentTime']);
			if($v['paymentMethod']==1) {	
				$list[$k]['paymentMethodflag']='余额支付';
			}elseif($v['paymentMethod']==2) {
				$list[$k]['paymentMethodflag']='支付宝支付';			
			}elseif($v['paymentMethod']==3){
				$list[$k]['paymentMethodflag']='银联支付';		
			}		
			if($v['state']==1) {    
                $list[$k]['stateflag']='未兑换';
                $list[$k]['receivables']='部分收款';         	
            }elseif($v['state']==2) {
                $list[$k]['stateflag']='已兑换';
                $list[$k]['receivables']='全部收款';  
//                $list[$k]['stateflag']='已验证'; 			
    			$data=array();
	    		$data['uid']=$v['adminUid'];
	    		$return=$mode->admin_getlist($data,4);	    		
	    		$list[$k]['realName']=$return['realName'];         
            }elseif($v['state']==3){
                $list[$k]['stateflag']='退订';
                $list[$k]['receivables']='已退款';      
            }
        	if($v['paymentState']==1) {    
                $list[$k]['paymentStateflag']='等待支付';
            }elseif($v['paymentState']==2) {
                $list[$k]['paymentStateflag']='完成支付';           
            }elseif($v['paymentState']==3){
                $list[$k]['paymentStateflag']='支付失败';      
            }   

            $list[$k]['total']=number_format($v['total'],2);
            $list[$k]['prepay']=number_format($v['prepay'],2);
            $list[$k]['cr']=number_format($v['total']-$v['prepay'],2);
            
//			$list[$k]['cr']=;			
			$data=array();
			$data['reserveId']=$v['id'];
			$list[$k]['invoiceflag']='未开票';
			$ret=$mode1->getlist($data,4);
			if($ret&&$ret['state']==1){
				$list[$k]['invoiceflag']='已开票';			
			}			
		}		
//		$showlist['count']=$retlist;

		$retlist[0]['allprice']=number_format($retlist[0]['allprice'],2);
		$this->assign('show',$retlist[0]); 
		$this->assign('list',$list); 
		$this->display();		
	}
	/**
	 * 客户预定信息查询
	 */
	public function report_seach() {	
		$mode=D('WholeReserve');		
		$data=array();
		$data['eltstate']=2;
		$data['paymentState']=2;
		if($_REQUEST['viewingDate']) {		
			$data['start_time']=$_REQUEST['viewingDate'];
	        $data['end_time']=$_REQUEST['viewingDate'];		
		}
		if($_REQUEST['mobile']) {		
			$data['tel']=$_REQUEST['mobile'];		
		}
		if($_REQUEST['videoId']) {		
			$data['videoId']=$_REQUEST['videoId'];	
		}
		$list=$mode->getlist($data);		
		foreach($list as $k=> $v){		
			$list[$k]['cc']=date('H:i',$v['viewingDate']);
			$list[$k]['ec']=date('H:i',$v['endTime']);
			$list[$k]['time']=date('Y-m-d',$v['viewingDate']);
			if($v['paymentMethod']==1) {	
				$list[$k]['paymentMethodflag']='余额支付';
			}elseif($v['paymentMethod']==2) {
				$list[$k]['paymentMethodflag']='支付宝支付';			
			}elseif($v['paymentMethod']==3){
				$list[$k]['paymentMethodflag']='银联支付';		
			}		
			if($v['state']==1) {    
                $list[$k]['stateflag']='进行中';
            }elseif($v['state']==2) {
                $list[$k]['stateflag']='已完成';           
            }elseif($v['state']==3){
                $list[$k]['stateflag']='退订';      
            }
        	if($v['paymentState']==1) {    
                $list[$k]['paymentStateflag']='等待支付';
            }elseif($v['paymentState']==2) {
                $list[$k]['paymentStateflag']='完成支付';           
            }elseif($v['paymentState']==3){
                $list[$k]['paymentStateflag']='支付失败';      
            }
		}
		echo json_encode($list);
	}
	
	
	
    /**
     *客户预定信息展示
     */
    public function report() {
        $year=date('Y');
        $m=date('m');
        $d=date('j');
        $lists=array();
        $plantime=array();
        $mode1=D('WholePlanNumber');  
        $mode=D('WholeVideoInformation');
        $list2=$mode->getlist();
        $mode=D('WholeReserve');
        for ($i = 0; $i < 12; $i++) {
            $list=array();
            $data=mktime(0, 0, 0, $m, $d, $year);
            $list['id']=$data;
            $list['name']=date('m月d日',$data);           
            $dataarray=array();
            $dataarray['start_time']=$data;
            $dataarray['end_time']=$data;
            $dataarray['eltstate']=2; 
        	$dataarray['paymentState']=2; 
            $ret=$mode->getlist($dataarray,3);                     
            $list['num']=$ret;                  
            $lists[]=$list;
            $d++;
        }
        $data=array();
        $data['eltstate']=2; 
        $data['paymentState']=2; 
        $data['start_time']=mktime(0, 0, 0, date('m'), date('j'), date('Y'));
        $data['end_time']=mktime(0, 0, 0, date('m'), date('j'), date('Y'));
        $list=$mode->getlist($data);  
        if(is_array($list))      
        foreach($list as $k=> $v){      
            $list[$k]['cc']=date('H:i',$v['viewingDate']);
            $list[$k]['time']=date('Y-m-d',$v['viewingDate']);
            if($v['paymentMethod']==1) {    
                $list[$k]['paymentMethodflag']='余额支付';
            }elseif($v['paymentMethod']==2) {
                $list[$k]['paymentMethodflag']='支付宝支付';         
            }elseif($v['paymentMethod']==3){
                $list[$k]['paymentMethodflag']='银联支付';      
            }       
        	if($v['state']==1) {    
                $list[$k]['stateflag']='进行中';
            }elseif($v['state']==2) {
                $list[$k]['stateflag']='已完成';           
            }elseif($v['state']==3){
                $list[$k]['stateflag']='退订';      
            }
        	if($v['paymentState']==1) {    
                $list[$k]['paymentStateflag']='等待支付';
            }elseif($v['paymentState']==2) {
                $list[$k]['paymentStateflag']='完成支付';           
            }elseif($v['paymentState']==3){
                $list[$k]['paymentStateflag']='支付失败';      
            }
        }
        $this->assign('list',$list);
        $this->assign('list1',$lists);
        $this->assign('list2',$list2);
        $this->display('');
    }
    /**
     * 订单删除功能
     */
    public function report_delete() {
    	$id=$_REQUEST['id'];
    	$data=array();
    	$data['id']=$id;
    	if(isset($_REQUEST['state'])) {
    		$data['state']=$_REQUEST['state'];
    	}else {
    		$data['state']=3;
    	}
    	$mode=D('WholeReserve');
    	$ret=$mode->update_model($data);
    	echo '{"statusCode":"1", "message":"操作成功"}';
    }

    /**
     *订单回收站
     */
    public function recovery() {
    	$mode=D('WholeReserve');
    	$data=array();
    	$data['state']=3;//删除的状态
    	if($_REQUEST['mobile']) {
    		$data['tel']=$_REQUEST['mobile'];
    	}
    	if($_REQUEST['videoId']) {
    		$data['videoId']=$_REQUEST['videoId'];
    	}
    	$list=$mode->getlist($data);
    	foreach($list as $k=> $v){
    		$list[$k]['cc']=date('H:i',$v['viewingDate']);
    		$list[$k]['time']=date('Y-m-d',$v['viewingDate']);
    		if($v['paymentMethod']==1) {
    			$list[$k]['paymentMethodflag']='余额支付';
    		}elseif($v['paymentMethod']==2) {
    			$list[$k]['paymentMethodflag']='支付宝支付';
    		}elseif($v['paymentMethod']==3){
    			$list[$k]['paymentMethodflag']='银联支付';
    		}

    			
    		if($v['state']==1) {
    			$list[$k]['stateflag']='进行中';
    		}elseif($v['paymentMethod']==2) {
    			$list[$k]['stateflag']='已完成';
    		}elseif($v['paymentMethod']==3){
    			$list[$k]['stateflag']='银联支付';
    		}
    	}
    	$mode=D('WholeVideoInformation');
    	$list2=$mode->getlist();
    	//		dump($list);
    	$this->assign('list',$list);
    	$this->assign('list2',$list2);
    	$this->assign('pageData',$_REQUEST);

    	$this->display('');

    }
	
	
	/**
	 * 订单详情展示
	 */	
	public function order_from() {
		$id=$_GET['id'];
		$mode=D('WholeReserve');
		$data=array();
		$data['id']=$id;	
		$list=$mode->getlist($data,4);

		$list['paymentTimeflag']=date('Y-m-d H:i',$list['paymentTime']);	
		$list['cc']=date('H:i',$list['viewingDate']);
		$list['ec']=date('H:i',$list['endTime']);
		$list['time']=date('Y-m-d',$list['viewingDate']);
		$data=array();
		$data['reserveId']=$id;
		$data['type']=1;	
		$packagelist=$mode->reserve_relation_package_getlist($data);//获取关联套餐内容
		if($packagelist) {
			foreach($packagelist as $k=>$v) {
				
				
				$packagelist[$k]['tolprice']=$v['num']*$v['price'];
			
			
			
			}			
			$list['packagelist']=$packagelist;		
		}
		$data=array();
		$data['reserveId']=$id;
		$data['type']=2;	
		$servicelist=$mode->reserve_relation_service_getlist($data);//获取关联服务内容	
		if($servicelist) {
			foreach($servicelist as $k=>$v) {
				
				
				$servicelist[$k]['tolprice']=$v['num']*$v['price'];
			
			
			
			}
			
			
			$list['servicelist']=$servicelist;		
		}
		$this->assign($list);		
		$this->display('');
	}
	
	/**
	 *改签展示、改签操作
	 */
	public function editorder_from() {	
		
		$id=$_GET['id'];
		$mode=D('WholePlanNumber');	
		$mode1=D('WholeReserve');	
		if(isset($_POST['updata'])&&!empty($_REQUEST)){       				
			$data=array();
			$data['id']=$_POST['id'];						
			$data['viewingDate']=$_POST['viewingDate'];
			$data['endTime']=$_POST['endTime'];
			$data['filmNo']=$_POST['filmNo'];
			$data['filmName']=$_POST['filmName'];
			$data['changeState']=2;
			$data['total']=$_POST['total'];	
			$data['planPrice']=$_POST['planPrice'];	
			
			$mode1->update_model($data);
				echo '{"statusCode":"1", "message":"操作成功"}';
				exit;
		}
		$data=array();
		$data['id']=$id;
		$reservelist=$mode1->getlist($data,4);
		$reservelist['otherPrice']=$reservelist['total']-$reservelist['planPrice'];
		$this->assign('reservelist',$reservelist);
//		dump($reservelist);
		
		
		
		$year=date('Y');
		$m=date('m');
		$d=date('j')+3;
		
		
		$time=mktime(0, 0, 0, $m, $d, $year);	
		$this->assign('time',$time);
		$data=array();		
		$data['egtstartTime']=time();;	
		$list1=$mode1->cinema_plan_getlist($data,5);
		
		
//		dump($list1);
		if(is_array($list1)){
			
			foreach($list1 as $k =>$v) {
				if($v['filmNo']==$reservelist['filmNo'])
				$list1[$k]['checked']='checked="checked"';
			
			}
		
		
		
		}
		
		
		$this->assign('list1',$list1);
		
		
		
		$lists=array();
		$plantime=array();	
		for ($i = 0; $i < 10; $i++) {
			$list=array();
			$data=mktime(0, 0, 0, $m, $d, $year);
			$list['id']=$data;
			$list['name']=date('m月d日',$data);			
			$dataarray=array();
			$dataarray['start_time']=$data;
			$dataarray['end_time']=$data;	
			$ret=$mode->getlist($dataarray);						
			if($ret) {
				$list['num']=count($ret);					
				foreach($ret as $k=> $v){
					
					$ret[$k]['cc']=date('H:i',$v['time']);
					$ret[$k]['ec']=date('H:i',$v['endTime']);
					
					
					$dataarray=array();
					$dataarray['begintime']=$v['time'];
					$dataarray['endtime']=$v['endTime'];
					$dataarray['neqid']=$id;
					$dataarray['eltstate']=2;
					$dataarray['neqpaymentState']=3;
					$dataarray['videoId']=$reservelist['videoId'];
					
					if($v['time']>=$reservelist['viewingDate']&&$v['endTime']<=$reservelist['endTime']){
//						echo 'xxxx';
					
						$ret[$k]['checked']='checked="checked"';
					}
					
//					dump($dataarray);
					$retbak=$mode1->getlist($dataarray);
					
					
					
					
					if($retbak) {
						$ret[$k]['classesd']='disabled';			
					}
//					dump($ret);
					
					
					
					
				}		
			}else{
				$list['num']='0';
			}
			$plantime[$data]=$ret;			
			$lists[]=$list;
			$d++;
		}
//		dump($lists);
		$this->assign('list',$lists);
		$this->assign('id',$id);
		$this->assign('plantime',$plantime);		
		$this->display('');
	}
	
	
	/**
	 * 改签展示内容
	 */
	public function editOrder_detail() {
		$mode1=D('WholeReserve');
		$data=array();
		$data['reserveId']=I('id');
		$list=$mode1->reserve_log_getlist($data,4);	
		$list['ccOld']=date('H:i',$list['viewingDateOld']);
		$list['timeOld']=date('Y-m-d',$list['viewingDateOld']);
		$list['cc']=date('H:i',$list['viewingDate']);
		$list['ec']=date('H:i',$list['endTime']);
		$list['ecOld']=date('H:i',$list['endTimeOld']);
		$list['time']=date('Y-m-d',$list['viewingDate']);
		$data=array();
		$data['id']=I('id');
		$ret=$mode1->getlist($data,4);

		
		
		
		
		$list['cr']=$list['total']-$ret['prepay'];
		$list['prepay']=$ret['prepay'];
		$list['crOld']=$list['totalOld']-$ret['prepay'];
		
		
		
		$list['topicName']=$ret['topicName'];
		$list['crOld']=number_format($list['crOld'],2);
		$list['cr']=number_format($list['cr'],2);
		$list['totalOld']=number_format($list['totalOld'],2);
		$list['total']=number_format($list['total'],2);
		$list['prepayOld']=number_format($list['prepayOld'],2);
		$list['prepay']=number_format($list['prepay'],2);
		
		$this->assign($list);
		$this->display('');		
	}
	/**
	 * 改签中点击场次获取电影内容
	 */
	public function editorder_getfilm() {	
		$mode=D('WholeReserve');		
		$data=array();		
//		$data['time']=I('time');	
		$data['egtstartTime']=time();
		$list=$mode->cinema_plan_getlist($data,5);	
		echo json_encode($list);
	}
	
	/**
	 * 发票内容展示详情、发票确认操作
	 */
	public function invoice_from() {
		$model=D('WholeInvoiceDetails');	
		if($_POST['id']) {
			$data=array();
			$data['id']=$_POST['id'];
			$data['code']=$_POST['code'];
			$data['state']=1;
			$model->update_model($data);
			echo '{"statusCode":"1", "message":"操作成功"}';
			exit;			
		}
		$id=I('id');		
		$data=array();
		$data['reserveId']=I('id');
		$list=$model->getlist($data,4);
		$mode=D('WholeReserve');		
		$data=array();
		$data['id']=I('id');
		$ret1=$mode->getlist($data,4);
		if($ret1['state']==2) {
			
			
			$this->assign('flag','2222');
		
		
		}
		$data=array();
		$data['uid']=$ret1['uid'];
		$data['mark']='1';
		$ret=$model->consignee_getlist($data,4);
		$list['telname']=$ret['name'];
		$list['telphone']=$ret['phone'];
		$this->assign($list);
		$this->display('');
	}
	
	/**
	 * 排期列表展示
	 */
	public function plan() {
		$mode=D('WholePlanNumber');	
		$mode1=D('WholeReserve');
		$year=date('Y');
		$m=date('m');
		$d=date('j')+3;
		$lists=array();
		for ($i = 0; $i < 10; $i++) {
			$list=array();
			$data=mktime(0, 0, 0, $m, $d, $year);				
			$dataarray=array();
			$dataarray['start_time']=$data;
			$dataarray['end_time']=$data;
			$dataarray['sort']='time asc';	
			$ret=$mode->getlist($dataarray);			
			if($ret) {
				$list['id']=$data;
				$list['name']=date('m月d日',$data);				
				foreach($ret as $k=> $v){
					$ret[$k]['cc']=date('H:i',$v['time']);
					$ret[$k]['ec']=date('H:i',$v['endTime']);
					
					
					$dataarray=array();
					$dataarray['begintime']=$v['time'];
					$dataarray['endtime']=$v['endTime'];
					$dataarray['eltstate']=2;
					$dataarray['eltpaymentState']=2;
					
					$retbak=$mode1->getlist($dataarray);
					
					
					if($retbak) {
						$ret[$k]['classesd']='disabled';			
					}
							
				}
				$list['list']=$ret;
				$lists[]=$list;	
			}	
			$d++;
		}
		$this->assign('list',$lists);
		$this->display('');
	}
	/**
	 * 局部刷新调用排期
	 */
	public function search_plan() {
		$mode=D('WholePlanNumber');
		$mode1=D('WholeReserve');
		$list=array();
		$data=$_REQUEST['time'];
		$dataarray=array();
		$dataarray['start_time']=$data;
		$dataarray['end_time']=$data;
		$dataarray['sort']='time asc';
		$ret=$mode->getlist($dataarray);
		$list='';
		if($ret) {
			foreach($ret as $k=> $v){
				$ret[$k]['cc']=date('H:i',$v['time']);
				$ret[$k]['ec']=date('H:i',$v['endTime']);

				$dataarray=array();
				$dataarray['begintime']=$v['time'];
				$dataarray['endtime']=$v['endTime'];
				$retbak=$mode1->getlist($dataarray);
					
					
				if($retbak) {
					$ret[$k]['classesd']='disabled';
				}
			}
			$list=$ret;

		}
		echo json_encode($list);

	}
	/**
	 * 添加场次展示、添加场次
	 */
	public function plan_from() {
		$mode=D('WholePlanNumber');
		if(isset($_REQUEST)&&!empty($_REQUEST)){
			$return=$mode->add_model($_REQUEST);

			if($return==4){
				echo '<script>';
				echo 'parent.layer.alert("数据不能为空！")';
				echo '</script>';
				exit;				
			}
			if($return==5){
				echo '<script>';
				echo 'parent.layer.alert("时间段配置有误！")';
				echo '</script>';
				exit;				
			}
			echo '<script>';
			echo " parent.parent.window.location.href='plan';";
			echo 'var index = parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;			
		}
		$year=date('Y');
		$m=date('m');
		$d=date('j')+3;
		$lists=array();
		$plantime=array();	
		for ($i = 0; $i < 10; $i++) {
			$list=array();
			$data=mktime(0, 0, 0, $m, $d, $year);
			$list['id']=$data;
			$list['name']=date('m月d日',$data);			
			$dataarray=array();
			$dataarray['start_time']=$data;
			$dataarray['end_time']=$data;
			$dataarray['sort']='time asc';	
			$ret=$mode->getlist($dataarray);						
			if($ret) {
				$list['state']='已编排';					
				foreach($ret as $k=> $v){
					$ret[$k]['cc']=date('H:i',$v['time']);
					$ret[$k]['ec']=date('H:i',$v['endTime']);

					
				}		
			}else{
				$list['state']='未编排';
			}
			$plantime[$data]=$ret;			
			$lists[]=$list;
			$d++;
		}
		$this->assign('list',$lists);
		$this->assign('plantime',$plantime);
		$this->display('');
	}
	/**
	 * 删除场次信息
	 */
	public function plan_delete() {
		$mode=D('WholePlanNumber');

		$data=array();
		$data['id']=$_POST['id'];
		$ret=$mode->delete_model($data);
		if($ret===4){			
			echo '{"statusCode":"0", "message":"操作成功"}';
		}else{
			echo '{"statusCode":"1", "message":"操作成功"}';
		}
		
		
	}
	/**
	 * 添加场次展示、添加场次
	 */
	public function editplan_from() {
		$mode=D('WholePlanNumber');
		if(isset($_REQUEST['updata'])){
			if(!$_REQUEST['time']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请选择开始时间段！")';
    			echo '</script>';
    			exit;						
			}
			if(!$_REQUEST['endTime']) {		
    			echo '<script>';
    			echo 'parent.layer.alert("请选择结束时间段！")';
    			echo '</script>';
    			exit;			
			}
			if(!$_REQUEST['price']) {		
    			echo '<script>';
    			echo 'parent.layer.alert("请输入价格！")';
    			echo '</script>';
    			exit;			
			}
			if(!is_numeric($_POST['price']) ){
				echo '<script>';
				echo 'parent.layer.alert("价格必须为数字！")';
				echo '</script>';
				exit;
			}
			if(!$_REQUEST['oriPrice']) {		
    			echo '<script>';
    			echo 'parent.layer.alert("请输入原价！")';
    			echo '</script>';
    			exit;			
			}
			if(!is_numeric($_POST['oriPrice']) ){
				echo '<script>';
				echo 'parent.layer.alert("原价必须为数字！")';
				echo '</script>';
				exit;
			}
			
			$data=array();		
			$data['id']=I('id');
			$data['time']=strtotime(I('now').' '.I('time'));
			$data['endTime']=strtotime(I('now').' '.I('endTime'));
			if($data['time']>=$data['endTime']){
				echo '<script>';
    			echo 'parent.layer.alert("结束时间大于了开始时间！")';
    			echo '</script>';
    			exit;		
			}else{			
				$data['duration']=($data['endTime']-$data['time'])/60;
			}
			$data['price']=I('price');
			$data['oriPrice']=I('oriPrice');
			$ret=$mode->update_model($data);
			if($ret===4){
				echo '<script>';
    			echo 'parent.layer.alert("选择的时间段重复！")';
    			echo '</script>';
    			exit;
			}
			$nowtime=strtotime(I('now'));
			echo '<script>';
			echo 'parent.parent.search_plan("'.$nowtime.'");';
			echo 'var index = parent.parent.layer.getFrameIndex(parent.window.name);';
			echo 'parent.parent.layer.close(index);';
			
			echo '</script>';
			exit;		
		}
		$data=array();
		$data['id']=I('id');
		$list=$mode->getlist($data,4);
		$list['timeflag']=date('H:i',$list['time']);
		$list['now']=date('Y-m-d',$list['time']);
		$list['endTimeflag']=date('H:i',$list['endTime']);
		
		$this->assign($list);

		$this->display('');
	}
	/**
	 * 配置价格操作展示
	 */
	public function addprice_from() {
		$mode=D('plan');
		$mode1=D('WholeConfigurationDetails');
		$list=$mode->getPlanFilms();
		foreach($list as $k=>$v) {
			$dataarray=array();
			$dataarray['planNumberId']=$_REQUEST['id'];	
			$dataarray['filmNo']=$v['filmNo'];	
			$ret=$mode1->getlist($dataarray,4);
			if($ret) {
				$v['price']=$ret['price'];
				$v['fullHousePrice']=$ret['fullHousePrice'];
				$v['favorablePrice']=$ret['favorablePrice'];
				$v['serviceCharge']=$ret['serviceCharge'];
				$v['checked']='checked="true"';
			}else {
				$v['price']='0';
				$v['fullHousePrice']='0';
				$v['favorablePrice']='0';
				$v['serviceCharge']='0';
			}			
			$list[$k]=$v;		
		}		
		$this->assign('list',$list);		
		$this->assign('id',$_REQUEST['id']);		
		$this->display('');
	}
	/**
	 * 配置价格详细展示
	 */
	public function price_from() {
		$mode=D('WholeConfigurationDetails');
		$dataarray=array();
		$dataarray['planNumberId']=$_REQUEST['id'];
		$list=$mode->getalllist($dataarray);
		$this->assign('list',$list);				
		$this->display('');
	}
	/**
	 * 配置价格添加
	 */
	public function addprice() {
		$mode=D('WholeConfigurationDetails');	
		$id=$_POST['planNumberId'];
		$Unit=$_POST['Unit'];//价格元素集合
		$ids=explode(',',$id);	
		foreach ($ids as $v) {
			$data=array();
			if(!$v) {
			continue;
			}
			$data['planNumberId']=$v;
			$data['Unit']=$Unit;
			$mode->delete_model($data);
			$mode->add_model($data);
		}
		echo '{"statusCode":"1", "message":"操作成功"}';
	}
	/**
	 * 其他资料主页展示
	 */
	public function info() {		
		$mode=D('WholeHomeAds');
		$list=$mode->getlist();	
		$mode1=D('WholeVideoInformation');
		$mode11=D('WholeReserve');
		$list1=$mode1->getlist();
		if(is_array($list1))
		foreach($list1 as $k => $v ) {
			
			$data=array();
			$data['videoId']=$v['id'];
			$data['start_time']=time();
			$data['eltstate']=2;
			$data['eltpaymentState']=2;
			$retbak=$mode11->getlist($data);
			if($retbak) {
				$list1[$k]['classesd']='disabled';			
			}		
		}
		$mode2=D('WholePackageInformation');
		$list2=$mode2->getlist();
		$mode3=D('WholeAccessorialService');	
		$list3=$mode3->getlist();
		$mode4=D('WholePaymentType');
		$list4=$mode4->getlist();
		$this->assign('list',$list);
		$this->assign('list1',$list1);
		$this->assign('list2',$list2);
		$this->assign('list3',$list3);
		$this->assign('list4',$list4);
		$this->display('');
	}
	
	/**
	 * 添加首页广告
	 */
	public function banner_from() {	
		if(isset($_REQUEST)&&!empty($_REQUEST)){
			if(!$_POST['title']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入标题！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['title'],"UTF-8")>30) {
    			echo '<script>';
    			echo 'parent.layer.alert("标题最多30个字符！")';
    			echo '</script>';
    			exit;	
    		}
			if(!$_POST['priority']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入优先级！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['priority']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("优先级必须为数字！")';
    			echo '</script>';
    			exit;	
    		}	
			$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->savePath  =     '/banner/'; // 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->upload();
//    		echo $upload->getError();
    		if($info['image']){
    			$data['image']=$info['image']['savepath'].$info['image']['savename'];
    		}else{
    			echo '<script>';
    			echo 'parent.layer.alert("请上传图片")';
    			echo '</script>';
    			exit;
    		}
    		
    		$image = new \Think\Image();
    		$image->open($_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename']);
    		$mimage = './Uploads/banner/'.date('Y-m-d').'/'.$info['image']['savename'];
    		aotumkdir($mimage);
    		// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
    		$image->thumb(600, 200)->save($mimage);
    		
    		
			$mode=D('WholeHomeAds');				
			$data=array();
			$data['title']=$_POST['title'];
			$data['link']=$_POST['link'];
			$data['priority']=$_POST['priority'];
			$data['storePath']= $_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename'];
			$data['relativePath']=C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename'];
			$mode->add_model($data);		
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}			
		$this->display('');
	}
	/**
	 * 修改首页广告
	 */
	public function editbanner_from() {	
		$mode=D('WholeHomeAds');
		if(isset($_POST['updata'])&&!empty($_REQUEST)){
			if(!$_POST['title']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入标题！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['title'],"UTF-8")>30) {
    			echo '<script>';
    			echo 'parent.layer.alert("标题最多30个字符！")';
    			echo '</script>';
    			exit;	
    		}
    		
			if(!$_POST['priority']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入优先级！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['priority']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("优先级必须为数字！")';
    			echo '</script>';
    			exit;	
    		}
			$data=array();
			$data['id']=$_POST['id'];
			$data['title']=$_POST['title'];
			$data['link']=$_POST['link'];
			$data['priority']=$_POST['priority'];
			$upload = new \Think\Upload(); // 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
//          $upload->rootPath  =     'zmaxfilm/Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     '/banner/'; // 设置附件上传（子）目录
            // 上传文件
		    $info   =   $upload->upload();
            if($info['image']){
            	$image = new \Think\Image();
	    		$image->open($_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename']);
	    		$mimage = './Uploads/banner/'.date('Y-m-d').'/'.$info['image']['savename'];
	    		aotumkdir($mimage);
	    				// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
	    		$image->thumb(600, 200)->save($mimage);
               $data['storePath']= $_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename'];
               $data['relativePath']=C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename'];
            }
			$mode->update_model($data);		
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}
		$data=array();
		$data['id']=$_GET['id'];
		$list=$mode->getlist($data,4);	
		$this->assign($list);			
		$this->display('');
	}
	/**
	 * 删除首页广告
	 */
	public function banner_from_delete() {			
		$mode=D('WholeHomeAds');
		$data=array();
		$data['id']=$_POST['id'];
//		dump($_GET);
		$list=$mode->delete_model($data);	
		echo '{"statusCode":"1", "message":"操作成功"}';
	}	
	/**
	 * 添加影厅展示
	 */
	public function hall_from() {
		if(isset($_REQUEST)&&!empty($_REQUEST)){		
			if(!$_POST['topicName']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入影厅主题名称！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['topicName'],"UTF-8")>15) {
    			echo '<script>';
    			echo 'parent.layer.alert("影厅主题名称最多15个字符！")';
    			echo '</script>';
    			exit;	
    		}
		
			if(!$_POST['videoCode']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入影厅编号！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['videoCode'],"UTF-8")>10) {
    			echo '<script>';
    			echo 'parent.layer.alert("影厅编号最多10个字符！")';
    			echo '</script>';
    			exit;	
    		}
    		
			if(!$_POST['seating']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入影厅座位数！")';
    			echo '</script>';
    			exit;	
    		}
			if(strlen($_POST['seating'])>5) {
    			echo '<script>';
    			echo 'parent.layer.alert("影厅编号最多5个字符！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['seating']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("影厅座位数必须为数字！")';
    			echo '</script>';
    			exit;	
    		}
			
			
			
			
			$mode=D('WholeVideoInformation');				
			$data=array();
			$data['topicName']=$_POST['topicName'];
			$data['videoCode']=$_POST['videoCode'];
			$data['seating']=$_POST['seating'];
			if(isset($_POST['passNum']))
			$data['passNum']=$_POST['passNum'];

			if(isset($_POST['unitPrice']))
			$data['unitPrice']=$_POST['unitPrice'];
			$data['state']=1;
			$id=$mode->add_model($data);
			$mode1=D('WholeVideoInteriorMap');
			$data=array();
			if(isset($_POST['hallImage']))
			$data['hallImage']=$_POST['hallImage'];		
			$data['videoId']=$id;
			$mode1->add_model($data);		
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}			
		$this->display('');
	}
	/**
	 * 修改影厅信息
	 */
	public function edithall_from() {
		$mode=D('WholeVideoInformation');
		$mode1=D('WholeVideoInteriorMap');
		if(isset($_POST['updata'])&&!empty($_REQUEST)){

				
			if(!$_POST['topicName']) {
				echo '<script>';
				echo 'parent.layer.alert("请输入影厅主题名称！")';
				echo '</script>';
				exit;
			}
			if(iconv_strlen($_POST['topicName'],"UTF-8")>15) {
				echo '<script>';
				echo 'parent.layer.alert("影厅主题名称最多15个字符！")';
				echo '</script>';
				exit;
			}

			if(!$_POST['videoCode']) {
				echo '<script>';
				echo 'parent.layer.alert("请输入影厅编号！")';
				echo '</script>';
				exit;
			}
			if(iconv_strlen($_POST['videoCode'],"UTF-8")>10) {
				echo '<script>';
				echo 'parent.layer.alert("影厅编号最多10个字符！")';
				echo '</script>';
				exit;
			}

			if(!$_POST['seating']) {
				echo '<script>';
				echo 'parent.layer.alert("请输入影厅座位数！")';
				echo '</script>';
				exit;
			}
			if(strlen($_POST['seating'])>5) {
				echo '<script>';
				echo 'parent.layer.alert("影厅编号最多5个字符！")';
				echo '</script>';
				exit;
			}
			if(!is_numeric($_POST['seating']) ){
				echo '<script>';
				echo 'parent.layer.alert("影厅座位数必须为数字！")';
				echo '</script>';
				exit;
			}			
			$data=array();
			$data['id']=$_POST['id'];
			if(isset($_POST['topicName']))
			$data['topicName']=$_POST['topicName'];
			if(isset($_POST['videoCode']))
			$data['videoCode']=$_POST['videoCode'];
			if(isset($_POST['seating']))
			$data['seating']=$_POST['seating'];


			if(isset($_POST['passNum']))
			$data['passNum']=$_POST['passNum'];

			if(isset($_POST['unitPrice']))
			$data['unitPrice']=$_POST['unitPrice'];


			if(isset($_POST['state']))
			$data['state']=$_POST['state'];
			$mode->update_model($data);

			if(!empty($_REQUEST['hallImage'])){
				$data=array();
				$data['hallImage']=$_POST['hallImage'];
				$data['videoId']=$_POST['id'];
				$mode1->add_model($data);
			}
//				exit;
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}
		$data=array();
		$data['id']=$_GET['id'];
		$list=$mode->getlist($data,4);

		$data=array();
		$data['videoId']=$_GET['id'];
		$list2=$mode1->getlist($data);
		$this->assign($list);
		if(is_array($list2)){
		
			$count=count($list2);
		}else{
		
			$count=0;
		}
		$this->assign('count',$count);
		$this->assign('list',$list2);
		$this->display('');
	}
	/**
	 * 删除主题影厅
	 */
	public function hall_delete() {			
		$mode=D('WholeVideoInformation');
		$mode1=D('WholeVideoInteriorMap');	
		$data=array();
		$data['id']=$_POST['id'];
//		dump($_GET);
		$list=$mode->delete_model($data);	
		
		
		$data=array();
		$data['videoId']=$_POST['videoId'];
		$mode1->delete_model($data);
		echo '{"statusCode":"1", "message":"操作成功"}';
	}
	/**
     * 删除修改图片
     */
    function delpic_d(){
    	
    	$mode=D('WholeVideoInteriorMap');
    	if(isset($_POST['id'])) {
    		$data=array();
    		$data['id']=$_POST['id'];
    		$mode->delete_model($data);
    	}
    }
	/**
	 * 影厅设置状态
	 */
	public function set_state() {			
		$mode=D('WholeVideoInformation');
		$data=array();
		$data['id']=$_POST['id'];
		$data['state']=$_POST['state'];
		$mode->update_model($data);			
		echo '{"statusCode":"1", "message":"操作成功"}';
	}
	/**
	 * 添加套餐信息
	 */
	public function package_from() {	
		if(isset($_REQUEST)&&!empty($_REQUEST)){
		if(!$_POST['name']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入套餐名称！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['name'],"UTF-8")>10) {
    			echo '<script>';
    			echo 'parent.layer.alert("套餐名称最多10个字符！")';
    			echo '</script>';
    			exit;	
    		}
		
			if(!$_POST['detail']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入套餐详细！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['detail'],"UTF-8")>16) {
    			echo '<script>';
    			echo 'parent.layer.alert("套餐详细最多16个字符！")';
    			echo '</script>';
    			exit;	
    		}
    		
			if(!$_POST['price']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入套餐售价！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['price']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("套餐售价必须为数字！")';
    			echo '</script>';
    			exit;	
    		}
			if(!$_POST['oriPrice']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入套餐原价！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['oriPrice']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("套餐原价必须为数字！")';
    			echo '</script>';
    			exit;	
    		}
			
			
			$mode=D('WholePackageInformation');				
			$data=array();
			$data['name']=$_POST['name'];
			$data['detail']=$_POST['detail'];
			$data['price']=$_POST['price'];	
			if(isset($_POST['state'])&&$_POST['state']=='on') {
			
				$data['state']=1;
				
				if(!is_numeric($_POST['disNum']) ){
	    			echo '<script>';
	    			echo 'parent.layer.alert("折扣份数必须为数字！")';
	    			echo '</script>';
	    			exit;	
	    		}
				if(!is_numeric($_POST['discount']) ){
	    			echo '<script>';
	    			echo 'parent.layer.alert("折扣必须为数字！")';
	    			echo '</script>';
	    			exit;	
	    		}
	    		$data['disNum']=$_POST['disNum'];
	    		$data['discount']=$_POST['discount'];
	    		
				
			}else {
			
				$data['state']=2;
			}
			$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->savePath  =     '/package/'; // 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->upload();
//    		echo $upload->getError();
    		if($info['image']){
//    			$data['image']=$info['image']['savepath'].$info['image']['savename'];
    		}else{
    			echo '<script>';
    			echo 'parent.layer.alert("请上传图片")';
    			echo '</script>';
    			exit;
    		}
    		$image = new \Think\Image();
    		$image->open($_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename']);
    		$mimage = './Uploads/package/'.date('Y-m-d').'/'.$info['image']['savename'];
    		aotumkdir($mimage);
    				// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
    		$image->thumb(600, 400)->save($mimage);
    		
    		
			$data['storePath']= $_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename'];
			$data['relativePath']=C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename'];
		
			
			$data['oriPrice']=$_POST['oriPrice'];
			$data['status']=1;
//			dump($_POST);
			$mode->add_model($data);	
//			exit;	
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}			
		$this->display('');
	}
	/**
	 * 修改套餐信息
	 */
	public function editpackage_from() {	
		
		$mode=D('WholePackageInformation');
		if(isset($_POST['updata'])&&!empty($_REQUEST)){	
			
			if(!$_POST['name']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入套餐名称！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['name'],"UTF-8")>10) {
    			echo '<script>';
    			echo 'parent.layer.alert("套餐名称最多10个字符！")';
    			echo '</script>';
    			exit;	
    		}
		
			if(!$_POST['detail']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入套餐详细！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['detail'],"UTF-8")>16) {
    			echo '<script>';
    			echo 'parent.layer.alert("套餐详细最多16个字符！")';
    			echo '</script>';
    			exit;	
    		}
    		
			if(!$_POST['price']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入套餐售价！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['price']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("套餐售价必须为数字！")';
    			echo '</script>';
    			exit;	
    		}
			if(!$_POST['oriPrice']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入套餐原价！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['oriPrice']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("套餐原价必须为数字！")';
    			echo '</script>';
    			exit;	
    		}
			
			$data=array();
			$data['id']=$_POST['id'];
			if(isset($_POST['name']))
			$data['name']=$_POST['name'];
			if(isset($_POST['detail']))
			$data['detail']=$_POST['detail'];
			if(isset($_POST['price']))
			$data['price']=$_POST['price'];
			if(isset($_POST['oriPrice']))
			$data['oriPrice']=$_POST['oriPrice'];
			if(isset($_POST['state'])&&$_POST['state']=='on') {			
				$data['state']=1;
				if(!is_numeric($_POST['disNum']) ){
	    			echo '<script>';
	    			echo 'parent.layer.alert("折扣份数必须为数字！")';
	    			echo '</script>';
	    			exit;	
	    		}
				if(!is_numeric($_POST['discount']) ){
	    			echo '<script>';
	    			echo 'parent.layer.alert("折扣必须为数字！")';
	    			echo '</script>';
	    			exit;	
	    		}
	    		$data['disNum']=$_POST['disNum'];
	    		$data['discount']=$_POST['discount'];
			}else {			
				$data['state']=2;
				$data['disNum']='';
	    		$data['discount']='';
			}
			$upload = new \Think\Upload(); // 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
//          $upload->rootPath  =     'zmaxfilm/Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     '/package/'; // 设置附件上传（子）目录
            // 上传文件
		    $info   =   $upload->upload();
            if($info['image']){
            	
            	$image = new \Think\Image();
	    		$image->open($_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename']);
	    		$mimage = './Uploads/package/'.date('Y-m-d').'/'.$info['image']['savename'];
	    		aotumkdir($mimage);
	    				// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
	    		$image->thumb(600, 400)->save($mimage);
            	
               $data['storePath']= $_SERVER ['DOCUMENT_ROOT'].C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename'];
               $data['relativePath']=C('WHOLE_UPLOAD').$info['image']['savepath'].$info['image']['savename'];
            }
			$mode->update_model($data);
//			dump($data);		
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}
		$data=array();
		$data['id']=$_GET['id'];
		$list=$mode->getlist($data,4);	
		$this->assign($list);			
		$this->display('');
	}
	
	/**
	 * 删除套餐信息
	 */
	public function package_delete() {			
		$mode=D('WholePackageInformation');
		
		$data=array();
		$data['id']=$_POST['id'];
		$list=$mode->delete_model($data);	
		echo '{"statusCode":"1", "message":"操作成功"}';
	}
	/**
	 * 套餐设置状态
	 */
	public function set_package_status() {			
		$mode=D('WholePackageInformation');
		$data=array();
		$data['id']=$_POST['id'];
		$data['status']=$_POST['state'];
		$mode->update_model($data);			
		echo '{"statusCode":"1", "message":"操作成功"}';
	}
	/**
	 * 添加附加服务
	 */
	public function other_from() {	
		if(isset($_REQUEST)&&!empty($_REQUEST)){
			if(!$_POST['name']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入服务类别！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['name'],"UTF-8")>15) {
    			echo '<script>';
    			echo 'parent.layer.alert("服务类别最多15个字符！")';
    			echo '</script>';
    			exit;	
    		}	
			if(!$_POST['price']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入服务资费！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['price']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("服务资费必须为数字！")';
    			echo '</script>';
    			exit;	
    		}
			$mode=D('WholeAccessorialService');				
			$data=array();
			$data['name']=$_POST['name'];
			$data['price']=$_POST['price'];
			$data['num']=1;	
			$data['status']=1;	
			$mode->add_model($data);		
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}			
		$this->display('');
	}
	/**
	 * 修改附加服务
	 */
	public function editother_from() {	
		
		$mode=D('WholeAccessorialService');
		if(isset($_POST['updata'])&&!empty($_REQUEST)){
			
			if(!$_POST['name']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入服务类别！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['name'],"UTF-8")>15) {
    			echo '<script>';
    			echo 'parent.layer.alert("服务类别最多15个字符！")';
    			echo '</script>';
    			exit;	
    		}	
			if(!$_POST['price']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入服务资费！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['price']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("服务资费必须为数字！")';
    			echo '</script>';
    			exit;	
    		}
							
			$data=array();
			$data['id']=$_POST['id'];
			if(isset($_POST['name']))
			$data['name']=$_POST['name'];
			if(isset($_POST['price']))
			$data['price']=$_POST['price'];
			if(isset($_POST['num']))
			$data['num']=$_POST['num'];
			$mode->update_model($data);		
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}
		$data=array();
		$data['id']=$_GET['id'];
		$list=$mode->getlist($data,4);	
		$this->assign($list);			
		$this->display('');
	}
	
	
	/**
	 * 删除附加信息
	 */
	public function other_delete() {			
		$mode=D('WholeAccessorialService');
		
		$data=array();
		$data['id']=$_POST['id'];
		$list=$mode->delete_model($data);	
		echo '{"statusCode":"1", "message":"操作成功"}';
	}
	/**
	 * 附加信息设置状态
	 */
	public function set_other_status() {			
		$mode=D('WholeAccessorialService');
		$data=array();
		$data['id']=$_POST['id'];
		$data['status']=$_POST['state'];
		$mode->update_model($data);			
		echo '{"statusCode":"1", "message":"操作成功"}';
	}
	/**
	 * 添加付款类别
	 */
	public function pay_from() {	
		if(isset($_REQUEST)&&!empty($_REQUEST)){
			
			
		
			if(!$_POST['name']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入付款类别！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['name'],"UTF-8")>15) {
    			echo '<script>';
    			echo 'parent.layer.alert("付款类别最多15个字符！")';
    			echo '</script>';
    			exit;	
    		}	
			if(!$_POST['ratio']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入付款比例！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['ratio']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("付款比例数字！")';
    			echo '</script>';
    			exit;	
    		}
			if(!$_POST['detail']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入付款说明！")';
    			echo '</script>';
    			exit;	
    		}
			
			$mode=D('WholePaymentType');				
			$data=array();
			$data['name']=$_POST['name'];
			$data['detail']=$_POST['detail'];
			$data['ratio']=$_POST['ratio'];		
			$mode->add_model($data);		
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}			
		$this->display('');
	}
	/**
	 * 修改付款类别
	 */
	public function editpay_from() {	
		
		$mode=D('WholePaymentType');
		if(isset($_POST['updata'])&&!empty($_REQUEST)){
			if(!$_POST['name']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入付款类别！")';
    			echo '</script>';
    			exit;	
    		}
			if(iconv_strlen($_POST['name'],"UTF-8")>15) {
    			echo '<script>';
    			echo 'parent.layer.alert("付款类别最多15个字符！")';
    			echo '</script>';
    			exit;	
    		}	
			if(!$_POST['ratio']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入付款比例！")';
    			echo '</script>';
    			exit;	
    		}
			if(!is_numeric($_POST['ratio']) ){
    			echo '<script>';
    			echo 'parent.layer.alert("付款比例数字！")';
    			echo '</script>';
    			exit;	
    		}
			if(!$_POST['detail']) {
    			echo '<script>';
    			echo 'parent.layer.alert("请输入付款说明！")';
    			echo '</script>';
    			exit;	
    		}
							
			$data=array();
			$data['id']=$_POST['id'];
			if(isset($_POST['name']))
			$data['name']=$_POST['name'];
			if(isset($_POST['detail']))
			$data['detail']=$_POST['detail'];
			if(isset($_POST['ratio']))
			$data['ratio']=$_POST['ratio'];
			$mode->update_model($data);		
			echo '<script>';
			echo " parent.parent.window.location.href='info';";
			echo 'var index = parent.parent.layer.getFrameIndex(window.name);';
			echo 'parent.layer.close(index);';
			echo '</script>';
			exit;
		}
		$data=array();
		$data['id']=$_GET['id'];
		$list=$mode->getlist($data,4);	
		$this->assign($list);			
		$this->display('');
	}
	

  	/**
     *添加上传图片
     */
    public function addUpload() {
    	$targetFolder = C('WHOLE_UPLOAD'); // Relative to the root
    	if (! empty ( $_FILES ) ) {
    		$tempFile = $_FILES ['hallImage'] ['tmp_name'];
    		$targetPath = $_SERVER ['DOCUMENT_ROOT'] .$targetFolder.'/'.CPUID;
    		if(!is_dir($targetPath)){
    			mkdir($targetPath,0777);
    		}
    		$name=iconv("utf-8","gbk",$_FILES ['hallImage'] ['name']);
    		$targetFile = rtrim ( $targetPath, '/' ) . '/' . $name;   			
    		// Validate the file type
    		$fileTypes = array ('jpg','jpeg','gif','png' ); // File extensions
    		$fileParts = pathinfo ( $_FILES ['hallImage'] ['name'] );
    		if (in_array ( $fileParts ['extension'], $fileTypes )) {
    			$newfilename=time().rand(1000,9999).'.'.$fileParts ['extension'];	
    			$targetFile=rtrim ( $targetPath, '/' ) . '/'. $newfilename;
    			move_uploaded_file($tempFile,$targetFile);
    			echo $newfilename;
    		} else {
    			echo 'Invalid file type.';
    		}
    	}
    }
   /**
     * 删除添加图片
     */
    function delpic(){
    	$pic=iconv('utf-8','gbk',I('pic'));
    	$picurl='./Uploads/'.CPUID.'/'.$pic;
    	@unlink($picurl);
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
    	$title=array(); 	
    	$title['0']="订单编号";
    	$title['1']="订单间时";
    	$title['2']="会员/手机";
    	$title['3']="付款类型";
    	$title['4']="订单总额";
    	$title['5']="已付款金额";
    	$title['6']="订单差额";
    	$title['7']="收款状态";
    	$title['8']="支付方式";
    	$title['9']="订单状态";
    	$title['10']="开票状态";  
    	$title['11']="操作员";  	
    	
    	
    	
   		$mode=D('WholeReserve');
   		$mode1=D('WholeInvoiceDetails');		
		$data=array();
//		$data['eltstate']=3;
		$data['paymentState']=2;//获取支付成功的订单	
		
		
   		 if(isset($_REQUEST['state'])&&$_REQUEST['state']!=''){
		
			$data['state']=$_REQUEST['state'];
		
		}else{
		
			$data['eltstate']=3;
		
		}
	 	if(isset($_REQUEST['id'])&&$_REQUEST['id']!=''){
		
			$data['id']=$_REQUEST['id'];
		
		}
		if(isset($_REQUEST['start'])){
			if($_REQUEST['start'])
			$data['start_paymentTime']=strtotime($_REQUEST['start']);
		}
	 	if(isset($_REQUEST['end'])){
	 		if($_REQUEST['end'])
			$data['end_paymentTime']=strtotime($_REQUEST['end']);
		}
		

        
	 	$list=$mode->getlist($data);
	 	
	 	
	 	
	 	
	 	$orders=array();
	 	
		foreach($list as $k=> $v){	
			

			$order=array();	
			$order['id']=$v['id'];
			$order['time']=date('Y-m-d',$v['viewingDate']);
			$order['tel']=$v['tel'];
			$order['paymentypeName']=$v['paymentypeName'];
			$order['total']=$v['total'];
			$order['prepay']=$v['prepay'];
			$order['cr']=$v['total']-$v['prepay']; 
			
			
			$realNam='';
					
			if($v['state']==1) { 
				$receivables='部分收款';   
                $stateflag='未完成';
                
                
                
                	
            }elseif($v['state']==2) {
            	$receivables='全部收款'; 
                $stateflag='已完成';
                 
//                $order['stateflag']='已验证'; 			
    			$data=array();
	    		$data['uid']=$v['adminUid'];
	    		$return=$mode->admin_getlist($data,4);
	    		
	    		$realNam=$return['realName'];         
            }elseif($v['state']==3){
                $stateflag='退订';
                $receivables='已退款';      
            }
            $order['receivables']=$receivables; 
			if($v['paymentMethod']==1) {	
				$order['paymentMethodflag']='余额支付';
			}elseif($v['paymentMethod']==2) {
				$order['paymentMethodflag']='支付宝支付';			
			}elseif($v['paymentMethod']==3){
				$order['paymentMethodflag']='银联支付';		
			}
            $order['stateflag']=$stateflag; 
            
//        	if($v['paymentState']==1) {    
//                $order['paymentStateflag']='等待支付';
//            }elseif($v['paymentState']==2) {
//                $order['paymentStateflag']='完成支付';           
//            }elseif($v['paymentState']==3){
//                $order['paymentStateflag']='支付失败';      
//            }
            
            
            
            
			$data=array();
			$data['reserveId']=$v['id'];
			

			
			$order['invoiceflag']='未开票';
			$ret=$mode1->getlist($data,4);
//			dump($ret);
			if($ret&&$ret['state']==1){
				$order['invoiceflag']='已开票';
			
			}
             $order['realNam']=$realNam;
            
			
	
			$orders[]=$order;
		}
    	
    	
    	
    	
    	
    
        exportexcel($orders,$title);
    }
	
	
	
}