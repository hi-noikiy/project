<?php
/**
 *
 * 包场控制器类
 * @author jcjtim
 *
 */
namespace Home\Controller;
use Think\Controller;
class BookwholeController extends BookController {
	/**
	 * 订单选择展示内容
	 */
	public function index() {	
		if(!session('ftuser')){
			session('url',U());
			$this->redirect('public/login');
			die();
		}
		$model=D('Whole');
		$year=date('Y');
		$m=date('m');
		$d=date('j')+3;
		$list=array();
		$time=mktime(0, 0, 0, $m, $d, $year);		
//		setcookie('time',$time,'','/');
		
		setcookie( "time",  $time,  time() + 3600,  "/");
		$data['start_time']=$time;
		$data['end_time']=$time;
		$data['sort']='time asc';
		$list=$model->plan_number_getlist_for_indx($data);
		for ($i = 0; $i < 10; $i++) {			
			$pl=array();
			$time=mktime(0, 0, 0, $m, $d, $year);
			$pl['name']='周';
			$pl['name'].=$this->week_over(date('w',$time));
			$pl['name'].=date('m-d',$time);
			$pl['time'].=$time;
			$title[]=$pl;
			$d++;
		}	
		cookie('videoId',null);
		$model->get_onpay();//取消15分钟意外的订单
		//获取是否有15分钟以内的订单
		$userInfo = session('ftuser');
		if($userInfo){
			$data['gtpaymentTime']=time()-15*60;
			$data['paymentState']=1;
			$data['uid']=$userInfo['id'];
			$ret=$model->reserve_getlist($data,4);
			if($ret){
				$this->assign('flag','111');
				$this->assign('flag_id',$ret['id']);
			
			}	
		}
		setcookie( "package_price",  null,  time() + 3600,  "/");
		setcookie( "service_price",  null,  time() + 3600,  "/");
		$this->assign('title',$title);
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 取消订单重新购买
	 */
	public function index_cancel() {		
		$model=D('Whole');
		$data=array();
		if(I('id')){
			$data['id']=I('id');
			$data['paymentState']=3;
			$model->update_reserve($data);	
		}
	}
	/**
	 * 获取日期后获取场次
	 */
	public function search_plan_theme() {
		$model=D('Whole');
		$data=array();
		$data['time']=$_REQUEST['time'];
		$data['endTime']=$_REQUEST['endTime'];
		$list=$model->find_video_do($data);
		if($list){			
			$this->success('有影厅可以选择');	
		}else{
			$this->error('你选择的时段没有影厅可以接受预定！');		
		}	
	}
	/**
	 * 获取日期后获取场次
	 */
	public function setftime() {
		$data=array();
		$time=strtotime($_POST['time']);
		$data['start_time']=$time;
		$data['end_time']=$time;
		$data['sort']='time asc';
		$data['state']=2;//获取已经配置的场次
		$model=D('Whole');
		$list=$model->plan_number_getlist($data);
		$ret=array();
		foreach ($list as $v) {
			$ret[]=date('H:i',$v['time']);
		}
		echo json_encode($ret);
	}
	/**
	 * 获取日期后获取场次
	 */
	public function search_plan() {
		$model=D('Whole');
		$data=array();
		$time=$_POST['time'];
		$data['start_time']=$time;
		$data['end_time']=$time;
		if(isset($_COOKIE['videoId'])&&$_COOKIE['videoId'])
		$data['videoId']=$_COOKIE['videoId'];
		$list=$model->plan_number_getlist_for_indx($data);	
		$this->success('查询成功',$list);
	}	
	/**
	 * 获取日期后获取场次v1
	 */
	public function search_theme() {
		$data=array();
		$time=strtotime($_POST['time']);
		$data['gtviewingDate']=$time;
		$data['ltendTime']=$time;
//		$data['state']=2;//获取已经配置的场次
		$model=D('Whole');
		$list=$model->reserve_getlist($data);
		$ret=array();
		foreach ($list as $v) {
			$ret[]=date('H:i',$v['time']);
		}
		echo json_encode($ret);
	}	
	/**
	 * 获取热映的影片
	 */
	public function search() {
		$model=D('Plan');
		$list=$model->getFilms();
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 通过时间获取影片
	 */
	public function search_file() {
		$model=D('Whole');
		$data=array();
		if(isset($_REQUEST['stime'])&&isset($_REQUEST['svtime'])) {
			$data['time']=strtotime($_REQUEST['stime'].' '.$_REQUEST['svtime']);
		}elseif(isset($_REQUEST['stime'])&&!isset($_REQUEST['svtime'])) {
			$data['start_time']=strtotime($_REQUEST['stime']);
			$data['end_time']=strtotime($_REQUEST['stime']);
		}
		$ret=$model->getlist_from_filmNo($data);
		echo json_encode($ret);
	}
	/**
	 * 获取场次查询内容
	 */
	public function schedule() {
		$model=D('Whole');
		$lists=array();
		$data=array();
		$data['filmNo']=$_REQUEST['filmNo'];
		$ret2=$model->film_getlist($data,4);
		$duration=$ret2['duration']?$ret2['duration']:90;
		$duration=$duration*60;
		if(isset($_REQUEST['data'])&&$_REQUEST['data']!='') {
			$time=strtotime($_REQUEST['data']);
			$data['start_time']=$time;
			$data['end_time']=$time;
			$data['getField']='min(price) as min';
			$min=$model->getlist_from_filmNo($data,6);
			$list=$model->getlist_from_filmNo($data);
			foreach($list as $k=>$v){
				$list[$k]['cc']=date('H:i',$v['time']);
				$list[$k]['dd']=date('H:i',$v['time']+$duration);
			}
			$pl=array();
			$pl['min']=$min['min'];
			$pl['time']=date('m月d日',$time);
			$lists['time'][]=$pl;
			$lists['list'][$time]=$list;
		}else {
			$year=date('Y');
			$m=date('m');
			$d=date('j')+3;
			for ($i = 0; $i < 10; $i++) {
				$list=array();
				$time=mktime(0, 0, 0, $m, $d, $year);
				$data['start_time']=$time;
				$data['end_time']=$time;
				$data['getField']='min(price) as min';
				$min=$model->getlist_from_filmNo($data,6);
				if($min['min']!=NULL) {
					$list=$model->getlist_from_filmNo($data);
					foreach($list as $k=>$v){
						$list[$k]['cc']=date('H:i',$v['time']);
						$list[$k]['dd']=date('H:i',$v['time']+$duration);
					}
					$pl=array();
					$pl['min']=$min['min'];
					$pl['time']=date('m月d日',$time);
					$lists['time'][]=$pl;
					$lists['list'][$time]=$list;
				}
				$d++;
			}
		}
		$this->assign('list',$lists);
		$this->display();
	}
	/**
	 * 主题展示
	 */
	public function theme() {
		$mode=D('Whole');
		$data=array();
		$data['state']=1;	
		$data['time']=$_COOKIE['viewingDate'];
		$data['endTime']=$_COOKIE['endTime'];
		$list=$mode->video_getlist_for_theme($data);
		$this->assign('list',$list);
		$this->display();
	}

	/**
	 * 电影列表
	 */
	public function filmlist() {
		$mode=D('Whole');
		$data=array();
//		if(I('time')) {
//		
//			$data['time']=I('time');
//		}else{
//		
//			$data['time']=isset($_COOKIE['time'])?$_COOKIE['time']:time();
//		
//		}
		$data['time']=time();
				
		$list=$mode->cinema_plan_getlist($data,5);				
		foreach($list as $k=>$v) {			
			$data=array();
			$data['filmNo']=$v['filmNo'];
			$ret=$mode->film_getlist($data,4);
			if($ret){		
				$list[$k]['duration']=$ret['duration'];
				$list[$k]['cast']=$ret['cast'];
				$list[$k]['image']=C('WHOLE_UPLOAD').'/'.$ret['image'];			
			}else{
				$list[$k]['duration']=90;
				$list[$k]['image']=C('FILM_IMG_URL');			
			}					
		}
//		dump($list);
		$model=D('Whole');
		$year=date('Y');
		$m=date('m');
		$d=date('j')+3;
		$list1=array();
		$time=mktime(0, 0, 0, $m, $d, $year);
		$data['start_time']=$_COOKIE['time'];
		$data['end_time']=$_COOKIE['time'];
		$data['sort']='time asc';
		$data['videoId']=$_COOKIE['videoId'];
//		dump($data);
		$list1=$model->plan_number_getlist_for_indx($data);
		for ($i = 0; $i < 10; $i++) {
			$pl=array();
			$time=mktime(0, 0, 0, $m, $d, $year);
			$pl['name']='周';
			$pl['name'].=$this->week_over(date('w',$time));
			$pl['name'].=date('m-d',$time);
			$pl['time'].=$time;
			$title[]=$pl;
			$d++;
		}	
		$this->assign('title',$title);
		$this->assign('list',$list);
		$this->assign('list1',$list1);
		$this->display();
	}
	/**
	 * 主题环境背景
	 */
	public function environment() {
		$mode=D('Whole');
		if(isset($_GET['id'])){
			$data=array();
			$data['videoId']=$_GET['id'];
			$list=$mode->video_map_getlist($data);
			$this->assign('list',$list);
		}
		$this->display();
	}
	/**
	 * 套餐和附加服务的获取
	 */
	public function service() {
		$model=D('Whole');
		$data=array();
		$data['status']=1;
		$list2=$model->package_getlist($data);	
		if(isset($_COOKIE['package_price'])){		
			$package_price=json_decode($_COOKIE['package_price'],true);
			foreach($list2 as $k=>$v){
				$list2[$k]['num']=0;
				foreach($package_price as $v1) {				
					if($v['id']==$v1['id']){					
						$list2[$k]['num']=$v1['num'];					
					}									
				}			
			}		
		}		
		$list['package']=$list2;
		$list3=$model->service_getlist($data);
		foreach($list3 as $k=>$v){
			$list3[$k]['num']=0;	
		}	
		if(isset($_COOKIE['service_price'])){		
			$service_price=json_decode($_COOKIE['service_price'],true);
			foreach($list3 as $k=>$v){
				$list3[$k]['num']=0;
				foreach($service_price as $v1) {				
					if($v['id']==$v1['id']){					
						$list2[$k]['num']=$v1['num'];					
					}									
				}			
			}		
		}		
		$list['service']=$list3;
		$this->assign('list',$list);
		$this->display();
	}

	/**
	 * 确认订单展示
	 */
	public function confirmOrder (){
		$userInfo = session('ftuser');
		if(isset($_REQUEST['film'])){
			$this->get_onpay();
			$mode=D('whole');
			$data=array();
			$data['viewingDate']=strtotime($_REQUEST['stime'].' '.$_REQUEST['svtime']);
			$data['videoId']=$_REQUEST['index_theme_id'];
			$data['paymentState']=2;
			$ret=$mode->reserve_getlist($data);
			if($ret) {
				$this->error('该主题影厅的观影时间已经被预定，请选择其他场次！');
				exit;
			}				
			$data=array();
			$data['viewingDate']=strtotime($_REQUEST['stime'].' '.$_REQUEST['svtime']);
			$data['videoId']=$_REQUEST['index_theme_id'];
			$data['gtpaymentTime']=time()-15*60;
			$data['paymentState']=1;
			$data['nequid']=$userInfo['id'];
			$ret=$mode->reserve_getlist($data);
			if($ret) {
				$this->error('该主题影厅的观影时间场次已经被预定，请选择其他场次！！');
				exit;
			}				
			unset($_SESSION['confirmOrder']);
			$_SESSION['confirmOrder']['film']=$_REQUEST['film'];
			$_SESSION['confirmOrder']['film_price']=$_REQUEST['film_price'];
			$_SESSION['confirmOrder']['film_fullHousePrice']=$_REQUEST['film_fullHousePrice'];
			$_SESSION['confirmOrder']['film_favorablePrice']=$_REQUEST['film_favorablePrice'];
			$_SESSION['confirmOrder']['film_serviceCharge']=$_REQUEST['film_serviceCharge'];
			$_SESSION['confirmOrder']['file_ord_price']=$_REQUEST['file_ord_price'];
			$_SESSION['confirmOrder']['file_ord_tolprice']=$_REQUEST['file_ord_tolprice'];
			$_SESSION['confirmOrder']['file_ord_tolserviceCharge']=$_REQUEST['file_ord_tolserviceCharge'];
			$_SESSION['confirmOrder']['film_filmNo']=$_REQUEST['film_filmNo'];
			$_SESSION['confirmOrder']['stime']=$_REQUEST['stime'];
			$_SESSION['confirmOrder']['svtime']=$_REQUEST['svtime'];
			$_SESSION['confirmOrder']['votesNum']=$_REQUEST['votesNum'];
			$_SESSION['confirmOrder']['all_price']=$_REQUEST['all_price'];
			$_SESSION['confirmOrder']['index_service_price']=json_decode($_REQUEST['index_service_price'],true);
			$_SESSION['confirmOrder']['package_price']=json_decode($_REQUEST['package_price'],true);
			$_SESSION['confirmOrder']['theme']=$_REQUEST['theme'];
			$_SESSION['confirmOrder']['index_theme_id']=$_REQUEST['index_theme_id'];
			$_SESSION['confirmOrder']['index_theme_seating']=$_REQUEST['index_theme_seating'];
			if(isset($_REQUEST['index_detail']))
			$_SESSION['confirmOrder']['index_detail']=$_REQUEST['index_detail'];			
			//			wlog('确认订单' ,'bookwhole');
			//			dump($_SESSION['confirmOrder']);
			$this->success('操作成功');
			exit;
		}
		if (!$userInfo) {
			$_SESSION['url']=U('Bookwhole/confirmOrder');
			$this->redirect('public/login');
		}
		$mobileUser=$this->getBindCardInfo($userInfo);
		$this->assign('mobile',$mobileUser['mobile']);
		$hall=D('whole_video_information')->where(array('videoCode'=>$_COOKIE['videoCode']))->find();
		$this->assign('hall',$hall);
		$this->assign('list',json_decode($_COOKIE['package_price'],true));
		$this->assign('list1',json_decode($_COOKIE['service_price'],true));
		$this->display();
	}

	public function consigneeEmpty (){
		$this->display();
	}

	/**
	 * 收货人展示
	 */
	public function addressReceiving (){
		$this->display();
	}

	/**
	 * 展示修改收货人内容
	 */
	public function editaddressReceiving (){
		$mode=D('whole');
		$data=array();
		$data['id']=$_REQUEST['id'];
		$list=$mode->consignee_getlist($data,4);
		$this->assign($list);
		$this->display();
	}
	/**
	 * 收获人地址删除
	 */
	public function addressReceiving_del (){
		$mode=M('whole_consignee');
		$wherearray=array();
		$wherearray['id']=array('EQ',$_REQUEST['id']);
		$ret=$mode->where($wherearray)->delete();
		$this->success('操作成功');
	}
	/**
	 * 收获人地址展示、添加、修改.
	 */
	public function consigneeInfo (){
		$userInfo = session('ftuser');
		//添加操作
		if(isset($_REQUEST['addressReceiving_name'])){
			$data=array();
			if($_REQUEST['addressReceiving_name'])
			$data['name']=$_REQUEST['addressReceiving_name'];
			if($_REQUEST['addressReceiving_phone'])
			$data['phone']=$_REQUEST['addressReceiving_phone'];
			if($_REQUEST['addressReceiving_address'])
			$data['address']=$_REQUEST['addressReceiving_address'];
			if($userInfo['id'])
			$data['uid']=$userInfo['id'];
			$mode=M('whole_consignee');
			$ret=$mode->add($data);
			$this->success('操作成功');
			exit;
		}
		//修改操作
		if(isset($_REQUEST['editaddressReceiving_name'])){
			$data=array();
			if($_REQUEST['editaddressReceiving_name'])
			$data['name']=$_REQUEST['editaddressReceiving_name'];
			if($_REQUEST['editaddressReceiving_phone'])
			$data['phone']=$_REQUEST['editaddressReceiving_phone'];
			if($_REQUEST['editaddressReceiving_address'])
			$data['address']=$_REQUEST['editaddressReceiving_address'];
			$mode=M('whole_consignee');
			$wherearray=array();
			$wherearray['id']=array('EQ',$_REQUEST['id']);
			$ret=$mode->where($wherearray)->save($data);
			$this->success('操作成功');
			exit;
		}
		if(isset($_REQUEST['invoice_type'])){
			$_SESSION['invoice_type']=$_REQUEST['invoice_type'];
		}
		$mode=D('whole');
		$data['uid']=$userInfo['id'];
		//		wlog('查询isUId---:'.$userInfo['id']);
		$list=$mode->consignee_getlist($data);
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 改变默认收货人
	 */
	public function changmark (){
		$mode=D('whole');
		$data=array() ;
		$data['id']=$_REQUEST['id'];
		$list=$mode->consignee_getlist($data,4);
		$mode1 = M('whole_consignee');
		$data=array() ;
		$data['mark']='0';
		$wherearray=array();
		$wherearray['uid']=array('EQ',$list['uid']);
		$mode1->where($wherearray)->save($data);
		$data=array() ;
		$data['mark']='1';
		$wherearray=array();
		$wherearray['id']=array('EQ',$_REQUEST['id']);
		$mode1->where($wherearray)->save($data);
	}
	/**
	 *发票展示内容
	 */
	public function invoice (){
		if(isset($_SESSION['confirmOrder']['invoice_type'])&&$_SESSION['confirmOrder']['invoice_type']!=''){
			$_SESSION['invoice_type']=$_SESSION['confirmOrder']['invoice_type'];
		}
		$userInfo = session('ftuser');
		$mode=D('whole');
		$data['uid']=$userInfo['id'];
		$data['mark']=1;
		//      wlog('查询isUId---:'.$userInfo['id']);
		$list=$mode->consignee_getlist($data,4);
		$this->assign('name',$list['name']);
		$this->display();
	}
	/**
	 * 支付展示页面、发票填加功能
	 */
	public function payoff (){	
		//用户登录检测
		$userInfo = session('ftuser');
		if (!$userInfo) {
			$_SESSION['url']=U('Bookwhole/payoff');
			$this->error('用户未登入！');
		}
		$hall=D('whole_video_information')->where(array('videoCode'=>$_COOKIE['videoCode']))->find();
		//订单确认。
		if(isset($_REQUEST['confirmOrder_tel'])){
			$_COOKIE['confirmOrder_tel']=$_REQUEST['confirmOrder_tel'];
			$mode=D('Whole');
			$data=array();
			$data['total']=$_COOKIE['all_price'];
			$data['num']=$_REQUEST['num'];
			if($data['num']>$hall['seating']){
				$this->error('座位数不够');
			}elseif($data['num']>$hall['passNum']){
				$data['total']+=round($hall['unitPrice']*($data['num']-$hall['passNum']),2);
			}
			$data['viewingDate']=$_COOKIE['viewingDate'];
			$data['endTime']=$_COOKIE['endTime'];
			$data['videoId']=$_COOKIE['videoId'];
			$data['videoCode']=$_COOKIE['videoCode'];
			$data['topicName']=$_COOKIE['topicName'];;
//			$data['num']=$_SESSION['confirmOrder']['votesNum'];
			$data['paymentState']=1;
			$data['state']=1;
			$data['changeState']='1';
			
			$data['paymentTime']=time();
			$data['tel']=$_COOKIE['confirmOrder_tel'];
			$data['filmNo']=$_COOKIE['filmNo'];
			$data['filmName']=$_COOKIE['filmName'];
			$data['planPrice']=$_COOKIE['planPrice'];
			$data['uid']=$userInfo['id'];
			//if(isset($_SESSION['confirmOrder']['index_detail']))
			$data['detail']=$_COOKIE['detail'];
			$datainvoice=array();
			//验证是否是重复提交的内容
			$datac=array();
			$datac['viewingDate']=$data['viewingDate'];
			$datac['videoId']=$data['videoId'];
			$datac['gtpaymentTime']=time()-20;
			$datac['paymentState']=1;
			$ret=$mode->reserve_getlist($datac);
			if($ret) {
				$this->error('请不要重复提交订单！');
				exit;
			}
			$id=$mode->add_payoff($data,isset($_COOKIE['package_price'])?json_decode($_COOKIE['package_price'],true):'',isset($_COOKIE['service_price'])?json_decode($_COOKIE['service_price'],true):'');
			$_SESSION['confirmOrder']['id']=$id;
			wlog('用户id：'.$userInfo['id'].'确认订单,订单id:'.$id.',提交总金额：'.$_COOKIE['all_price'],'bookwhole','订单过程');
			$this->success('添加成功');
		}
		//发票添加到session
		if(isset($_REQUEST['invoice_type'])){
			$_SESSION['confirmOrder']['invoice_type']=$_REQUEST['invoice_type'];
			$_SESSION['confirmOrder']['invoice_name1']=isset($_REQUEST['invoice_name1'])?$_REQUEST['invoice_name1']:'';
			$_SESSION['confirmOrder']['invoice_content']=isset($_REQUEST['invoice_content'])?$_REQUEST['invoice_content']:'';
			$_SESSION['confirmOrder']['invoice_name2']=isset($_REQUEST['invoice_name2'])?$_REQUEST['invoice_name2']:'';
			$_SESSION['confirmOrder']['invoice_identificationNum']=isset($_REQUEST['invoice_identificationNum'])?$_REQUEST['invoice_identificationNum']:'';
			$_SESSION['confirmOrder']['invoice_address']=isset($_REQUEST['invoice_address'])?$_REQUEST['invoice_address']:'';
			$_SESSION['confirmOrder']['invoice_phone']=isset($_REQUEST['invoice_phone'])?$_REQUEST['invoice_phone']:'';
			$_SESSION['confirmOrder']['invoice_bank']=isset($_REQUEST['invoice_bank'])?$_REQUEST['invoice_bank']:'';
			$_SESSION['confirmOrder']['invoice_bankAccount']=isset($_REQUEST['invoice_bankAccount'])?$_REQUEST['invoice_bankAccount']:'';
		}
		unset($_SESSION['invoice_type']);
		$mode=D('Whole');
		$list=$mode->payment_type_getlist($data);//获取付款方式
		$data=array();
		$data['id']=$_SESSION['confirmOrder']['id'];
		if(isset($_REQUEST['id'])) {
			$data['id']=$_REQUEST['id'];
			$_SESSION['confirmOrder']['id']=$_REQUEST['id'];
		}
		$ret=$mode->reserve_getlist($data,4);
		
		$_COOKIE['all_price']=$ret['total'];
		
		$cinemaCode='35014171'; //南华
		$cinema=D('cinema')->find($cinemaCode);
		$payConfig=json_decode($cinema['payConfig'],true);
		$wxConfig=$payConfig['weixinpayConfigWap'];
		if(!empty($wxConfig['appid'])){
			$openid=session('openid'.$cinemaCode);
			if(empty($openid)){
				$openid=cookie('openid'.$cinemaCode);
			}
			$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
			$jsurl=strtolower('http://' . $_SERVER['HTTP_HOST'] . U('',I('request.')));
			//$jsurl='http://testapi.zmaxfilm.com'. U('');
			if (empty($openid)&&empty($_GET['code'])){
				$url = $jsApi->createOauthUrlForCode(urlencode($jsurl));
				Header("Location: $url");
				die();
		
			}elseif(empty($openid)){
				$code = $_GET['code'];
				$jsApi->setCode($code);
				$openid = $jsApi->getOpenId();
				session('openid'.$cinemaCode,$openid);
				cookie('openid'.$cinemaCode,$openid,3600);
			}else{
				cookie('openid'.$cinemaCode,$openid,3600);
				session('openid'.$cinemaCode,$openid);
			}
		
			$wxdata['appid']=$wxConfig['appid'];
			$wxdata['timestamp']=$timestamp=time();
			$wxdata['wxnonceStr']=$wxnonceStr = $jsApi->createNoncestr();
			$wxticket=wx_get_jsapi_ticket($wxConfig['appid'],$wxConfig['appsecret']);
			$wxOri = 'jsapi_ticket=' . $wxticket.'&noncestr='.$wxnonceStr.'&timestamp='.$timestamp.'&url='.$jsurl;
			$wxdata['wxSha1']=$wxSha1 = sha1($wxOri);
		}
		$this->assign('orderCode',$data['id']);
		$this->assign('data',$wxdata);
		$this->assign('list',$list);
		$this->assign('token',$this->token);
		$this->display();
	}
	//支付成功
	public function paymentSuccess (){
		$userInfo = session('ftuser');
		/*if(isset($_REQUEST['paymentTypeId'])){
			if($_REQUEST['paymentMethod']==1){
				//				$this->error('余额支付！');
				//				exit;
			}
			if($_REQUEST['paymentMethod']==2){
				//$Return = file_get_contents('http://www.zrfilm.com/index.php?route=system/api/login' . $str);
				// $Return = json_decode($Return, true);
				//				$this->error('支付包支付！');
				//				exit;
					
			}
			if($_REQUEST['paymentMethod']==3){
				//	 $Return = file_get_contents('http://www.zrfilm.com/index.php?route=system/api/login' . $str);
				//$Return = json_decode($Return, true);
				//
				//				$this->error('网银支付！');
				//				exit;
					
			}
			$code=$this->GetRandStr(5);
			$_SESSION['confirmOrder']['code']=$code;
			$mode=D('Whole');
			$data=array();
			$data['id']=$_SESSION['confirmOrder']['id'];
			//			$data['viewingDate']=strtotime($_SESSION['confirmOrder']['stime'].' '.$_SESSION['confirmOrder']['svtime']);
			//
			//			$data['videoId']=$_SESSION['confirmOrder']['index_theme_id'];
			//			$data['num']=$_SESSION['confirmOrder']['votesNum'];
			$data['paymentTypeId']=$_REQUEST['paymentTypeId'];
			$data['paymentMethod']=$_REQUEST['paymentMethod'];
			$data['paymentState']=1;
			//			$data['state']=1;
			//			$data['changeState']='1';
			//			$data['total']=$_SESSION['confirmOrder']['all_price'];
			$data['prepay']=$_REQUEST['payoff_now_price'];
			$data['code']=$code;
			//			$data['paymentTime']=time();
			//			$data['tel']=$_SESSION['confirmOrder']['confirmOrder_tel'];
			//			$data['filmNo']=$_SESSION['confirmOrder']['film_filmNo'];
			$data['uid']=$userInfo['id'];
			//$data['detail']=$_SESSION['confirmOrder']['index_detail'];
			$datainvoice=array();
			if(isset($_SESSION['confirmOrder']['invoice_type'])) {
				$datainvoice['type']=$_SESSION['confirmOrder']['invoice_type'];
				if($_SESSION['confirmOrder']['invoice_type']==1) {
					$datainvoice['type']=$_SESSION['confirmOrder']['invoice_type'];
					$datainvoice['name']=$_SESSION['confirmOrder']['invoice_name1'];
					$datainvoice['content']=$_SESSION['confirmOrder']['invoice_content'];
				}else {
					$datainvoice['type']=$_SESSION['confirmOrder']['invoice_type'];
					$datainvoice['name']=$_SESSION['confirmOrder']['invoice_name2'];
					$datainvoice['identificationNum']=$_SESSION['confirmOrder']['invoice_identificationNum'];
					$datainvoice['address']=$_SESSION['confirmOrder']['invoice_address'];
					$datainvoice['phone']=$_SESSION['confirmOrder']['invoice_phone'];
					$datainvoice['bank']=$_SESSION['confirmOrder']['invoice_bank'];
					$datainvoice['bankAccount']=$_SESSION['confirmOrder']['invoice_bankAccount'];
				}
			}
			wlog('用户id：'.$userInfo['id'].'支付订单,订单id:'.$_SESSION['confirmOrder']['id'].',支付金额：'.$_REQUEST['payoff_now_price'],'bookwhole');
			//验证是否是重复提交的内容
			//
			//			$datac=array();
			//			$datac['viewingDate']=$data['viewingDate'];
			//			$datac['videoId']=$data['videoId'];
			//			$datac['paymentState']=1;
			//			$datac['uid']=$userInfo['id'];
			//			$ret=$mode->reserve_getlist($datac);
			//			if($ret) {
			//				$this->error('请不要重复提交订单！');
			//				exit;
			//			}
			//	dump($_SESSION['confirmOrder']);
			$mode->eidt_payoff($data,'','',$datainvoice);
			$id=$_SESSION['confirmOrder']['id'];
			$orderInfo=array();
			$orderInfo['id']=$id;
			$orderInfo['prepay']=$_REQUEST['payoff_now_price'];
			$orderInfo['tel']=$_SESSION['confirmOrder']['confirmOrder_tel'];
			$this->alipay($userInfo,$orderInfo);
			exit;
//			$this->success('支付成功！');
			
		}*/
		$mode3=	M('whole_invoice_details');
		$invoice=$mode3->where(array('reserveId'=>$_SESSION['confirmOrder']['id']))->find();
		$datainvoice=array();
		if(isset($_SESSION['confirmOrder']['invoice_type'])&&empty($invoice)) {
			$datainvoice['type']=$_SESSION['confirmOrder']['invoice_type'];
			if($_SESSION['confirmOrder']['invoice_type']==1) {
				$datainvoice['type']=$_SESSION['confirmOrder']['invoice_type'];
				$datainvoice['name']=$_SESSION['confirmOrder']['invoice_name1'];
				$datainvoice['content']=$_SESSION['confirmOrder']['invoice_content'];
			}else {
				$datainvoice['type']=$_SESSION['confirmOrder']['invoice_type'];
				$datainvoice['name']=$_SESSION['confirmOrder']['invoice_name2'];
				$datainvoice['identificationNum']=$_SESSION['confirmOrder']['invoice_identificationNum'];
				$datainvoice['address']=$_SESSION['confirmOrder']['invoice_address'];
				$datainvoice['phone']=$_SESSION['confirmOrder']['invoice_phone'];
				$datainvoice['bank']=$_SESSION['confirmOrder']['invoice_bank'];
				$datainvoice['bankAccount']=$_SESSION['confirmOrder']['invoice_bankAccount'];
			}
		
			$datainvoice['reserveId']=$_SESSION['confirmOrder']['id'];
			M('wholeReserve')->add_invoice($datainvoice,isset($userInfo['id'])?$userInfo['id']:'');
			$mode3->add($datainvoice);
		}else{
			$reserve=M('wholeReserve')->find($_SESSION['confirmOrder']['id']);
			$this->assign('reserve',$reserve);
			$this->display();
		}
	}
	
	/**
	 * 微信支付
	 * 主方法
	 * @param [get] [orderno] [订单编号]
	 */
	public function main() {
		$payType=I('payType');
		$orderno=I('orderid');
		$reserve = M('wholeReserve')->field('total')->find($orderno);
		//$mobile=$reserve['tel'];
		$user=session('ftuser');
		//$user=$this->getBindUserInfo($user);
		//获取用户订单号（非必须）
		$ratio=I('ratio');
		$fee=round($reserve['total']*$ratio/100,2);
		$fee=0.01;
		$sdata['id']=$orderno;
		$sdata['ratio']=$ratio;
		$sdata['payType']=$payType;
		$sdata['prepay']=$fee;
		M('wholeReserve')->save($sdata);
		$cinemaCode='35014171'; //南华
		$cinema=D('cinema')->find($cinemaCode);
		$payConfig=json_decode($cinema['payConfig'],true);
		$wxConfig=$payConfig['weixinpayConfigWap'];
		$jsApi = new \Org\Wechat\Wxjspay($wxConfig);
		//统一支付接口类
		$unifiedOrder = new \Org\Wechat\UnifiedOrder($wxConfig);
		$openid=session('openid'.$cinemaCode);
		/*-----------------------------必填--------------------------*/
		$unifiedOrder->setParameter('body', 'WAP包场支付' . $fee);//商品描述岚樨微支付平台
		$unifiedOrder->setParameter('out_trade_no', $orderno);//商户订单号
		$unifiedOrder->setParameter('total_fee', $fee * 100);//总金额（微信支付以人民币“分”为单位）
		/*-------------------------------------------------------*/
		$unifiedOrder->setParameter('openid', $openid);//获取到的OPENID
		$unifiedOrder->setParameter('notify_url', C('PAY_URL').'home/whole/weixinpay_wap.html');//通知地址
		$unifiedOrder->setParameter('trade_type', 'JSAPI');//交易类型
		
		$prepay_id = $unifiedOrder->getPrepayId();
		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_id);
		$jsApiParameters = $jsApi->getParameters();
		$this->jsApiParameters = $jsApiParameters;
		echo json_encode($this->jsApiParameters);
	
	}
	/**
	 * 支付宝支付
	 *
	 * @param string $title
	 * @param float $fee
	 * @see AlipaySubmit::buildRequestHttp()
	 * @see AlipaySubmit::parseResponse()
	 * @see AlipaySubmit::buildRequestParaToString()
	 */
	public function alipay($userInfo,$orderInfo) {
		
		
		$cinemaCode='35014171';
//    	$cinema = S('GETCINEMABYCODE' . $cinemaCode);
//    	if (empty($cinema)) {
    		$cinema = D('cinema')->find($cinemaCode);
//    		S('GETCINEMABYCODE' . $cinemaCode, $cinema, 3600*24*7);
//    	}
		$payConfig=json_decode($cinema['payConfig'],true);
    	$alipayConfig=$payConfig['alipayConfig'];				
//		$shareNo ='2088302990741076';
		$shareNo =$alipayConfig['partnerId'];
//		$shareEmail = '3299519712@qq.com';
		$shareEmail = $alipayConfig['sellerEmail'];
		$orderNo = date('Ymdhis').'N'.$orderInfo['id'];
		$req_id = date ( 'Ymdhis' );
		$orderTotal=$orderInfo['prepay'];
		$orderTotal = '0.01';
		$subject = '华南包场测试支付金额为'.$orderInfo['prepay'].'支付订单号'.$orderNo;
		//成功返回
		$call_back_url=C('bookwhole.call_back_url');
		//异步通知页面
		$notify_url=C('bookwhole.notify_url');
		//失败返回
		$merchant_url=C('bookwhole.merchant_url');
		$conf = array(
					'service' => 'alipay.wap.trade.create.direct',
					'format' => 'xml',
					'v' => '2.0',
					'partner' =>$shareNo,
					'req_id' => $req_id,
					'sec_id' => '0001',
					'_input_charset' => 'utf-8',
					'sign' => '',
					'req_data' =>'',
					'cacert'    => './Public/static/alipay_cacert.pem',
					'transport'    => 'http',
		);
		$reqConf = array(
					'subject' => $subject,
					'out_trade_no' => $orderNo,
					'total_fee' => $orderTotal, 
					'seller_account_name'  => $shareEmail,
					'call_back_url' =>  $call_back_url,
					'notify_url' => $notify_url,
					'merchant_url' => $merchant_url,
					'out_user' => 'zrfilm.com',
					'pay_expire' => '10'//订单自动关闭时间
		);
		$reqXmlString = '<direct_trade_create_req>';
		foreach ($reqConf as $key => $value) {
			$reqXmlString .= '<' . $key . '>' . $value . '</' . $key . '>';
		}
		$reqXmlString .= '</direct_trade_create_req>';
		$conf['req_data'] = $reqXmlString;
		ksort($conf);
		reset($conf);
		$sign_data = array();
		foreach ($conf as $key => $value) {
			if ($key != 'sign') {
				$sign_data[] = $key . '=' . $value;
			}
		}
///		$priKeyValue = file_get_contents('./Public/static/rsa_private_key.pem');
		$priKeyValue=$alipayConfig['privateKey'];
		$conf['sign'] = urlencode(rsaSign(implode('&', $sign_data), $priKeyValue));
		$sign_data = array();
		foreach ($conf as $key => $value) {
			$sign_data[] = $key . '=' . $value;
		}
		// 获取token
		$requestData = $this->parseResponse(urldecode(htmlspecialchars_decode(getHttpResponseGET('http://wappaygw.alipay.com/service/rest.htm?' . implode('&', $sign_data)), ENT_COMPAT)), $priKeyValue);
		if (isset($requestData['request_token'])) {
			$conf = array(
						'service' => 'alipay.wap.auth.authAndExecute',
						'format' => 'xml',
						'v' => '2.0',
						'partner' => $shareNo,
						'sec_id' => '0001',
						'_input_charset' => 'utf-8',
						'sign' => '',
						'req_data' => '<auth_and_execute_req><request_token>' . $requestData['request_token'] . '</request_token></auth_and_execute_req>'
						);
						ksort($conf);
						reset($conf);
						$sign_data = array();
						foreach ($conf as $key => $value) {
							if ($key != 'sign') {
								$sign_data[] = $key . '=' . $value;
							}
						}
						$conf['sign'] = urlencode(rsaSign(implode('&', $sign_data), $priKeyValue));
						$sign_data = array();
						foreach ($conf as $key => $value) {
							$sign_data[] = $key . '=' . $value;
						}
						$surl='http://wappaygw.alipay.com/service/rest.htm?' . implode('&', $sign_data);
						$this->success('', $surl);
		}else{
			
//			dump($requestData);
			$this->error('支付失败！');
		}
		
	}

	/**
	 * 支付提示功能
	 */
	public function pay() {
		$this->assign('token',$this->token);
		$this->display();
	}
	/**
	 * 支付宝回调函数
	 */
	public function notify()  {
		 
		//  	$rdatajson='{"service":"alipay.wap.trade.create.direct","sign":"tSX2FWB1pdorgG7oPYM4kkTp0tH+b\/KSx0H8f66cPWt3NzP8xPn7Vof2dqp470CyseNf1IKEINhyUbi+9Hzrab4dv4Bq7cZZ45p4l0kIimThlY5zkDJaHFg0cVn9j8YsTgN2RMIJ45O9GAoGdwMpoSffSpwayxCZGWt0123orPM=","sec_id":"0001","v":"1.0","notify_data":"fowVow4St+B04yPl+f8evrJgd8hE6Q645gS+qIITLJ3gSsGRBSFEhPMKQOfNEYaj8d92NxqBkHBRb2gkYY4x\/sbpHKCxaF20KvlxzefGLipfI7KnraldR+o2ZUtN3rDE6NhZ2i\/2f\/OfVqfFBsLZ34Pb5INHRASEakehhSWbW3pJekJAomYOlpeapVynvwQ0Qo8G1QE\/rrxYuBgC6R1W8Hp9zfV+liyt8axgw0AGeP0lPuxA16WOxhajAlTlqQy62mDRLXsmfr86aMWhl0bdu12L8CC2O91xlCGPyAHBKjb0vARi\/aQ7hLx9Gqv6\/C9cGX0c3xBS\/rliMESk0JKCFmqJEct6fOsWQ2EMq8KR31AxUYGPyLpnDV4GCLZOgifggTILci8oCzXX0sfXD3nf1+PfdqhPuXERbS1k7qi\/4ftK\/egC0Qgg57A7eNqH3A3zHAvgUgYvw0MOSY8ciJPk1Zrgp96kp\/\/5SIQ+MfBPnJ2DJ5hVT7VnCjMeLHEvRPqGQlBM\/usjcKXgizKuwNhchnJ8iwy526U9YxrZWxvftvlcMyPm76zGInE7ldCxTYguiLhZlfu2php5rNNFNOgcKKZlVGq0jLM0iE7q4hEaHTeyA2O1K5Z+chmHqITcgUH0KIOfbsB0TVr8LXWXrmR6xBQqjRkz\/hsD6xWlPIXnmzSnQyut76sKbr5jivp5qI+HIG7QZBN9QN0PArtAfIJv2CZG6MJnkYizwpPWFucMICevRbslR2iLyyCDIFIlln2l36vqHHohHDVbgk2nc+RiQN68C2or4ywAp4kvjIvvAP1Z3OweUd+PcwrJKMuZti0wmf2g7zu3OgVy5DLERH8ey2YM0rxLfVCv5MTMPIm9l96J+LiOZ060ambGVDtMSG4qYxWo5dHfP9rmNejceXY9hjuY72cEYt0eFxjRdJX6CGy8Ky5z\/3MFGaoUEBzTTTryivDqjUVF0hukaeQg42qHlCdcksfbBdNs0xAo8CGpxGJac9d51MD7iIUUuHkxhfb8AoDSnEBr5TmXpw57PRGkU07yQ13dy9DZeE5ljyNAJyWG3my0vuOxpfMhTPF\/cHlDdeSxMyXMr\/xr72j\/ymetS\/b2q75y6CsJgsuqnWe+u6sFq0R9jlj2Rt0Iu+6qnUjnB9EmjSTfrpu6zt1MQp9WhWJ8OjpaZLaBllTHasEqmsVyVXSMXTlYD6Vdxzm3wmgltxj1dLPD7cEz\/FpjLAeAU1fK62kVEQVJaNyK51VAPvBhYim4hZWVAmEEBWPmZ241QacYmVZkfsq0DpUcgIYnW+9lm3yIbnNlo9neINnpOHG\/Bma2l9F3O7RfITwcOCU8mQvyU3+vUn2mI+VjGfF\/OA=="}';
		 
		 
		 
		$rdata=I('request.');
		//$rdata=json_decode($rdatajson,true);
		$alipay_config=C('alipay_config');

		$alipay_config['sign_type'] = '0001';
		$alipay_config['partner']='2088411237448714';
		$alipay_config['ali_public_key_path']='./Public/static/35012401.pem';
		$alipay_config['seller_email'] = 'linmin@zrfilm.com';
		$alipay_config['key'] = 'nz0mklzdpc66p7evwu3kmg5sqwjwnfiz';
		$alipay_config['private_key_path'] = './Public/static/rsa_private_key.pem';

		wlog('进入支付宝支付'.json_encode($rdata), 'alipay');
		//         $alipay_config=C('alipay_config');
		wlog('参数配置'.arrayeval($alipay_config), 'alipay');

		if( $rdata['sec_id']=='0001' ){
			 
			 
			$priKeyValue = file_get_contents($alipay_config['private_key_path']);
			 
			$alipay_config['privateKey']=$priKeyValue;
			 
			$data = rsaDecrypt($rdata['notify_data'], $priKeyValue);
			wlog('准备解密：'. $rdata['notify_data'] . '------private_key_path:' . $alipay_config['private_key_path'],'alipay');
			$notifyData = json_decode(json_encode(simplexml_load_string($data)), true);
			wlog('解密参数：'. json_encode($notifyData),'alipay');
			$out_trade_no = $notifyData['out_trade_no'];
			$trade_no = $notifyData['trade_no'];
			$total_fee = $notifyData['total_fee'];
			$trade_status = $notifyData['trade_status'];
		}else{
			/*---------获取支付配置-------- */
			$doc = new \DOMDocument ();
			$doc->loadXML ( $_POST ['notify_data'] );
			if (! empty ( $doc->getElementsByTagName ( "notify" )->item ( 0 )->nodeValue )) {
				$out_trade_no = $doc->getElementsByTagName ( "out_trade_no" )->item ( 0 )->nodeValue;
				 
				// 支付宝交易号
				$trade_no = $doc->getElementsByTagName ( "trade_no" )->item ( 0 )->nodeValue;
				 
				// 支付宝交易金额
				$total_fee = $doc->getElementsByTagName ( "total_fee" )->item ( 0 )->nodeValue;
				 
				$trade_status = $doc->getElementsByTagName ( "trade_status" )->item ( 0 )->nodeValue;
			}
		}
		wlog('支付宝订单号：'.$out_trade_no,'alipay');
		wlog('支付宝交易号：'.$trade_no,'alipay');
		wlog('支付宝充值金额：'.$total_fee,'alipay');


		//        $orderInfo = D('Buying')->getBuyingOrderInfoByOrderId($out_trade_no);
		//        // $row = D( 'TopUp' )->findObj($out_trade_no );
		//        // $user=D('member')->getUser(array('id'=>$row['uid']));
		//        // wlog('支付宝用户信息'.arrayeval($user),'alipay');
		//        if($row['status']=='2'){
		//            wlog('支付宝支付已完成，不要重复充值!订单号为：'.$out_trade_no,'alipay');
		//            echo "success";
		//            die();
		//        }

		/*--------end-----------*/

		$alipayNotify = new \Think\Pay\AlipayNotify ( $alipay_config );
		$verify_result = $alipayNotify->verifyNotify ();

		//        wlog('支付宝充值参数'.arrayeval($alipay_config),'alipay');

		$verify_result=true;

		if ($verify_result) { // 验证成功
			//            wlog('支付宝验证成功','alipay');
			if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
				$type = 1;
			} else {
				$type = 2;
			}
			$bingpay=S ( 'bingPay' . $out_trade_no . '_' . $type );
			if (!empty($bingpay)) {
				wlog('支付进行中，不要重复支付'.$bingpay,'alipay');
				echo "fail";
				die();
			}
			S ( 'bingPay' . $out_trade_no . '_' . $type, true, 60 );
			// 多条信息过渡
			if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
				//                wlog('支付宝支付完成:','alipay');

				 

				 
				$days_array=explode('N',$out_trade_no);
				$completeOrderData=array();
				$completeOrderData['id'] =$days_array[1];

				$completeOrderData['paymentState'] = 2;

				$model=M('whole_reserve');
				$wherearray=array();
				$wherearray['id']=array('EQ',$completeOrderData['id']);;
				$model->where($wherearray)->save($completeOrderData);

				wlog('订单id:'.$days_array[1].'支付成功,支付金额：'.$total_fee,'bookwhole');

				//订单号查询


				//                if(D('Buying')->completeOrder($completeOrderData)){
				//
				//                    $seatInfo = json_decode($orderInfo['seatInfo'], true);
				//                    foreach ($seatInfo as $key => $value) {
				//                        if(empty($str)){
				//                            $str = $value['seatRow'] . '排' . $value['seatColumn']. '座';
				//                        }else{
				//                            $str .= ',' . $value['seatRow'] . '排' . $value['seatColumn']. '座';
				//                        }
				//
				//                    }

				//                $content = '订单号'.$orderInfo['orderNo'].'，尊敬的用户：您已成功订购第二届丝绸之路电影节'.$orderInfo['filmHall']. $orderInfo['filmStartTime'] . $orderInfo['filmName'] . $str . '电影票，请在影片开映前30分钟凭验证码'.$completeOrderData['verifyCode'].'至影城指定地点换取入场券，过期作废，电影节期间请爱护环境，遵守秩序，谢绝16周岁以下人员参与，活动期间不得随意走动、中途离场，禁止带入食品！';
				//
				//                    $sms = new \Think\SmsModel();
				//                    $smsResult = $sms->sendSms($orderInfo['userMobile'], $content);
				//                    if($smsResult['code'] == 407 ){
				//                        $search = str_replace(array('短信内容含有敏感字符(',')'), '', $smsResult['text']);
				//                        $array = StringToArray($search);
				//                        $replace =  implode(' ', $array);
				//                        $newContent = str_replace($search, $replace, $content);
				//                        $smsResult = $sms->sendSms($orderInfo['userMobile'], $newContent);
				//                    }
				//
				//                    wlog('发送信息:' . json_encode($smsResult),'alipay');
				//                    die('success');
				//                }else{
				//                    D('Buying')->editOrderStatus(5, $orderInfo['orderId']);
				//                    die('fail');
				//                }



				$config=array();
				$config['smsType'] = 'ihyi';
				$config['smsAccount'] = 'cf_zrgjys';
				$config['smsPassword'] = 'APgWjP';
				$sms = new \Think\SmsModel($config);


				$mode=D('whole');
				$data=array();
				$data['id']=$days_array[1];
				$list=$mode->reserve_getlist($data,4);
				//
				//		$list[$k]['cc']=date('H:i',$v['viewingDate']);
				//			$list[$k]['time']=date('Y-m-d',$v['viewingDate']);
				//尊敬的用户，您已成功预定中瑞南华店包场，时间：【变量】，场次：【变量】，影片：【变量】，影厅：【变量】，票数:【变量】张，请在影片开映前30分钟凭验证码【变量】到影城柜台兑换入场。
				//
				$content='尊敬的用户，您已成功预定中瑞南华店包场，订单号：'.$list['id'].'，时间：'.date('Y-m-d',$list['viewingDate']).'，时段：'.date('H:i',$list['viewingDate']).'-'.date('H:i',$list['endTime']).'，影片：'.$list['filmName'].'，影厅：'.$list['topicName'].'，请在影片开映前30分钟凭验证码'.$list['code'].'到影城柜台兑换入场。';


				$smsResult = $sms->sendSms($list['tel'], $content);
				if($smsResult['code'] == 407 ){
					$search = str_replace(array('短信内容含有敏感字符(',')'), '', $smsResult['text']);
					$array = StringToArray($search);
					$replace =  implode(' ', $array);
					$newContent = str_replace($search, $replace, $content);
					$smsResult = $sms->sendSms($list['tel'], $newContent);
				}


				wlog('发送信息:' . $content.',发送号码为：'.$list['tel'],'bookwhole');


				die('success');

			}
		} else {
			// 验证失败
			wlog('支付宝充值参数验证失败'.arrayeval($alipay_config),'alipay');
			echo "fail";
		}
	}

	//订单详情展示
	public function orderBc (){
		$userInfo = session('ftuser');
		if(!$userInfo) {
			//			$_SESSION['url']='Bookwhole/orderBc';
			$_SESSION['url']=U('Bookwhole/orderBc');			
			$this->redirect('public/login');
		}
		$mode=D('whole');
		$data=array();
		if($_REQUEST['id']) {
			$data['id']=$_REQUEST['id'];		
		}else{
			$data['uid']=isset($userInfo['id'])?$userInfo['id']:'';				
			//			$data['uid']=22;
			$data['neqdelState']=1;
		}
		$data['sort']='id desc';
		//		dump($data);
		$list=$mode->reserve_getlist($data);
		foreach($list as $k=>$v) {								
			$list[$k]['paymentTimeflag']=date('Y-m-d H:i',$v['paymentTime']);
			$list[$k]['cc']=date('H:i',$v['viewingDate']);
			$list[$k]['ec']=date('H:i',$v['endTime']);
			$list[$k]['time']=date('Y-m-d',$v['viewingDate']);
			$inv=M('wholeInvoiceDetails')->where(array('reserveId'=>$v['id']))->find();

			if(!empty($inv)){
				if($inv['type']=='1'){
					$list[$k]['inv']='纸质发票';
				}else{
					$list[$k]['inv']='增值税发票';
				}
			}
			$data=array();
			$data['reserveId']=$v['id'];
			$data['type']=1;
			$packagelist=$mode->reserve_relation_package_getlist($data);
			if($packagelist) {
				$list[$k]['packagelist']=$packagelist;
			}
			$data=array();
			$data['reserveId']=$v['id'];
			$data['type']=2;
			$servicelist=$mode->reserve_relation_service_getlist($data);
			if($servicelist) {
				$list[$k]['servicelist']=$servicelist;
			}
			$list[$k]['paymentTypeName']='预付定金';
			if($v['total']==$v['prepay']){
				$list[$k]['paymentTypeName']='全额支付';			
			}
			
			$list[$k]['total']=number_format($v['total'],2);
			
//				$list[$k]['prepay']=number_format($v['prepay'],2);
			
			
			
			
		}
		$this->assign('list',$list);
		//		$mode->
		$this->display();
	}

	/**
	 * 前台用户登录
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $verify
	 */
	public function login($username = null, $password = null, $verify = null){
		 
		 
		 
		$this->assign('backurl',urlencode($_SERVER ['HTTP_HOST'].U('public/login')));
		//		$this->assign('backurl',$_SERVER ['HTTP_HOST'].U('Bookwhole/login'));
		 
		$cinemaList = D('cinema')->getList();
		$this->assign('data',$cinemaList);
		$this->display();

	}

	//用户登入获取信息
	public function userLogin() {
		$placeNo=trim(I('placeNo'));
		$cardId=I('cardId');
		$passWord=I('passWord');
		$mobile=I('mobile');
		$telPassWord=I('telPassWord');
		$url='http://www.zrfilm.com/index.php?route=system/api/login&placeNo='.$placeNo.'&cardId='.$cardId.'&passWord='.$passWord;
		//         $url='http://www.zrfilm.com/index.php?route=system/api/login&placeNo=35014171&cardId=30600001&passWord=654321';
		$url2='http://www.zrfilm.com/index.php?route=system/api/login&mobile='.$mobile.'&telPassWord='.$telPassWord;
		if(!empty($placeNo)){
			$backJson = file_get_contents($url);
		}else{
			$backJson = file_get_contents($url2);
		}
		//        echo $url;
		$userLoginReturn=json_decode($backJson, true);
		// print_r($userLoginReturn);
		if ($userLoginReturn['status'] == 0) {
			$this->error($userLoginReturn['data'][0]);
		}else{
			session('ftuser',$userLoginReturn['data']);
			$reBackData['balance'] = $userLoginReturn['data']['balance']/100;
			$reBackData['cardId'] = $userLoginReturn['data']['cardId'];
			$reBackData['url']= session('url');

			$this->success('登录成功！', $reBackData);
		}
	}

	public function checkLogin() {
		$userInfo = session('ftuser');
		if ($userInfo) {
			$this->success('');
		}else{
			$this->error('');
		}
	}
//	//成功数据处理
//	public function success($content, $dataList = array()) {
//		$data['status']  = 0;
//		$data['content'] = $content;
//		$data['data'] = $dataList;
//		$this->ajaxReturn($data);
//	}
//	/**
//	 * Enter description here ...
//	 * @param string $content
//	 * @param string $status
//	 */
//	public function error($content, $status = 1) {
//		$data['status']  = $status;
//		$data['content'] = $content;
//		$this->ajaxReturn($data);
//	}
	/**
	 * 随机生成数
	 * @param int $len 长度
	 * @return string
	 */
	function GetRandStr($len) {
		$chars = array(
	        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",  
	        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",  
	        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",  
	        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",  
	        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",  
	        "3", "4", "5", "6", "7", "8", "9" 
	     );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i=0; $i<$len; $i++)  {
        	$output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
	}


	public function parseResponse($str_text, $rsaDecrypt) {
		//以“&”字符切割字符串
		$para_split = explode('&',$str_text);
		//把切割后的字符串数组变成变量与数值组合的数组
		foreach ($para_split as $item) {
			//获得第一个=字符的位置
			$nPos = strpos($item,'=');
			//获得字符串长度
			$nLen = strlen($item);
			//获得变量名
			$key = substr($item,0,$nPos);
			//获得数值
			$value = substr($item,$nPos+1,$nLen-$nPos-1);
			//放入数组中
			$para_text[$key] = $value;
		}
		if( ! empty ($para_text['res_data'])) {
			//解析加密部分字符串
			$para_text['res_data'] = rsaDecrypt($para_text['res_data'], $rsaDecrypt);
			//token从res_data中解析出来（也就是说res_data中已经包含token的内容）
			$doc = new \DOMDocument();
			$doc->loadXML($para_text['res_data']);
			$para_text['request_token'] = $doc->getElementsByTagName( "request_token" )->item(0)->nodeValue;
		}

		return $para_text;
	}
	/**
	 * 设置默认值
	 */
	public function setsession() {
		if(isset($_REQUEST['theme']))
		$_SESSION['theme']=$_REQUEST['theme'];
		if(isset($_REQUEST['theme_id']))
		$_SESSION['theme_id']=$_REQUEST['theme_id'];
		if(isset($_REQUEST['theme_seating']))
		$_SESSION['theme_seating']=$_REQUEST['theme_seating'];
		if(isset($_REQUEST['stime']))
		$_SESSION['stime']=$_REQUEST['stime'];
		if(isset($_REQUEST['svtime']))
		$_SESSION['svtime']=$_REQUEST['svtime'];
		if(isset($_REQUEST['filmName']))
		$_SESSION['filmName']=$_REQUEST['filmName'];
		if(isset($_REQUEST['filmNo']))
		$_SESSION['filmNo']=$_REQUEST['filmNo'];
		if(isset($_REQUEST['votesNum']))
		$_SESSION['votesNum']=$_REQUEST['votesNum'];
		if(isset($_REQUEST['price']))
		$_SESSION['price']=$_REQUEST['price'];
		if(isset($_REQUEST['fullHousePrice']))
		$_SESSION['fullHousePrice']=$_REQUEST['fullHousePrice'];
		if(isset($_REQUEST['favorablePrice']))
		$_SESSION['favorablePrice']=$_REQUEST['favorablePrice'];
		if(isset($_REQUEST['serviceCharge']))
		$_SESSION['serviceCharge']=$_REQUEST['serviceCharge'];
		if(isset($_REQUEST['file_ord_price']))
		$_SESSION['file_ord_price']=$_REQUEST['file_ord_price'];
		if(isset($_REQUEST['file_ord_tolprice']))
		$_SESSION['file_ord_tolprice']=$_REQUEST['file_ord_tolprice'];
		if(isset($_REQUEST['file_ord_tolserviceCharge']))
		$_SESSION['file_ord_tolserviceCharge']=$_REQUEST['file_ord_tolserviceCharge'];
		if(isset($_REQUEST['all_price']))
		$_SESSION['all_price']=$_REQUEST['all_price'];
		if(isset($_REQUEST['index_service_price']))
		$_SESSION['index_service_price']=$_REQUEST['index_service_price'];
		if(isset($_REQUEST['package_price']))
		$_SESSION['package_price']=$_REQUEST['package_price'];
		if(isset($_REQUEST['index_detail']))
		$_SESSION['index_detail']=$_REQUEST['index_detail'];
		if(isset($_REQUEST['service']))
		$_SESSION['service']=$_REQUEST['service'];
		if(isset($_REQUEST['snacks']))
		$_SESSION['snacks']=$_REQUEST['snacks'];
		echo '设置成功';
	}
	/**
	 * 清空默认值
	 */
	public function delsession() {
		$_SESSION['theme']='';
		$_SESSION['theme_id']='';
		$_SESSION['theme_seating']='';
		$_SESSION['stime']='';
		$_SESSION['svtime']='';
		$_SESSION['filmName']='';
		$_SESSION['votesNum']='';
		$_SESSION['price']=0;
		$_SESSION['fullHousePrice']=0;
		$_SESSION['favorablePrice']=0;
		$_SESSION['serviceCharge']=0;
		$_SESSION['file_ord_price']=0;
		$_SESSION['file_ord_tolprice']=0;
		$_SESSION['file_ord_tolserviceCharge']=0;
		$_SESSION['all_price']=0;
		$_SESSION['index_service_price']=0;
		$_SESSION['package_price']=0;
		$_SESSION['index_detail']='';
		$_SESSION['service']='';
		$_SESSION['snacks']='';
	}
	/**
	 *  取消开发票
	 */
	public function invoice_cancel() {
		$_SESSION['confirmOrder']['invoice_name1']='';
		$_SESSION['confirmOrder']['invoice_content']='';
		$_SESSION['confirmOrder']['invoice_type']='';
		$_SESSION['confirmOrder']['invoice_name2']='';
		$_SESSION['confirmOrder']['invoice_identificationNum']='';
		$_SESSION['confirmOrder']['invoice_address']='';
		$_SESSION['confirmOrder']['invoice_phone']='';
		$_SESSION['confirmOrder']['invoice_bank']='';
		$_SESSION['confirmOrder']['bankAccount']='';
	}
	/**
	 * 临时记录
	 */
	public function invoice_for_session() {
		$_SESSION['confirmOrder']['invoice_name1']=isset($_REQUEST['invoice_name1'])?$_REQUEST['invoice_name1']:'';
		$_SESSION['confirmOrder']['invoice_content']=isset($_REQUEST['invoice_content'])?$_REQUEST['invoice_content']:'';
		$_SESSION['confirmOrder']['invoice_name2']=isset($_REQUEST['invoice_name2'])?$_REQUEST['invoice_name2']:'';
		$_SESSION['confirmOrder']['invoice_identificationNum']=isset($_REQUEST['invoice_identificationNum'])?$_REQUEST['invoice_identificationNum']:'';
		$_SESSION['confirmOrder']['invoice_address']=isset($_REQUEST['invoice_address'])?$_REQUEST['invoice_address']:'';
		$_SESSION['confirmOrder']['invoice_phone']=isset($_REQUEST['invoice_phone'])?$_REQUEST['invoice_phone']:'';
		$_SESSION['confirmOrder']['invoice_bank']=isset($_REQUEST['invoice_bank'])?$_REQUEST['invoice_bank']:'';
		$_SESSION['confirmOrder']['invoice_bankAccount']=isset($_REQUEST['invoice_bankAccount'])?$_REQUEST['invoice_bankAccount']:'';
	}
	/**
	 * 获取15分钟内未支付成功的订单并设置成支付失败
	 */
	public function get_onpay() {
		$mode=D('whole');
		$data=array();
		$data['paymentState']=1;
		$data['ltpaymentTime']=time()-15*60;
		$list=$mode->reserve_getlist($data);
		if(is_array($list))
		foreach($list as $v) {
			$data=array();
			$data['id']=$v['id'];
			$data['paymentState']=3;
			$mode->update_reserve($data);
		}
	}
	/**
	 * 删除订单
	 */
	public function del_order() {
		$mode=D('whole');
		$ids=$_REQUEST['ids'];
		$idsarray=explode(",",$ids);
		foreach($idsarray as $v) {
			$data=array();
			$data['id']=$v;
			$mode->del_order($data);
		}
	}
	
	/**
	 * 周期数字转换
	 * @param int $flag 周期数字
	 * @return string
	 */
	public function week_over($flag) {
		$return='日';
		
		switch ($flag) {
			case 0:
				$return='日';
			break;
			case 1:
				$return='一';
			break;
			case 2:
				$return='二';
			break;
			case 3:
				$return='三';
			break;
			case 4:
				$return='四';
			break;
			case 5:
				$return='五';
			break;
			case 6:
				$return='六';
			break;
			default:
				$return='日';
			break;
		}
		return $return;
	
	
	}



}


