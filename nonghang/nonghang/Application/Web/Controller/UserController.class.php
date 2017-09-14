<?php 
namespace Web\Controller;
use Think\Controller;
class UserController extends InitController {
	/**
	 * 取消订单
	 *
	 * @param string $orderid
	 * @return void
	 */
	function backTicket(){
		$orderid=I('orderid');
		$backTip=I('backTip');
		$result=D('Order')->backTicket($orderid,$backTip);
		if ($result['ResultCode'] == 0) {
			$this->success('退票成功！');
		}else{
			if(!empty($result['Message'])){
				$this->error($result['Message']);
			}else{
				$this->error(getMtxError($result['ResultCode']));
			}
		}
	}
	
	/**
	 * 登出
	 */
	function logout(){
		session('ftuser',null);
		cookie('ftuser',null);
		$this->success();
	}
	/**
	 * 注册
	 */
	public function register(){
		if(IS_AJAX){
			$mobile = I('userMobile');
			$randomCode = I('validateCode');
			$passWord = I('userPasswd');
			if(empty($randomCode)||empty($mobile)||empty($passWord)){
				$this->error('参数错误', '11001');
			}
			if(!checkMobile($mobile)){
				$this->error('手机格式不正确',1,1);
			}
			if(strlen($passWord)<6||strlen($passWord)>20){
				$this->error('请重新输入（限6-20个字符、数字或组合）',1,4);
			}
			$send = S('tokenId_getMobileVerification' . $mobile . 'register');
			if (empty($send)) {
				$this->error('手机动态码已过期，请重新获取',1,3);
			}
			if(!empty($send)){
				if($randomCode != $send['code'] || empty($randomCode)){
					$this->error('手机动态码有误，请重新输入',1,3);
				}else{
					$arr['mobile']=$mobile;
					$arr['mpword']=encty($passWord);
					$arr['bindTime']=time();
					$arr['levelCode']=$this->wwwInfo['defaultLevel'];
					$arr['memberGroupId']=$this->wwwInfo['defaultLevel'];
					$arr['userName'] = $mobile;
					$arr['otherName']=$mobile;
					$arr['cinemaGroupId'] = $this->wwwInfo['cinemaGroupId'];
					if(D('Member')->add($arr)){
						$this->success('注册成功', $arr);
						S('tokenId_getMobileVerification' . $mobile . 'register',null);
					}else{
						$this->error('注册提交失败');
					}
				}
			}
		}else{
			$this->assign('pageId', 'page-register');
			$cinemalist=D('cinema')->getAllCinema(array('cinemaCode'=>array('in',$this->wwwInfo['cinemaList'])));
			$this->assign('cinemalist',$cinemalist);
			$this->display('register');
		}
    }
    /**
     * 登录
     */
	public function login(){
		if(IS_AJAX){
			$cinemaCode=I('cinemaCode');
			$loginNum=I('userAccount');
			$passWord=I('userPasswd');
			if(empty($loginNum)|| empty($passWord)){
				$this->error('参数错误', '11001');
			}
			if(!empty($cinemaCode)){   //会员卡登录
				$cinema=D('cinema')->find($cinemaCode);
				$hasUser=D('member')->getBindInfo(array('mobile'=>$loginNum,'cinemaCode'=>$cinemaCode,'cinemaGroupId'=>$this->wwwInfo['cinemaGroupId']));
				if(!empty($hasUser)){
					$loginNum=$hasUser['cardId'];
				}
				$loginarr=array('cinemaCode'=>$cinemaCode,'loginNum'=>$loginNum,'password'=>$passWord,'link'=>$cinema['link'],'cinemaName'=>$cinema['cinemaName']);
				$loginResult = D('ZMUser')->verifyMemberLogin($loginarr);
				if($loginResult['ResultCode'] == 0){//登录成功
					$result=D('member')->loginMember($loginResult,$cinemaCode,$passWord);
					if($result['status']=='0'){
						$this->success('登录成功',$_REQUEST['url']);
					}else{
						$this->error($result['info']);
					}
				}else{
					if($loginResult['Message']=='记录不存在'){
						$loginResult['Message']='所属门店无此会员卡号';
						$this->error($loginResult['Message'],1,1);
					}
					if($loginResult['Message']=='密码错误'){
						$loginResult['Message']='会员卡密码有误，请重新输入！';
						$this->error($loginResult['Message'],1,3);
					}
					$this->error($loginResult['Message']);
				}
			}else{   //手机用户登录
				$verify=I('verify');
				if(!check_verify($verify,'login')){
					$this->error('您输入的验证码有误',1,3);
				}
				if(!checkMobile($loginNum)){
					$this->error('手机格式不正确',1,1);
				}
				$user = D('member')->getUser(array('mobile'=>$loginNum,'cinemaGroupId'=>$this->wwwInfo['cinemaGroupId']));
				if(empty($user)){
					$this->error('该手机号未注册',1,1);
				}elseif($user['mpword']!=encty($passWord)){
					$this->error('登录密码有误，请重新输入！',1,2);
				}else{
					session('ftuser',$user);
					cookie('ftuser',$user,3600);
					$this->success('登录成功',$_REQUEST['url'] );
				}
			}
		}else{

			$this->assign('url', $_REQUEST['url']);
			$this->assign('pageId', 'page-login');
			$cinemalist=D('cinema')->getAllCinema(array('cinemaCode'=>array('in',$this->wwwInfo['cinemaList'])));
			$this->assign('cinemalist',$cinemalist);
			$this->display();
		}
    }

    /**
     * 修改密码
     */
	public function backpw(){
		if(IS_AJAX){
			$page=I('page');
			if($page=='1'){
				$mobile = I('userMobile');
				$randomCode = I('validateCode');
				if(empty($mobile)){
					$this->error('请输入手机号',1,1);
				}
				if(!checkMobile($mobile)){
					$this->error('手机格式不正确',1,1);
				}
				if(empty($randomCode)){
					$this->error('请输入手机动态码',1,3);
				}
				$send = S('tokenId_getMobileVerification' . $mobile . 'find');
				if (empty($send)) {
					$this->error('动态码已过期，请重新获取',1,3);
				}
				if(!empty($send)){
					if($randomCode != $send['code'] || empty($randomCode)){
						$this->error('动态码有误，请重新输入！',1,3);
					}else{
						$user=D('Member')->getUser(array('mobile'=>$mobile));
						session('finduid',$user['id']);
						$this->success();
					}
				}
			}elseif($page=='2'){
				$passWord = I('userPasswd');
				if(empty($passWord)){
					$this->error('请输入新密码',1,1);
				}
				if(strlen($passWord)<6||strlen($passWord)>20){
					$this->error('请重新输入（限6-20个字符、数字或组合）',1,1);
				}
				$data['id']=session('finduid');
				if(empty($data['id'])){
					$this->error('请重新输入手机号');
				}
				$data['mpword']=encty($passWord);
				$result=D('member')->save($data);
				if($result!==false){
					session('finduid',null);
					$this->success();
				}else{
					$this->error('网络异常');
				}
			}
		}else{
			$this->assign('pageId', 'page-backpw');
			$this->display();
		}
    }

    /**
     * 修改用户资料
     */
	public function userinfo(){
		if(IS_AJAX){
			$user=$this->user;
			$page=I('page');
			if($page=='1'){
				$otherName=I('otherName');
				$sex=I('sex');
				$email=I('email');
				$birthday=I('birthday');			
				
				if(empty($otherName)||empty($sex)||empty($email)){
					$this->error('参数错误', '11001');
				}
				if(mb_strlen($otherName,'utf-8')>11){
					$this->error('昵称不能超过11位',1,1);
				}
				$data['sex']=$sex;
				$data['email']=$email;
				$data['otherName']=$otherName;
				if(!$user['birthday']){
					$data['birthday']=$birthday;
				}
			}elseif($page==2){
				$oldPasswd=I('oldPasswd');
				$newPasswd=I('newPasswd');
				if(empty($user['mobile'])){
					if($user['pword']!=encty($oldPasswd)){
						$this->error('原始密码错误',1,1);
					}
					if(strlen($newPasswd)<6){
						$this->error('密码必须6位数!',1,2);
					}
					if(!preg_match('/^\d{6}$/',$newPasswd)){
						$this->error('密码必须为纯数字!',1,2);
					}
					$data['pword']=encty($newCardPasswd);
					$arr['cinemaCode']=$user['businessCode'];
					$cinema=D('cinema')->find($arr['cinemaCode']);
					$arr['loginNum']=$user['cardNum'];
					$arr['link']=$cinema['link'];
					$arr['oldPassword']=$oldPasswd;
					$arr['newPassword']=$newPasswd;
					
					$result=D('ZMUser')->modifyMemberPassword($arr);
					if($result['ResultCode']!='0'){
						if(empty($result['Message'])){
							$result['Message']='暂不支持修改会员卡密码';
						}
						$this->error($result['Message']);
					}
				}else{
					if($user['mpword']!=encty($oldPasswd)){
						$this->error('原始密码错误',1,1);
					}
					if(strlen($newPasswd)<6){
						$this->error('密码长度必须大于6位!',1,2);
					}
					$data['mpword'] = encty($newPasswd);
				}
			}
			$result=D('member')->saveUser(array('id'=>$user['id']), $data);
			if($result!==false){
				if(UID==$user['id']){
					$user=D('member')->find(UID);
					session('ftuser',$user);
				}
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}else{
			$page=I('page');
			if($page=='3'){
				$user=$this->user;
				$upload = new \Think\Upload(); // 实例化上传类
	    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
	    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    		$upload->savePath  =     'userIcons/'; // 设置附件上传（子）目录
	    		// 上传文件
	    		$info   =   $upload->upload();
	    		if($info['image']){	    			
	    			$data=array();
	    			$data['headImage']=$info['image']['savepath'].$info['image']['savename'];
	    			$result=D('member')->saveUser(array('id'=>$user['id']), $data);		
	    			echo '<script>';
					echo " parent.window.location.href='';";
					echo '</script>';
					exit;
	    		}
				
			}
			
			
			
			
			
			$this->assign('pageId', 'page-userinfo');
			$this->display();
		}
    }
    
    /**
     * 充值
     */
	public function recharge(){
		$user=$this->user;
		if(IS_AJAX){
			$payType = I('payType');
			if(empty($payType)){
				$this->error('参数错误！', '11001');
			}else{
				//付款金额
				$total_fee = I('fee');
				if($total_fee<100){
					$this->error('充值金额必须大于等于100元');
				}
				if(!empty($user['cardNum'])){
					if($total_fee%100!=0){
						$this->error('充值金额须为100的整数倍');
					}
					$cinemaCode=$user['businessCode'];
				}else{
					$cinemaCode='35012401';
				}
				$cinema=M('cinema')->find($cinemaCode);
				$payConfig=json_decode($cinema['payConfig'],true);
				if($this->wwwInfo['isDebug']){
					$total_fee = 0.01;
				}
//				$total_fee = 0.01;
				$topUparr=array(
						'uid'=>$user['id'],
						'cinemaCode'=>$cinemaCode,
						'cinemaName'=>$cinema['cinemaName'],
						'money'=>$total_fee,
						'createTime'=>time(),
						'way'=>'www',
						'type'=>$payType,
						'cinemaGroupId'=>$this->wwwInfo['cinemaGroupId'],
				);
				if(!empty($user['cardNum'])){
					$topUparr['cardId']=$user['cardNum'];
				}else{
					$topUparr['mobile']=$user['mobile'];
				}
				$orderno=D('orderRecharge')->add($topUparr);
			}
			
			
			if($orderno){
				if($payType=='alipay'){
					$alipayConfig=$payConfig['alipayConfig'];
					$alipayConfig['partner']=$alipayConfig['partnerId'];
					$alipayConfig['seller_email']=$alipayConfig['sellerEmail'];
					$alipayConfig['key']=$alipayConfig['alipayKey'];
					$alipayConfig['sign_type']=$alipayConfig['signType'];
					$alipayConfig['input_charset']='utf-8';
					$alipayConfig['transport']='http';
					$alipayConfig['cacert']=getcwd().'/cacert.pem';
					$alipayConfig['pay_way']=='www';
					//支付类型
					$payment_type = "1";
					//必填，不能修改
					//服务器异步通知页面路径
					$notify_url =C('PAY_URL'). "recharge/alipay_www";
					//需http://格式的完整路径，不能加?id=123这类自定义参数
						
					//页面跳转同步通知页面路径
					$return_url =C('IMG_URL'). "user/rechargeStatus/orderid/".$orderno;
					//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
						
					//商户订单号
					$out_trade_no = time().'recharge'.$orderno;//$_POST ['WIDout_trade_no'];
					//商户网站订单系统中唯一订单号，必填
						
					//订单名称
					$subject = I('title');
					$subject = '充值';
						
					//订单描述
					$body =I('body');
					$body = '充值';
					//商品展示地址
					$show_url = I('show_url');
					//需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
						
					//防钓鱼时间戳
					$anti_phishing_key = "";
					//若要使用请调用类文件submit中的query_timestamp函数
						
					//客户端的IP地址
					$exter_invoke_ip = "";
					//非局域网的外网IP地址，如：221.0.0.1
					$parameter = array(
							"service" => "create_direct_pay_by_user",
							"partner" => trim($alipayConfig['partner']),
							"seller_email" => trim($alipayConfig['seller_email']),
							"payment_type"	=> $payment_type,
							"notify_url"	=> $notify_url,
							"return_url"	=> $return_url,
							"out_trade_no"	=> $out_trade_no,
							"subject"	=> $subject,
							"total_fee"	=> $total_fee,
							"body"	=> $body,
							"show_url"	=> $show_url,
							"anti_phishing_key"	=> $anti_phishing_key,
							"exter_invoke_ip"	=> $exter_invoke_ip,
							"_input_charset"	=> trim(strtolower($alipayConfig['input_charset']))
					);
					// 建立请求
					$alipaySubmit = new \Think\Pay\AlipaySubmit ( $alipayConfig );
					$html_text = $alipaySubmit->buildRequestParaToString ( $parameter, 'get', '确认' );
					$surl = 'https://mapi.alipay.com/gateway.do?'. $html_text;
					$this->success('',$surl);
					//header('Location:' . $surl);
				}else{
					$this->error('其他支付');
				}
			}else{
				$this->error('生成充值订单失败');
			}
		}else{
			$this->assign('pageId', 'page-pay');
			if(empty($user['cardNum'])){
				$cinemaCode='35012401';
			}else{
				$cinemaCode=$user['businessCode'];
			}
			$payInfo=$this->getRechargePayway($cinemaCode);
			$this->assign('payInfo',$payInfo);
			$this->display();
		}
    }

    
/**
 * 充值状态
 */
	public function rechargeStatus(){
		$orderid=I('orderid');
		if(IS_AJAX){
			$order=M('orderRecharge')->find($orderid);
			$this->success('',$order);
		}else{
			$this->assign('pageId', 'page-paymentStatus');
			$this->assign('orderid',$orderid);
			$this->display();
		}
    }

	public function paySuccess(){
	   $this->assign('pageId', 'page-paySuccess');
       $this->display('paySuccess');
    }

    /**
     * 影票订单
     */
	public function movieorder(){
		if(IS_AJAX){
			$user=$this->user;
			D('order')->updateOrder($user['id']);
			$status= I('status');
			if(empty($status)){
				$this->error('参数错误！', '11001');
			}
			$data['status']=$status;
			$map['cinemaGroupId']=$this->wwwInfo['cinemaGroupId'];
			if($status=='1'){
				$map['status']=3;
			}elseif($status=='2'){
				$map['status']=9;
			}elseif($status=='3'){
				$map['status']=array('not in', '9,3,0');
			}else{
				$map['_string']='status !=0 or (status=0 and way="www")';
			}
			$map['uid']=$user['id'];
			$limit=$this->pageNum;
			$nowPage = intval(I ('request.p')) == 0 ? 1 : intval(I ('request.p'));
			$count=D('order')->countObj($map);
			$allPage = ceil ( $count / $limit);
			$curPage = $this->curPage ( $nowPage, $allPage );
			$startLimit = ($curPage - 1) * $limit;
			$showPage = $this->getPageList ( $count, $limit, $data );
			$orders=D('order')->getList('status,orderCode,myPrice,seatCount,filmNo,lockTime,status,printNo,verifyCode,filmName,startTime,seatIntroduce,copyType,downTime,hallName,cinemaName,orderTime,mobile, submitPrice, amount',$map,$startLimit,$limit);
			foreach ($orders as $k=>$v){
				if(($v['status']=='3'&&($v['startTime']+7200>time()))||$v['status']=='0'){
					$orders[$k]['nodel']=1;
				}
				$film[$k]=D('film')->getFilm(array('filmNo'=>$v['filmNo']));
				$orders[$k]['filmImg']=$film[$k]['image'];
				$orders[$k]['downTime']=date('Y-m-d H:i:s',$v['downTime']);
				$orders[$k]['qrcode']=substr(C('IMG_URL'), 0,-1). U('index/getQRcode', array('orderid'=>$v['orderCode'],'code' => $v['printNo'] ));
			}
			if(empty($orders)){
				$this->error();
			}else{
				$showdata['page']=$showPage;
				$showdata['orders']=$orders;
				$this->success('',$showdata);
			}
		}else{
			$this->assign('pageId', 'page-movieOrder');
			$this->display();
		}
    }

    /**
     * 删除影票订单
     */
    function delOrderFilm(){
    	$orderid=I('orderid');
    	if(empty($orderid) ){
    		$this->error('参数校验信息错误', 100101);
    	}
    	$order=D('orderFilm')->find($orderid);
    	if(!empty($order)&&$order['status']=='3'&&($order['startTime']+7200>time())){ //2小时
    		$this->error('该影片未放映结束，删除失败');
    	}
    	$data['orderCode']=$orderid;
    	$data['visible']=1;
    	if(D('orderFilm')->save($data)){
    		if($order['status']=='0'){
    			$this->success('取消订单成功',$orderid);
    		}
    		$this->success('删除成功',$orderid);
    	}else{
    		if($order['status']=='0'){
    			$this->success('取消订单失败',$orderid);
    		}
    		$this->error('删除失败');
    	}
    }
    /**
     * 卖品订单详细
     */
    public function goodsorder(){
    	$modle=D('Goods');
    	$user=$this->user;
    	if($user){
    		$data=array();
    		$data['uid']=$user['id'];
    		$data['status']=1;
    		$data['visible']=0;   		
    		$data['sort']='id desc';
    		$list=$modle->order_goods_getlist($data);
    		if(is_array($list)){
    			foreach($list as $k=>$v) {
    				$map=array();
    				$map['orderid']=$v['id'];
    				$ret=$modle->order_detail_getlist($map);    				 
    				if(is_array($ret)){
    					foreach($ret as $k1=>$v1){   							
    						if(file_exists('./Uploads/'.$v1['goodsImg'])&&$v1['goodsImg']){
    							$ret[$k1]['goodsImg']=C('IMG_URL').'Uploads/'.$v1['goodsImg'];
    						}else{
    							$ret[$k1]['goodsImg']=C('GOODS_IMG_URL');
    						}
    						$ret[$k1]['tolprice']=$v1['price']*$v1['number'];   							
    					}    					    					 
    				}   				    				     				 
    				$list[$k]['order_detail']=$ret;
    			}
    		}
    		$this->assign('list', $list); 
    	}
    	$this->assign('pageId', 'page-goodsOrder');
    	$this->display('goodsorder');
    }
	/**
     * 删除卖品订单
     */
    function delOrderGoods(){
    	$orderid=I('orderid');
    	if(empty($orderid) ){
    		$this->error('参数校验信息错误', 100101);
    	}  	
    	$data['id']=$orderid;
    	$order=M('orderGoods')->where($data)->find();
    	if($order['exstatus']!=1){
//    		dump($order);
    		$this->error('卖品未兑换');
    	}
    	$data['visible']=1;
    	if(M('orderGoods')->save($data)){   		
    		$this->success('删除成功',$orderid);
    	}else{
    		$this->error('删除失败');
    	}
    }
	/**
	 * 退票中心
	 */
	public function returnticket(){
	   $this->assign('pageId', 'page-returnTicket');
       $user=$this->user;
		$page= I('page');
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		$map['uid']=$user['id'];
		$map['status']=3;
		$map['startTime']=array('egt',time()+7200);
		$orders=D('order')->getList('status,orderCode,myPrice,seatCount,filmNo,lockTime,status,printNo,verifyCode,filmName,startTime,seatIntroduce,copyType,downTime,hallName,cinemaName,orderTime,mobile',$map,$start,$this->pageNum);
		foreach ($orders as $k=>$v){
			$film[$k]=D('film')->getFilm(array('filmNo'=>$v['filmNo']));
			$orders[$k]['filmImg']=$film[$k]['image'];
			$orders[$k]['qrcode']=substr(C('IMG_URL'), 0,-1). U('index/getQRcode', array('orderid'=>$v['orderCode'],'code' => $v['printNo'] ));
		}
		$this->assign('orders',$orders);
		$this->display();
    }
    /**
     * 5.0.2获取用户券包
     */
	public function vouchers(){
		if(IS_AJAX){
			$userInfo = $this->user;
			$voucherClass=I('voucherClass');
			$map['typeClass'] = $voucherClass;
			$map['memberId'] = $userInfo['id'];
			$map['validData'] = array('EGT', strtotime(date('Y-m-d')));
			$map['isUnlock'] = 0;
			$map['isUse'] = 0;
			$map['visible'] = 0;
			$memberVoucherList = D('Member')->getMemberVoucherList('cardId,typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $map, '', 'validData asc');
			$map['validData'] = array('between',strtotime(date('Y-m-d')) . ',' . (strtotime(date('Y-m-d')) + 604800));
			$expireVoucherList = D('Member')->getMemberVoucherList('typeId', $map, '', 'validData asc');
			$expireNum = count($expireVoucherList);
			foreach ($memberVoucherList as $key => $value) {
				$memberVoucherList[$key]['expireNum'] = $expireNum;
				$memberVoucherList[$key]['validDate'] =date('Y-m-d',$value['validData']);
			}
			if(empty($memberVoucherList)){
				$this->error('');
			}else{
				if($voucherClass=='0'){
					$vt='兑换券';
				}elseif($voucherClass=='1'){
					$vt='立减券';
				}else{
					$vt='卖品券';
				}
				$data['vname']=$vt;
				$data['memberVoucherList']=$memberVoucherList;
				$this->success('',$data);
			}
		}else{
			$this->assign('pageId', 'page-vouchers');
			$this->display();
		}
    }
    
    /**
     * 历史券包
     */
    function packagerecord(){
    	$userInfo = $this->user;
    	$map['memberId'] = $userInfo['id'];
    	$map['visible'] = 0;
    	$map['_string'] = 'validData < ' . strtotime(date('Y-m-d')) . ' or isUnlock=1 or isUse=1';
    	$memberVoucherList = D('Member')->getMemberVoucherList('cardId,typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData, isUse, isUnlock', $map);
    	foreach ($memberVoucherList as $key => $value) {
    		if ($value['validData'] < strtotime(date('Y-m-d')) ) {
    			$remarks = '已过期';
    		}elseif ($value['isUnlock'] == 1) {
    			$remarks = '已解锁';
    		}elseif ($value['isUse'] == 1) {
    			$remarks = '已使用';
    		}
    		$memberVoucherList[$key]['remarks'] = $remarks;
    		unset($memberVoucherList[$key]['isUnlock'], $memberVoucherList[$key]['isUse']);
    	}
    	if(empty($memberVoucherList)){
    		$this->error('');
    	}else{
    		$this->success('', $memberVoucherList);
    	}
    	
    }
    /**
     * 解绑券包
     */
    function delvoucher(){
    	$vid=I('vid');
    	if(empty($vid)){
    		$this->error();
    	}
    	$data['cardId']=$vid;
    	$data['isUnlock']=1;
    	if(M('memberVoucher')->save($data) !== false){
    		$this->success();
    	}else{
    		$this->error();
    	}
    }
    /**
     * 隐藏券包
     */
    function hidevoucher(){
    	$vid=I('vid');
    	if(empty($vid)){
    		$this->error();
    	}
    	$data['cardId']=$vid;
    	$data['visible']=1;
    	if(M('memberVoucher')->save($data) !== false){
    		$this->success();
    	}else{
    		$this->error();
    	}
    	
    }
    /**
     * 5.0.1添加卡包
     */
    public function addvoucher(){
    	$voucherNum=I('voucherNum');
    	if(empty($voucherNum)){
    		$this->error('参数错误！', '11001');
    	}
    
    	$voucherInfo = D('Voucher')->checkVoucher($voucherNum);
    	if ($voucherInfo['status'] ==1) {
    		$this->error($voucherInfo['content']);
    	}
    	$userInfo = $this->user;
    	//判断是否被添加
    
    	if(D('Member')->checkVoucher($voucherNum)){
    		$this->error('当前票券已被添加！');
    	}
    
    	//开始加入券包
    
    	$data['memberId'] = $userInfo['id'];
    	$data['voucherName'] = $voucherInfo['data']['typeName'];
    	$data['voucherNum'] = $voucherInfo['data']['voucherNumber'];
    	$data['typeClass'] = $voucherInfo['data']['typeClass'];
    	$data['voucherValue'] = $voucherInfo['data']['typeValue'];
    	$data['createdDatetime'] = time();
    	$data['validData'] = $voucherInfo['data']['endTime'];
    	$data['typeId'] = $voucherInfo['data']['typeId'];
    
    	if(D('Member')->addMemberVoucher($data)){
    		$data['validData']=date('Y-m-d',$data['validData']);
    		//$memberVoucherList=$this->packageajax($voucherClass);
    		$this->success('添加成功！');
    	}else{
    		$this->error('添加失败！');
    	}
    }
    

}