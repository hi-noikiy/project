<?php
namespace Api\Controller;
use Think\Controller;

class UserController extends ServiceController {
	/**
	 * 反馈
	 */
	public function feedback(){
		if($this->param['type']=='1'){
			$upload = new \Think\Upload(); // 实例化上传类
			$upload->maxSize   =     3145728 ;// 设置附件上传大小
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->rootPath  =     'Uploads/'; // 设置附件上传根目录
			$upload->savePath  =     'feedback/'; // 设置附件上传（子）目录
			// 上传文件
			$info   =   $upload->upload();
			if($info['img']){
				$data['img']=$info['img']['savepath'].$info['img']['savename'];
			}else{
				$this->error('发送内容不能为空');
			}
		}else{
			$data['content']=$this->param['content'];
			if(empty($data['content'])){
				$this->error('发送内容不能为空');
			}
		}
		$user=$this->appInfo['userInfo']['id'];
		$data['uid']=$user['id'];
		$data['puid']=$user['id'];
		$data['cinemaGroupId']=$this->appInfo['cinemaGroupInfo']['cinemaGroupId'];
		$data['time']=time();
		D('feedback')->add($data);
		$this->fbajax();
		
	}
	/**
	 * 反馈列表
	 */
	function fbajax(){
		$user=$this->appInfo['userInfo']['id'];
		$feedbacks=D('feedback')->getList(array('puid'=>$user['id'],'cinemaGroupId'=>$this->appInfo['cinemaGroupInfo']['cinemaGroupId']));
		if(empty($feedbacks)){
			$feedbacks=array();
		}
		$this->success('', $feedbacks);
	}
 
	/**
	 * 获取用户主页信息
	 */
	function getHomeInfo(){
		$this->success('', $this->appInfo);
	}
	
	/**
	 * 全部消费记录
	 */
	public function record(){
		$type= $this->param['type'];
		if(empty($type)){
			$this->error('参数错误！', '11001');
		}
		$page= $this->param['page'];
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		$user=$this->getBindUserInfo($this->appInfo['userInfo']);
		$moneylogs=D('moneyLog')->getList('',array('uid'=>$user['id']),$type,$start,$this->pageNum);
		if(empty($moneylogs)){
			$this->success('');//
		}
		$this->success('', $moneylogs);
	}
	/**
	 * 是否有未支付订单
	 */
	function hasOrder(){
		$user = $this->getBindUserInfo($this->appInfo['userInfo']);
    	$result=D('order')->updateOrder($user['id']); //同步订单状态
    	$this->success('',$result);
    	
	}
	
	/**
	 * 我的评论
	 */
	function getMyViews(){
		$page= $this->param['page'];
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		$user=$this->getBindUserInfo($this->appInfo['userInfo']);
		$map['status']=0;
		$map['pid']=0;
		$map['uid']=$user['id'];
		$map['memberGroupId']=$this->appInfo['memberGroupId'];
		$views=D('film')->getMyViews($map,$start,$this->pageNum);
		$this->success('',$views);
	}
	
	/**
	 * 我的影票订单
	 */
	function getCinemaOrders(){
		$status= $this->param['status'];
		$page= $this->param['page'];
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
		if(empty($status)){
			$this->error('参数错误！', '11001');
		}
		if($status=='1'){
			$map['status']=3;
		}elseif($status=='2'){
			$map['status']=0;
		}elseif($status=='3'){
			$map['status']=array('not in',array(0,3));
		}elseif ($status == 9) {
            $map['status'] = 9;
        }
		$user=$this->getBindUserInfo($this->appInfo['userInfo']);
		$map['uid']=$user['id'];
		$orders=D('order')->getList('orderCode,myPrice,seatCount,filmNo,lockTime,status,printNo,verifyCode,filmName,startTime,seatIntroduce,copyType,downTime,hallName,cinemaName,orderTime,mobile',$map,$start,$this->pageNum);
		if(!empty($orders)){
			foreach ($orders as $k=>$v){
				$film[$k]=D('film')->getFilm(array('filmNo'=>$v['filmNo']));
				$orders[$k]['filmImg']=$film[$k]['image'];
                $orders[$k]['seatInfo'] = json_decode($v['seatInfo'], true);
			}
			$this->success('',$orders);
		}else{
			$this->error('');//
		}
	}

    /**
     * 可退票列表
     */
    public function canBackOrderList()
    {
        $status= 3;
        $page= $this->param['page'];
        if(empty($page)){
            $page=1;
        }
        $start=($page-1)*$this->pageNum;

        $map['status']=array('not in',array(0,3));

        $user=$this->getBindUserInfo($this->appInfo['userInfo']);
        $map['uid']=$user['id'];
        $orders=D('order')->getList('seatInfo, orderCode,myPrice,seatCount,filmNo,lockTime,status,printNo,verifyCode,filmName,startTime,seatIntroduce,copyType,downTime,hallName,cinemaName,orderTime,mobile',$map,$start,$this->pageNum);
        if(!empty($orders)){
            foreach ($orders as $k=>$v){
                $film[$k]=D('film')->getFilm(array('filmNo'=>$v['filmNo']));
                $orders[$k]['filmImg']=$film[$k]['image'];
                $orders[$k]['seatInfo'] = json_decode($v['seatInfo'], true);
            }
            $this->success('',$orders);
        }else{
            $this->error('');//
        }
    }

    /**
     * 退票
     */
    public function backTieck()
    {
       if(empty($this->param['orderCode']) || empty($this->param['message'])){
            $this->error('参数校验信息错误', 100101);
        }

        $this->success('退票成功！');
    }
	
	/**
	 * 获取绑定会员卡信息
	 */
	function getCardInfo(){
		$user=$this->appInfo['userInfo'];
		if(empty($user['mobile'])){
			$this->error('没有绑定会员卡信息');
		}
		$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'memberGroupId'=>$this->appInfo['memberGroupId']));
		if(empty($bind)){
			$this->error('没有绑定会员卡信息');
		}
		$user=D('member')->getUser(array('cardNum'=>$bind['cardId'],'businessCode'=>$bind['cinemaCode'],'memberGroupId'=>$this->appInfo['memberGroupId']));
		if(empty($user)){
			$this->error('找不到该会员卡信息');
		}
		$myuser['levelName']=$user['levelName'];
		$myuser['integral']=round($user['integral'],1);
		$myuser['money']=$user['basicBalance']+$user['donateBalance'];
		$myuser['expirationTime']=date('Y-m-d',$user['expirationTime']);
		$myuser['cardStatus']=$user['cardStatus'];
		$this->success('', $myuser);
	}
	
    /**
     * 注册
     */
    function registered(){
    	$mobile = $this->param['userMobile'];
    	$randomCode = $this->param['validateCode'];
    	$passWord =$this->param['userPasswd'];
    	if(empty($randomCode)||empty($mobile)||empty($passWord)){
    		$this->error('参数错误！', '11001');
    	}
    	if(!checkMobile($mobile)){
    		$this->error('手机格式不正确!');
    	}
    	if(strlen($passWord)<6){
    		$this->error('密码不能小于6位数');
    	}
        S('tokenId_getMobileVerification' . $mobile . 'registe', array('code'=>117797), 120);
    	$send = S('tokenId_getMobileVerification' . $mobile . 'register');

        if (empty($send)) {
            $this->error('验证码已过期！');
        }

        wlog(json_encode($send) . json_encode($this->param), 'testLog');
    	if(!empty($send)){
    		if($randomCode != $send['code'] || empty($randomCode)){
    			$this->error('验证码填写错误');
    		}else{
    			$arr['mobile']=$mobile;
    			$arr['mpword']=encty($passWord);
    			$arr['bindTime']=time();
    			$arr['levelCode']=$this->appInfo['cinemaGroupInfo']['defaultLevel'];
                $arr['memberGroupId']=$this->appInfo['cinemaGroupInfo']['defaultLevel'];
                $arr['userName'] = $this->param['userMobile'];
                $arr['otherName']=$this->param['userMobile'];
    			$arr['cinemaGroupId']=$this->appInfo['cinemaGroupInfo']['cinemaGroupId'];

    			if(D('Member')->add($arr)){
    				$this->success('注册成功', $arr);
    				S('tokenId_getMobileVerification' . $mobile . 'register',null);
    			}else{
    				$this->error('注册提交失败' . json_encode($arr));
    			}
    		}
    	}
    } 
    
    /**
     *判断登录
     */
    public function login(){
    	$cinemaCode=$this->param['cinemaCode'];
    	$loginNum=$this->param['userAccount'];
    	$passWord=$this->param['userPasswd'];



    	if(empty($loginNum)|| empty($passWord)){
    		$this->error('参数错误！', '11001');
    	}

        wlog('用户登录：' . json_encode($this->param), 'userLogin');

    	if(!empty($cinemaCode)){   //会员卡登录
    		$cinema=D('cinema')->find($cinemaCode);
    		$hasUser=D('member')->getBindInfo(array('mobile'=>$loginNum,'cinemaCode'=>$cinemaCode,'cinemaGroupId'=>$this->appInfo['cinemaGroupInfo']['cinemaGroupId']));
    		if(!empty($hasUser)){
    			$loginNum=$hasUser['cardId'];
    		}
    		$loginarr=array('cinemaCode'=>$cinemaCode,'loginNum'=>$loginNum,'password'=>$passWord,'link'=>$cinema['link'],'cinemaName'=>$cinema['cinemaName']);
    		$loginResult = D('ZMUser')->verifyMemberLogin($loginarr);
    		// print_r($loginResult);
            if($loginResult['ResultCode'] == 0){//登录成功

    			$result=D('member')->loginMember($loginResult,$cinemaCode,$passWord,$this->appInfo);
    			if($result['status']=='0'){
                    $appInfo = $this->appInfo;
                    $appInfo['userInfo'] = $result['info'];
    				S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $appInfo, 604800);
    				$this->success('', $this->setAppUserInfo($result['info']));
    			}else{
    				$this->error($result['info']);
    			}
    		}else{
    			$this->error($loginResult['Message']);
    		}
    	}else{   //手机用户登录
    		if(!checkMobile($loginNum)){
    			$this->error('手机格式不正确');

    		}
            // print_r($this->appInfo);
    		$user = D('member')->getUser(array('mobile'=>$loginNum,'cinemaGroupId'=>$this->appInfo['cinemaGroupInfo']['cinemaGroupId']));
            
            if(empty($user)){
    			$this->error('该手机号未注册');
    		}elseif($user['mpword']!=encty($passWord)){
    			$this->error('手机密码错误');
    		}else{
                $appInfo = $this->appInfo;
                if ($appInfo['xgTokenId']) {
                    $userarr['xgTokenId'] = $appInfo['xgTokenId'];
                    $userarr['deviceType'] = $appInfo['deviceType'];
                    $userarr['deviceNumber'] = $appInfo['deviceNumber'];
                }               
                $userarr['appVersion'] = $appInfo['appVersion'];
                $userarr['id'] = $user['id'];

                wlog('更新用户信鸽tokenId' . json_encode($userarr), 'xgTokenId');
                M('member')->save($userarr);
    			$appInfo['userInfo'] = $user;
                S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $appInfo, 604800);
                wlog('用户登录：' . json_encode($this->setAppUserInfo($user)), 'userLogin');
    			$this->success('',  $this->setAppUserInfo($user));
    		}
    	}
    }
	
    /**
     * 登出
     */
    function logout(){

        $cachePre = C('CACHE_NAME_LIST');
        S($cachePre . 'APPINFOtokenId_' . $this->param['tokenId'], null);
        S($cachePre . 'APPINFOUserInfotokenId_' . $this->param['tokenId'], null);

        $this->success('登出成功');
    }
    /**
     * 密码找回
     */
    function findPasswd(){
    	$mobile = $this->param['userMobile'];
    	$randomCode = $this->param['validateCode'];
    	$passWord =$this->param['newUserPasswd'];
    	if(empty($randomCode)||empty($mobile)||empty($passWord)){
    		$this->error('参数错误！', '11001');
    	}
    	if(!checkMobile($mobile)){
    		$this->error('手机格式不正确!');
    	}
    	if(strlen($passWord)<6){
    		$this->error('密码长度必须大于6位!');
    	}
    	$send=S('tokenId_getMobileVerification' . $mobile . 'find');
    	if(!empty($send)){
    		if($randomCode!=$send['code'] || empty($randomCode)){
    			$this->error('验证码填写错误');
    		}else{
    			$arr['mpword']=encty($passWord);
    			$result=D('Member')->saveUser(array('mobile'=>$mobile,'memberGroupId'=>$this->appInfo['memberGroupId']),$arr);
    			if($result!==false){
    				$this->appInfo['userInfo']['mpword']=encty($passWord);
    				S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $this->appInfo, 604800);
    				S('tokenId_getMobileVerification' . $mobile . 'find',null);
    				$this->success('密码修改成功',$arr);
    			}else{
    				$this->error('修改提交失败');
    			}
    		}
    	}else{
            $this->error('验证码已过期！');
        }
    }
    
    /**
     * 修改用户信息
     */
    function setUserInfo(){
    	$user = $this->appInfo['userInfo'];
        $appInfo = $this->appInfo;
    	if($this->param['type']=='1'){
    		$upload = new \Think\Upload(); // 实例化上传类
    		$upload->maxSize   =     3145728 ;// 设置附件上传大小
    		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->rootPath  =     C('__UPLOAD__'); // 设置附件上传根目录
    		$upload->savePath  =     'userIcons/'; // 设置附件上传（子）目录
    		$info   =   $upload->upload();

            wlog('上传头像：' .  $upload->getError() . json_encode($info), 'testLog');
    		
            if ($upload->getError()) {
                $this->error($upload->getError());
            }

            if($info['userIcon']){
                $data['headImage']=$info['userIcon']['savepath'].$info['userIcon']['savename'];
                $appInfo['userInfo']['headImage'] = $data['headImage'];
            }
    	}elseif(!empty($this->param['userNickname'])){
            $appInfo['userInfo']['otherName'] = $this->param['userNickname'];

    		$data['otherName']=$this->param['userNickname'];
    	}elseif(!empty($this->param['email'])){
            $appInfo['userInfo']['email'] = $this->param['email'];
    		$data['email']=$this->param['email'];
    	}elseif(!empty($this->param['userSex'])){
            $appInfo['userInfo']['sex'] = $this->param['userSex'];
    		$data['sex']=$this->param['userSex'];
    	}elseif(!empty($this->param['userBirthday'])){
            $appInfo['userInfo']['birthday'] = $this->param['userBirthday'];
    		$data['birthday']=$this->param['userBirthday'];
    	}elseif(!empty($this->param['newCardPasswd'])){
    		$user=$this->getBindUserInfo($user);
    		if(empty($user['cardNum'])){
    			$this->error('无绑定会员卡');
    		}
    		if($user['pword']!=encty($this->param['oldPasswd'])){
    			$this->error('原始密码错误');
    		}
    		$data['pword']=encty($this->param['newCardPasswd']);
    		if(strlen($this->param['newCardPasswd'])<6){
    			$this->error('密码长度必须大于6位!');
    		}
    		$arr['cinemaCode']=$user['businessCode'];
    		$cinema=D('cinema')->find($arr['cinemaCode']);
    		$arr['loginNum']=$user['cardNum'];
    		$arr['link']=$cinema['link'];
    		$arr['oldPassword']=$this->param['oldPasswd'];
    		$arr['newPassword']=$this->param['newCardPasswd'];
    		$result=D('ZMUser')->modifyMemberPassword($arr);
    		if($result['ResultCode']!='0'){
    			$this->error('暂不支持修改会员卡密码');
    		}

            $appInfo['userInfo']['pword'] = encty($this->param['newCardPasswd']);
    	}elseif(!empty($this->param['newMobilePasswd'])){
    		$user=$this->getBindCardInfo($user);
            wlog(json_encode($user), 'test');
    		if(empty($user['mobile'])){
    			$this->error('无绑定手机号');
    		}
    		if($user['mpword']!=encty($this->param['oldPasswd'])){
    			$this->error('原始密码错误');
    		}
    		if(strlen($this->param['newMobilePasswd'])<6){
    			$this->error('密码长度必须大于6位!');
    		}
            $appInfo['userInfo']['mpword'] = encty($this->param['newMobilePasswd']);
    		$data['mpword'] = encty($this->param['newMobilePasswd']);
    	}
    	$result=D('member')->saveUser(array('id'=>$user['id']), $data);
    	if($result!==false){
    		if(!empty($result)&&$this->param['type']=='1'){
 				unlink(C('__UPLOAD__').$user['headImage']);
    		}
            wlog('更新用户信息成功！'. json_encode($this->param) . json_encode($this->appInfo), 'testLog');
            S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $appInfo, 604800);
            $this->success('修改成功', '');
    	}else{
    		$this->error('修改失败');
    	}
    }
    /**
     *获取用户信息
     */
    public function getUserInfo(){
        $appInfo = $this->appInfo;
        // print_r($this->appInfo);
        $userInfo = D('Member')->getUser(array('id'=>$appInfo['userInfo']['id'],'cinemaGroupId'=>$this->appInfo['cinemaGroupInfo']['cinemaGroupId']));
    	$appInfo['userInfo'] = $userInfo;
        S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $appInfo, 604800);
        $user = $this->setAppUserInfo($userInfo);
        $this->success('', $user, 60);
    }
    
    /**
     * 会员卡手机绑定
     */
    function setUserBind(){
    	$user = $this->appInfo['userInfo'];
    	$mobile=$this->param['userMobile'];
    	if(!empty($mobile)){  //会员卡绑手机
    		if(!checkMobile($mobile)){
    			$this->error('手机格式不正确');
    		}
    		$bind=D('member')->getBindInfo(array('mobile'=>$mobile,'memberGroupId'=>$this->appInfo['memberGroupId']));

    		if(!empty($bind)){
    			$this->error('该手机号已被卡号'.$bind['cardId'].'绑定');
    		}
    		$cardbind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'memberGroupId'=>$this->appInfo['memberGroupId']));
    		if(!empty($cardbind)){
    			$this->error('该会员卡已被手机号'.$cardbind['mobile'].'绑定');
    		}
    		$randomCode=$this->param['validateCode'];
    		$send=S('tokenId_getMobileVerification' . $mobile . 'bind');
    		if(!empty($send)){
    			if($randomCode!=$send['code'] || empty($randomCode)){
    				$this->error('验证码填写错误');
    			}else{
    				$mobileUser=D('member')->getUser(array('mobile'=>$mobile,'memberGroupId'=>$this->appInfo['memberGroupId']));
    				if(empty($mobileUser)){
    					$userarr['mobile']=$mobile;
    					$userarr['memberGroupId']='99101';
    					$userarr['levelCode']='99101';
    					$userarr['memberGroupId']=$this->appInfo['memberGroupId'];
    					$userarr['mpword']=$user['pword'];
    					$userarr['otherName']=$mobile;
    					if(!D('member')->add($userarr)){
    						$this->error('添加新手机账号失败');
    					}
    				}
    				
    				$arr['cardId']=$user['cardNum'];
    				$arr['cinemaCode']=$user['businessCode'];
    				$arr['mobile']=$mobile;
    				$arr['memberGroupId']=$this->appInfo['memberGroupId'];
    				if(D('memberBind')->add($arr)){
    					S('tokenId_getMobileVerification' . $mobile . 'bind',null);
    					$this->success('关联成功', $arr);
    				}else{
    					$this->error('关联失败');
    				}
    			}
    		}else{
    			$this->error('验证码已失效');
    		}
    	}else{  //手机绑会员卡
    		$cinemaCode=$this->param['cinemaCode'];
    		$cardId=$this->param['userAccount'];
    		$passwd=$this->param['userPasswd'];
    		$cinema=D('cinema')->find($cinemaCode);
    		$cardUser=D('member')->getUser(array('cardNum'=>$cardId,'businessCode'=>$cinemaCode,'memberGroupId'=>$this->appInfo['memberGroupId']));
            if(empty($cardUser)){  //会员卡不存在
    			$loginResult = D('ZMUser')->verifyMemberLogin(array('cinemaCode'=>$cinemaCode,'loginNum'=>$cardId,'password'=>$passwd,'link'=>$cinema['link'],'cinemaName'=>$cinema['cinemaName']));
    			
                wlog('参数' . json_decode($this->param) . json_decode($loginResult),'testLog');
                if($loginResult['ResultCode']=='0'){
    				$result=D('member')->loginMember($loginResult,$cinemaCode,$passwd);
    				if($result['status']!='0'){
    					$this->error('添加新会员卡信息失败');
    				}else{
                        $arr['cardId']=$cardId;
                        $arr['cinemaCode']=$result['info']['businessCode'];
                        $arr['mobile']=$user['mobile'];
                        $arr['memberGroupId']=$this->appInfo['memberGroupId'];
                        if(D('memberBind')->add($arr)){
                            // S('tokenId_getMobileVerification' . $mobile . 'bind',null);
                            $this->success('关联成功', $arr);
                        }else{
                            $this->error('关联失败');
                        }
                    }
    			}else{
    				$this->error($loginResult['Message']);
    			}
    		}else{  //会员卡已存在
    			if(encty($passwd)!=$cardUser['pword']){
    				$this->error('会员卡密码错误');
    			}

    			$bind=D('member')->getBindInfo(array('cardId'=>$cardId,'cinemaCode'=>$cardUser['businessCode'],'memberGroupId'=>$this->appInfo['memberGroupId']));
    			if(!empty($bind)){
    				$this->error('该会员卡已被绑定');
    			}
    			$mobilebind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'memberGroupId'=>$this->appInfo['memberGroupId']));
    			if(!empty($mobilebind)){
    				$this->error('该手机号已被绑定');
    			}
    			$arr['cardId']=$cardId;
    			$arr['cinemaCode']=$cardUser['businessCode'];
    			$arr['mobile']=$user['mobile'];
    			$arr['memberGroupId']=$this->appInfo['memberGroupId'];
    			if(D('memberBind')->add($arr)){
    				S('tokenId_getMobileVerification' . $mobile . 'bind',null);
    				$this->success('关联成功', $arr);
    			}else{
    				$this->error('关联失败');
    			}
    		}
    	}
    } 
    
    /**
     * 解除绑定
     */
    function setUserUnBind(){
    	$user=$this->appInfo['userInfo'];
    	$type=$this->param['type'];
    	if($type=='1'){ //解绑手机
    		$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'memberGroupId'=>$this->appInfo['memberGroupId']));
    		if(empty($bind)){
    			$this->error('该绑定关系不存在');
    		}else{
    			$randomCode=$this->param['validateCode'];
    			$send=S('tokenId_getMobileVerification' . $bind['mobile'] . 'unbind');
    			if(!empty($send)){
    				if($randomCode!=$send['code'] || empty($randomCode)){
    					$this->error('验证码填写错误');
    				}else{
    					if(D('memberBind')->delete($bind['id'])){
    						$this->success('解绑成功', $bind);
    					}else{
    						$this->error('解绑失败');
    					}
    				}
    			}else{
    				$this->error('验证码已超时');
    			}
    		}
    	}else{  //解绑会员卡
    		$pword=$this->param['passWord'];
    		$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'memberGroupId'=>$this->appInfo['memberGroupId']));
    		if(empty($bind)){
    			$this->error('该绑定关系不存在');
    		}else{
    			$cardUser=D('member')->getUser(array('cardNum'=>$bind['cardId'],'businessCode'=>$bind['cinemaCode'],'memberGroupId'=>$this->appInfo['memberGroupId']));
    			if(encty($pword)!=$cardUser['pword']){
    				$this->error('密码输入错误');
    			}
    			if(D('memberBind')->delete($bind['id'])){
    				$this->success('解绑成功','');
    			}else{
    				$this->error('解绑失败');
    			}
    		}
    	}
    	
    }
    /**
     * 2.7.1获取可以充值的支付方式
     */
    public function getRechargePayway()
    {
        if(empty($this->param['cinemaCode'])){
            $this->error('参数错误！', '11001');
        }



        $rechargePayConfig = getNowPayWay($this->param, 'recharge');

        foreach ($rechargePayConfig as $key => $value) {
            $rechargePayway[] = $this->payConfig[$value];
        }
        $this->success('', $rechargePayway);

    }

    /**
     * 充值
     */
    function recharge(){
    	$rechargeAmount = $this->param['rechargeAmount'];
        $payType = $this->param['payType'];
        $cinemaCode = $this->param['cinemaCode'];

        wlog('开始充值' . json_encode($this->param), 'testLog');


    	if(empty($rechargeAmount) || empty($payType) || empty($cinemaCode)){
    		$this->error('参数校验信息错误1', 100101);
    	}
    	$user = $this->getBindUserInfo($this->appInfo['userInfo']);
        // print_r($userInfo);
    	$cinemaInfo = D('Cinema')->getCinemaInfo('cinemaName, payConfig', array('cinemaCode' => $cinemaCode));
    	$payConfig = json_decode($cinemaInfo['payConfig'], true);
        // print_r($payConfig);
        $topUparr=array(
    			'uid'=>$user['id'],
    			'cinemaCode'=>$cinemaCode,
    			'cinemaName'=>$cinemaInfo['cinemaName'],
    			'money'=>$rechargeAmount,
    			'createTime'=>time(),
    			'way'=>'app'
    	);
    	if(!empty($user['cardNum'])){
    		$topUparr['cardId']=$user['cardNum'];
    	}else{
    		$topUparr['mobile']=$user['mobile'];
    	}
    	if ($payType == 'alipay') {//支付宝充值
    		$alipayConfig = $payConfig['alipayConfig'];
    		$topUparr['mchId']=$alipayConfig['partnerId'];
    		$topUparr['type']='alipay';
    	}elseif($payType == 'weixinpay'){   //微信支付
    		$weixinpayConfig =$payConfig['weixinpayConfig'];
    		$topUparr['mchId']=$weixinpayConfig['mchid'];
    		$topUparr['type']='weixinpay';
		}elseif($payType == 'unionpay'){   //银联
			$unionpayConfig = $payConfig['unionpayConfig'];
			$topUparr['mchId']=$unionpayConfig['unionPayId'];
			$topUparr['type']='unionpay';
		}

        // print_r($alipayConfig);

        // print_r($topUparr);

        $orderno=D('orderRecharge')->add($topUparr);
    	if($orderno){
    		$rechargeAmount=0.01;
    		if ($payType == 'alipay') {//支付宝充值
    			$data['alipay'] = array(
    					'notifyUrl'      => C('PAY_URL').'recharge/alipay_app.html',
    					'PartnerID'      => $alipayConfig['partnerId'],
    					'SellerID'       => $alipayConfig['sellerEmail'],
    					'Md5Key'         => '',
    					'PartnerPrivKey' => getKeyInfo($alipayConfig['privateKey'], 27),
    					'AlipayPubKey'   => getKeyInfo($alipayConfig['publicKey'], 26),
    					'outTradeNo'     => time().'recharge'.$orderno,
    					"subject"        => $cinemaInfo['cinemaName'] . ' 会员APP充值',
    					"totalFee"       => $rechargeAmount,
    					"body"           => $cinemaInfo['cinemaName'] . ' 会员APP充值' . $rechargeAmount . '元',
    					"showUrl"        => 'http://wap.zrfilm.com',
    			);
    			$data['alipay']['orderid']=$orderno;
    			$this->success('创建支付订单成功', $data);
    		
    		}elseif($payType == 'weixinpay'){   //微信支付
    			//统一支付接口类
    			$unifiedOrder = new \Org\Wechat\UnifiedOrder($weixinpayConfig);
    			/*-----------------------------必填--------------------------*/
    			$unifiedOrder->setParameter('body', $cinemaInfo['cinemaName'] . ' 注册会员APP充值');//商品描述岚樨微支付平台
    			$unifiedOrder->setParameter('out_trade_no', date('YmdHis') . $orderno);//商户订单号
    			$unifiedOrder->setParameter('total_fee', $rechargeAmount * 100);//总金额（微信支付以人民币“分”为单位）
    			/*-------------------------------------------------------*/
    			$unifiedOrder->setParameter('notify_url', C('PAY_URL').'recharge/weixinpay_app.html');//通知地址
    			$unifiedOrder->setParameter('trade_type', 'APP');//交易类型
    			$unifiedOrder->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);//交易IP
    			
    			$weixinPayInfo = $unifiedOrder->getResult();
    			
    			if ($weixinPayInfo['return_code'] == 'FAIL') {
    				$this->error($weixinPayInfo['return_msg']);
    			}
    			$data['weixinpay'] = array(
    					'appid'     => $weixinPayInfo['appid'],
    					'partnerid' => $weixinPayInfo['mch_id'],
    					'prepayid'  => $weixinPayInfo['prepay_id'],
    					'package'   => 'Sign=WXPay',
    					'noncestr'  => $weixinPayInfo['nonce_str'],
    					'timestamp' => time()
    			);
    			
                $data['weixinpay']['sign'] = $unifiedOrder->getSign($data['weixinpay']);
                $data['weixinpay']['orderid'] =$orderno;
    			$this->success('创建支付订单成功', $data);
    		
    		}elseif($payType == 'unionpay'){   //银联
    			$orderTitle =  $cinemaInfo['cinemaName'] . ' 注册会员APP充值';
    			$unionPayKey = $unionpayConfig['unionPayKey'];
    			$unionPayId = $unionpayConfig['unionPayId'];
    			$conf = array(
    					"version"          => '1.0.0',
    					"charset"          => 'UTF-8',
    					"transType"        => '01',
    					"merId"            => $unionPayId,
    					"frontEndUrl"      =>  'http://wap.zrfilm.com/',
    					'backEndUrl'       =>C('PAY_URL').'recharge/unionpay_app.html',
    					"orderTime"        => date('YmdHis'),
    					"orderTimeout"     => date('YmdHis', time() + 20 * 60),
    					"orderNumber"      => date('YmdHis') . 'N' . $orderno,
    					"orderAmount"      => round($rechargeAmount * 100, 2),
    					"orderCurrency"    => 156,
    					"orderDescription" => $orderTitle,
    			);
    			$conf['signature'] = unionSign($conf, $unionPayKey);
    			$conf['signMethod'] = 'MD5';
    			$result = getHttpResponsePOST('https://mgate.unionpay.com/gateway/merchant/trade', array(CURLOPT_HTTPHEADER => array('Expect:'), CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false), $conf) ;
    			parse_str($result, $result);
    		
    			if ( !empty($result) && isset($result['respCode']) && isset($result['tn']) && isset($result['signature']) && $result['respCode'] == '00' && $result['signature'] == unionSign($result, $unionPayKey) ) {
    				$data['unionpay']['tn'] = $result['tn'];
    				$this->success('创建支付订单成功', $data);
    			}
    		
    			$this->error('生成订单失败');
    		
    		}
    	}else{
            wlog('添加充值订单:' . arrayeval($topUparr), 'testLog');
        }
        die();
    }
    
    /**
     * 获取充值状态
     */
    function getRechargeStatus(){
    	$orderid=$this->param['orderid'];
    	$order=D('orderRecharge')->find($orderid);
    	$this->success('', $order);
    }
    

    /**
     * 5.0.1添加卡包
     */
    public function addVoucher()
    {
        if(empty($this->param['voucherNum'])){
            $this->error('参数错误！', '11001');
        }

        $voucherInfo = D('Voucher')->checkVoucher($this->param['voucherNum']);
        if ($voucherInfo['status'] ==1) {
            $this->error($voucherInfo['content']);
        }
        $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
        //判断是否被添加

        if(D('Member')->checkVoucher($this->param['voucherNum'])){
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
        wlog(json_encode($data), 'addvoucher');
        if(D('Member')->addMemberVoucher($data)){
            $this->success('添加成功！');
        }else{
            $this->error('添加失败！');
        }
    }
    /**
     * 5.0.2获取用户券包
     */
    public function userVoucherList()
    {

        $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
        $nowPage = $this->param['page'] ? $this->param['page'] : 1;
        $pageNum = 5;

        if (isset($this->param['voucherClass'])) {
            $pageNum = 50;
            $map['typeClass'] = $this->param['voucherClass'];
        }
        $map['memberId'] = $userInfo['id'];
        $map['validData'] = array('EGT', strtotime(date('Y-m-d')));
        $map['isUnlock'] = 0;
        $map['isUse'] = 0;
        $memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $map, ($nowPage - 1) * $pageNum . ',' . $pageNum, 'validData asc');
        
        $map['memberId'] = $userInfo['id'];
        $map['validData'] = array('between',strtotime(date('Y-m-d')) . ',' . (strtotime(date('Y-m-d')) + 604800));
        $map['isUnlock'] = 0;
        $map['isUse'] = 0;
        $expireVoucherList = D('Member')->getMemberVoucherList('typeId', $map, ($nowPage - 1) * $pageNum . ',' . $pageNum, 'validData asc');
        $expireNum = count($expireVoucherList);

        foreach ($memberVoucherList as $key => $value) {
            $memberVoucherList[$key]['expireNum'] = $expireNum;
        }

        $this->success('', $memberVoucherList);
    }

    /**
     * 5.0.5获取用户使用记录
     */
    public function userVoucherHistory()
    {
        $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);

        $nowPage = $this->param['page'] ? $this->param['page'] : 1;
        $pageNum = 5;
        $map['memberId'] = $userInfo['id'];
        $map['_string'] = 'validData < ' . strtotime(date('Y-m-d')) . ' or isUnlock=1 or isUse=1';
        $memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData, isUse, isUnlock', $map, ($nowPage - 1) * $pageNum . ',' . $pageNum, 'validData asc');
        
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

        $this->success('', $memberVoucherList);
    }
    

    /**
     * 5.0.6使用积分
     */
    public function useIntegral()
    {
        if(empty($this->param['orderId']) || empty($this->param['type'])){
            $this->error('参数错误！', '11001');
        }
        //取得用户信息
        // $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
        // if ($userInfo['integral'] < $this->param['integral']) {
        //     $this->error('积分不足！');
        // }
        //取得订单信息
        if ($this->param['type'] == 'film') {
            $orderInfo = S('getBuyPaywayOrderInfo' . $this->param['orderId']);
            if (empty($orderInfo)) {
                $orderInfo = D('Order')->findObj($this->param['orderId']);
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
            }

            $orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
            $orderInfo['otherPayInfo']['integral'] = true;
            $orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
            $data['orderCode'] = $this->param['orderId'];
            $data['otherPayInfo'] = $orderInfo['otherPayInfo'];
            if (D('Order')->saveObj($data)) {
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
                $this->success('使用积分成功！');
            }else{
                $this->error('使用积分失败');
            }
        }else{
            $goodOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId']);
            if (empty($goodOrderInfo)) {
                $goodOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $this->param['orderId']));
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
            }

            $goodOrderInfo['otherPayInfo'] = json_decode($goodOrderInfo['otherPayInfo'], true);
            $goodOrderInfo['otherPayInfo']['integral'] = true;
            $goodOrderInfo['otherPayInfo'] = json_encode($goodOrderInfo['otherPayInfo']);
            
            $data['otherPayInfo'] = $goodOrderInfo['otherPayInfo'];
            $map['id'] = $this->param['orderId'];
            if (D('Goods')->updateGoodsOrder($data, $map)) {
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
                $this->success('使用积分成功！');
            }else{
                $this->error('使用积分失败');
            }
        }
    }

    /**
     * 5.0.7取消使用积分
     */
    public function cancelIntegral()
    {
        if(empty($this->param['orderId']) || empty($this->param['type'])){
            $this->error('参数错误！', '11001');
        }
        //取得用户信息
        // $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
        // if ($userInfo['integral'] < $this->param['integral']) {
        //     $this->error('积分不足！');
        // }
        //取得订单信息
        if ($this->param['type'] == 'film') {
            $orderInfo = S('getBuyPaywayOrderInfo' . $this->param['orderId']);
            if (empty($orderInfo)) {
                $orderInfo = D('Order')->findObj($this->param['orderId']);
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
            }

            $orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
            unset($orderInfo['otherPayInfo']['integral']);
            $orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
            $data['orderCode'] = $this->param['orderId'];
            $data['otherPayInfo'] = $orderInfo['otherPayInfo'];
            if (D('Order')->saveObj($data)) {
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
                $this->success('取消使用积分成功！');
            }else{
                $this->error('取消使用积分失败');
            }
        }else{
            $goodOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId']);
            if (empty($goodOrderInfo)) {
                $goodOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $this->param['orderId']));
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
            }

            $goodOrderInfo['otherPayInfo'] = json_decode($goodOrderInfo['otherPayInfo'], true);
            unset($goodOrderInfo['otherPayInfo']['integral']);
            $goodOrderInfo['otherPayInfo'] = json_encode($goodOrderInfo['otherPayInfo']);
            
            $data['otherPayInfo'] = $goodOrderInfo['otherPayInfo'];
            $map['id'] = $this->param['orderId'];

            if (D('Goods')->updateGoodsOrder($data, $map)) {
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
                $this->success('取消使用积分成功！');
            }else{
                $this->error('取消使用积分失败');
            }
        }
    }


    /**
     * 5.0.8使用余额
     */
    public function useAccount()
    {
        if(empty($this->param['orderId']) || empty($this->param['type'])){
            $this->error('参数错误！', '11001');
        }
        //取得用户信息
        // $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
        // if ($userInfo['integral'] < $this->param['integral']) {
        //     $this->error('积分不足！');
        // }
        //取得订单信息
        if ($this->param['type'] == 'film') {
            $orderInfo = S('getBuyPaywayOrderInfo' . $this->param['orderId']);
            if (empty($orderInfo)) {
                $orderInfo = D('Order')->findObj($this->param['orderId']);
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
            }

            $orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
            $orderInfo['otherPayInfo']['account'] = true;
            $orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
            $data['orderCode'] = $this->param['orderId'];
            $data['otherPayInfo'] = $orderInfo['otherPayInfo'];
            if (D('Order')->saveObj($data)) {
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
                $this->success('使用余额支付成功！');
            }else{
                $this->error('使用余额支付失败');
            }
        }else{

            $goodOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId']);
            if (empty($goodOrderInfo)) {
                $goodOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $this->param['orderId']));
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
            }

            $goodOrderInfo['otherPayInfo'] = json_decode($goodOrderInfo['otherPayInfo'], true);
            $goodOrderInfo['otherPayInfo']['account'] = true;
            $goodOrderInfo['otherPayInfo'] = json_encode($goodOrderInfo['otherPayInfo']);
            
            $data['otherPayInfo'] = $goodOrderInfo['otherPayInfo'];
            $map['id'] = $this->param['orderId'];

            if (D('Goods')->updateGoodsOrder($data, $map)) {
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
                $this->success('使用余额支付成功！');
            }else{
                $this->error('使用余额支付失败');
            }
        }
    }

    /**
     * 5.0.9取消使用余额
     */
    public function cancelAccount()
    {
        if(empty($this->param['orderId']) || empty($this->param['type'])){
            $this->error('参数错误！', '11001');
        }
        //取得用户信息
        // $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
        // if ($userInfo['integral'] < $this->param['integral']) {
        //     $this->error('积分不足！');
        // }
        //取得订单信息
        if ($this->param['type'] == 'film') {
            $orderInfo = S('getBuyPaywayOrderInfo' . $this->param['orderId']);
            if (empty($orderInfo)) {
                $orderInfo = D('Order')->findObj($this->param['orderId']);
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
            }

            $orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
            unset($orderInfo['otherPayInfo']['account']);
            $orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);
            $data['orderCode'] = $this->param['orderId'];
            $data['otherPayInfo'] = $orderInfo['otherPayInfo'];
            if (D('Order')->saveObj($data)) {
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
                $this->success('取消余额支付成功！');
            }else{
                $this->error('取消余额支付失败');
            }
        }else{
            $goodOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId']);
            if (empty($goodOrderInfo)) {
                $goodOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $this->param['orderId']));
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
            }


            $goodOrderInfo['otherPayInfo'] = json_decode($goodOrderInfo['otherPayInfo'], true);
            unset($goodOrderInfo['otherPayInfo']['account']);
            $goodOrderInfo['otherPayInfo'] = json_encode($goodOrderInfo['otherPayInfo']);
            
            $data['otherPayInfo'] = $goodOrderInfo['otherPayInfo'];
            $map['id'] = $this->param['orderId'];

            if (D('Goods')->updateGoodsOrder($data, $map)) {
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
                $this->success('取消余额支付成功！');
            }else{
                $this->error('取消余额支付失败');
            }
        }
    }

    /**
     * 5.0.4使用票券
     */
    public function useVoucher()
    {
        if(empty($this->param['orderId']) || empty($this->param['voucherNum']) || empty($this->param['type'])){
            $this->error('参数错误！', '11001');
        }
        
        //验证票券状态
        $voucherInfo = D('Voucher')->checkVoucher($this->param['voucherNum']);
        if ($voucherInfo['status'] ==1) {
            $this->error($voucherInfo['content']);
        }
        //取得用户信息
        $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
        //取得订单信息

        if ($this->param['type'] == 'film') {
            $orderInfo = S('getBuyPaywayOrderInfo' . $this->param['orderId']);
            if (empty($orderInfo)) {
                $orderInfo = D('Order')->findObj($this->param['orderId']);
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
            }

            $seatInfo = json_decode($orderInfo['seatInfo'], true);
            $seatInfoCount = count($seatInfo);
            //取得排期信息
            $planInfo = S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo']);
            if (empty($planInfo)) {
                $planInfo = D('Plan')->getplan($orderInfo['featureAppNo']);
                S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo'], $planInfo, 900);
            }

            //取得当前场次的配置
            $arraySetingConfig = S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo']);
            if (empty($arraySetingConfig)) {
                $arraySetingConfig = D('Voucher')->isVoucher($planInfo);
                S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo'], $arraySetingConfig, 900);
            }
            $flag = false;


            foreach ($arraySetingConfig[$voucherInfo['data']['typeClass']] as $key => $value) {
                if ($key == $voucherInfo['data']['typeId']) {
                    $flag = true;
                    break;
                }
            }


            if (!$flag) {
                $this->error('该票券在当前场次不可使用！');
            }
        }elseif ($this->param['type'] == 'goods') {
            $orderInfo = S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId']);
            if (empty($goodOrderInfo)) {
                $orderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $this->param['orderId']));
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $orderInfo, 900);
            }
            $seatInfoCount = 1;
        }else{
            $this->error('参数错误！', '11001');
        }
        

        //判断是否在券包中
        $checkVoucherInfo = D('Member')->checkVoucher($this->param['voucherNum']);
        if(empty($checkVoucherInfo)){
            //开始加入券包
            $data['memberId'] = $userInfo['id'];
            $data['voucherName'] = $voucherInfo['data']['typeName'];
            $data['voucherNum'] = $voucherInfo['data']['voucherNumber'];
            $data['typeClass'] = $voucherInfo['data']['typeClass'];
            $data['voucherValue'] = $voucherInfo['data']['typeValue'];
            $data['createdDatetime'] = time();
            $data['validData'] = $voucherInfo['data']['endTime'];
            $data['typeId'] = $voucherInfo['data']['typeId'];
            if(!D('Member')->addMemberVoucher($data)){
                $this->error('加入券包失败，请重试！');
            }
            unset($data);
        }elseif ($checkVoucherInfo['memberId'] != $userInfo['id']) {
            $this->error('该票券已被绑定不可使用！');
        }

        $orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
        $typeClass = 0;
        $useNum = 0;
        $otherPayInfo = $orderInfo['otherPayInfo'];
        if (!empty($otherPayInfo['account']) || $otherPayInfo['integral']) {
            $this->error('请先取消积分或余额支付后，再使用票券支付！');
        }


        if (is_array($otherPayInfo)) {
            foreach ($otherPayInfo as $key => $value) {
                $typeClass = $key;
                $useNum += count(current($value)); 
                if ($key != $voucherInfo['data']['typeClass']) {
                    $this->error('一个订单中只能使用一种类型的券！');
                }
                if ($key == 1) {
                    $this->error('一个订单只能使用一张立减券！');
                }
            }
        }
        if ($seatInfoCount <= $useNum) {
            $this->error('该订单只能使用' . $seatInfoCount . '张券！');
        }

        if (in_array($voucherInfo['data']['voucherNumber'], $orderInfo['otherPayInfo'][$typeClass][$voucherInfo['data']['typeId']])) {
            $this->error('该订单中已使用这张票券，请勿重复使用');
        }

        $orderInfo['otherPayInfo'][$voucherInfo['data']['typeClass']][$voucherInfo['data']['typeId']][] = $voucherInfo['data']['voucherNumber'];
        $orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);

        if ($this->param['type'] == 'film') {
            $data['orderCode'] = $this->param['orderId'];
            $data['otherPayInfo'] = $orderInfo['otherPayInfo'];
            if (D('Order')->saveObj($data)) {
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
                $this->success('票券使用成功！');
            }else{
                $this->error('票券使用失败！');
            }
        }else{
            $data['otherPayInfo'] = $orderInfo['otherPayInfo'];
            $map['id'] = $this->param['orderId'];
            if (D('Goods')->updateGoodsOrder($data, $map)) {
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
                $this->success('票券使用成功！');
            }else{
                $this->error('票券使用失败！');
            }
        }
        
        
    }

    /**
     * 5.0.5取消票券
     */
    public function cancelVoucher()
    {
        if(empty($this->param['orderId']) || empty($this->param['voucherNum']) || empty($this->param['type'])){
            $this->error('参数错误！', '11001');
        }
        if ($this->param['type'] == 'film') {
            $orderInfo = S('getBuyPaywayOrderInfo' . $this->param['orderId']);
            if (empty($orderInfo)) {
                $orderInfo = D('Order')->findObj($this->param['orderId']);
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
            }
            $orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
            $typeClass = empty($orderInfo['otherPayInfo'][1]) ? 0 : 1; 
        }elseif ($this->param['type'] == 'goods') {
            $orderInfo = S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId']);
            if (empty($goodOrderInfo)) {
                $orderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $this->param['orderId']));
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $orderInfo, 900);
            }
            $orderInfo['otherPayInfo'] = json_decode($orderInfo['otherPayInfo'], true);
            $typeClass = 2;
        }else{
            $this->error('参数错误！', '11001');
        }
        

        $otherPayInfo = $orderInfo['otherPayInfo'][$typeClass];

        foreach ($otherPayInfo as $key => $value) {
            unset($orderInfo['otherPayInfo'][$typeClass][$key][array_search($this->param['voucherNum'],$value)]);
            if (empty($orderInfo['otherPayInfo'][$typeClass][$key])) {
                unset($orderInfo['otherPayInfo'][$typeClass][$key]);
            }

            if (empty($orderInfo['otherPayInfo'][$typeClass])) {
                unset($orderInfo['otherPayInfo'][$typeClass]);
            }
        }

        $orderInfo['otherPayInfo'] = json_encode($orderInfo['otherPayInfo']);



        if ($this->param['type'] == 'film') {
            $data['orderCode'] = $this->param['orderId'];
            $data['otherPayInfo'] = $orderInfo['otherPayInfo'];
            if (D('Order')->saveObj($data)) {
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
                $this->success('票券取消成功！');
            }else{
                $this->error('票券取消失败！');
            }
        }else{
            $data['otherPayInfo'] = $orderInfo['otherPayInfo'];
            $map['id'] = $this->param['orderId'];
            if (D('Goods')->updateGoodsOrder($data, $map)) {
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodOrderInfo, 900);
                $this->success('票券取消成功！');
            }else{
                $this->error('票券取消失败！');
            }
        }
    }

    /**
     * 2.12.1获取可以购票的支付方式
     */
    public function getBuyPayway()
    {

        if ($this->param['type'] == 'round' && empty($this->param['cinemaCode'])) {
            $this->error('参数错误！', '11001');
        }

        if (($this->param['type'] == 'film' || $this->param['type'] == 'goods') && empty($this->param['orderId'])) {
            $this->error('参数错误！', '11001');
        }

        $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
        if ($this->param['type'] == 'film') {//获得影票的支付渠道
        
            $orderInfo = S('getBuyPaywayOrderInfo'. $this->param['orderId']);
     
            if (empty($orderInfo)) {
                $orderInfo = D('Order')->findObj($this->param['orderId']);
                S('getBuyPaywayOrderInfo' . $this->param['orderId'], $orderInfo, 900);
            }
            $otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
            $seatInfo = json_decode($orderInfo['seatInfo'], true);
            $copyType = strtolower($orderInfo['copyType']);
            if (strstr($copyType, 'max')) {
                $copyType = 'max';
            }
            
            // print_r($orderInfo);
            if (!isset($orderInfo['status']) || $orderInfo['status'] != 0) {
                $this->error('订单状态失效');
            }
            
            $planInfo = S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo']);
            if (empty($planInfo)) {
                $planInfo = D('Plan')->getplan($orderInfo['featureAppNo']);
                S('getBuyPaywayPlanInfo' . $orderInfo['featureAppNo'], $planInfo, 900);
            }
            
            $arraySetingConfig = S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo']);
            if (empty($arraySetingConfig)) {
                $arraySetingConfig = D('Voucher')->isVoucher($planInfo);
                S('getBuyPaywaySetingConfig' . $orderInfo['featureAppNo'], $arraySetingConfig, 900);
            }

            //取得当前可支付方式
            $payWayMap['cinemaCode'] = $orderInfo['cinemaCode'];
            $payWayMap['tokenId'] = $this->param['tokenId'];
            $filmPayway = getNowPayWay($payWayMap, 'film');
            $isUnCard = false;
            // print_r($filmPayway);

            $price = $orderInfo['myPrice'] * count($seatInfo);
            $ticketPrice = $orderInfo['myPrice'];

            if (in_array('reduce', $filmPayway)) {//立减券
                unset($filmPayway[array_search("reduce",$filmPayway)]);
                if (!empty($arraySetingConfig[1])) {
                    $isCanUse = 0;
                }
                $voucerMap['memberId'] = $userInfo['id'];
                $voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
                $voucerMap['isUnlock'] = 0;
                $voucerMap['isUse'] = 0;
                $voucerMap['typeClass'] = 1;
                $memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
                $useNum = 0;
                $isCanUseNum = 0;
                foreach ($memberVoucherList as $key => $value) {
                    $memberVoucherList[$key]['isUse'] = 0;
                    $canUserTypeList = array_keys($arraySetingConfig[1]);

                    if (in_array($value['typeId'], $canUserTypeList)) {
                        $memberVoucherList[$key]['isCanUse'] = 1;
                        $isCanUseNum++;
                    }else{
                        $memberVoucherList[$key]['isCanUse'] = 0;
                    }
                    foreach ($otherPayInfo[1] as $vKey => $vValue) {
                        foreach ($vValue as $k => $v) {
                            if ($value['voucherNum'] == $v) {
                                $memberVoucherList[$key]['isUse'] = 1;
                                $price -= $arraySetingConfig[1][$vKey][$copyType];
                                $useNum++;
                            }
                        }
                        
                    }
                    
                }

                $rechargePayway['voucher'][0] = array(
                    'type' => 'voucher',
                    'voucherClass' => 1,
                    'name' => '立减券',
                    'content' => '您有' . $isCanUseNum . '张立减券可用',
                    'list' => $memberVoucherList,
                    'useNum' => intval(1 - $useNum),
                    'isShow' => 1
                );
                if (empty($memberVoucherList)) {
                    unset($rechargePayway['voucher'][0]['list']);
                }

            }else{
                $rechargePayway['voucher'][0] = array(
                    'type' => 'voucher',
                    'voucherClass' => 1,
                    'name' => '立减券',
                    'content' => '您有0张立减券可用',
                    'list' => '',
                    'useNum' => 0,
                    'isShow' => 0
                );
            }



            if (in_array('exchange', $filmPayway)) {//兑换券
                unset($filmPayway[array_search("exchange",$filmPayway)]);
                if (!empty($arraySetingConfig[0])) {
                    $isCanUse = 0;
                }
                $voucerMap['memberId'] = $userInfo['id'];
                $voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
                $voucerMap['isUnlock'] = 0;
                $voucerMap['isUse'] = 0;
                $voucerMap['typeClass'] = 0;
                $memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
                $useNum = 0;
                $isCanUseNum = 0;
                foreach ($memberVoucherList as $key => $value) {
                    $memberVoucherList[$key]['isUse'] = 0;
                    $canUserTypeList = array_keys($arraySetingConfig[0]);

                    if (in_array($value['typeId'], $canUserTypeList)) {
                        $memberVoucherList[$key]['isCanUse'] = 1;
                        $isCanUseNum++;
                    }else{
                        $memberVoucherList[$key]['isCanUse'] = 0;
                    }
                    foreach ($otherPayInfo[0] as $vKey => $vValue) {
                        foreach ($vValue as $k => $v) {
                            if ($value['voucherNum'] == $v) {
                                $memberVoucherList[$key]['isUse'] = 1;
                                $newPrice += $arraySetingConfig[0][$vKey][$copyType];
                                $useNum++;
                                $isUnCard = true;
                            }
                        }
                        
                    }
                    
                }
                $price = $price - $ticketPrice * $useNum + $newPrice;
                $rechargePayway['voucher'][1] = array(
                    'type' => 'voucher',
                    'voucherClass' => 0,
                    'name' => '兑换券',
                    'content' => '您有' . $isCanUseNum . '张兑换券可用',
                    'list' => $memberVoucherList,
                    'useNum' => intval(count($seatInfo) - $useNum),
                    'isShow' => 1
                );
                if (empty($memberVoucherList)) {
                    unset($rechargePayway['voucher'][1]['list']);
                }

            }else{
                $rechargePayway['voucher'][1] = array(
                    'type' => 'voucher',
                    'voucherClass' => 0,
                    'name' => '兑换券',
                    'content' => '您有0张兑换券可用',
                    'list' => '',
                    'useNum' => 0,
                    'isShow' => 0
                );
            }

            $price = $price >=0 ? $price : 0;
            
            $allIsShow = 1;
            if ($price == 0) {
                $allIsShow = 0;
            }

            if (in_array('integral', $filmPayway)) {//积分
                unset($filmPayway[array_search("integral",$filmPayway)]);
                if (!empty($otherPayInfo['integral'])) {
                    $isIntegral = 1;
                    $allIntegral = $price * $this->appInfo['cinemaGroupInfo']['proportion'];
                    if ($allIntegral >= $userInfo['integral']) {
                        $useIntegral = $userInfo['integral'];
                        $price -= round($userInfo['integral'] / $this->appInfo['cinemaGroupInfo']['proportion'],2);
                    }else{
                        $useIntegral = $allIntegral;
                        $price = 0;
                    }

                }
                $rechargePayway['integral'][] = array(
                    'type' => 'integral',
                    'name' => '积分',
                    'content' => '您有' . intval($userInfo['integral']) . '积分可用',
                    'integral' => intval($userInfo['integral'] - $useIntegral),
                    'proportion' => $this->appInfo['cinemaGroupInfo']['proportion'],
                    'isUse' => intval($isIntegral),
                    'isShow' => $allIsShow
                );

            }

            $price = $price >=0 ? $price : 0;

            if ($price == 0) {
                $allIsShow = 0;
            }

            if (in_array('account', $filmPayway)) {//余额
                if ($isUnCard) {
                    $this->payConfig['account']['isShow'] = 0;
                }else{
                    $this->payConfig['account']['isShow'] = $allIsShow;
                }
                unset($filmPayway[array_search("account",$filmPayway)]);
                
                if (!empty($otherPayInfo['account'])) {
                    $this->payConfig['account']['isUse'] = 1;
                    if ($price >= $userInfo['userMoney']) {
                        $price -= $userInfo['userMoney'];
                        $this->payConfig['account']['userMoney'] = 0;
                    }else{
                        $this->payConfig['account']['userMoney'] -= $price;
                        $price = 0;
                    }
                }else{
                    $this->payConfig['account']['isUse'] = 0;
                }
                $rechargePayway['account'][] = $this->payConfig['account'];
            }

            $price = $price >=0 ? $price : 0;

            if ($price == 0) {
                $allIsShow = 0;
            }


            $order['price'] = $price > 0 ? round($price, 1) : 0;
            $order['surplusTime'] = $orderInfo['lockTime'] - time();
            $rechargePayway['orderInfo'] = $order;


            if (empty($userInfo['cardNum']) || $isUnCard) {
                foreach ($filmPayway as $key => $value) {
                    if ($price == 0) {
                        $this->payConfig[$value]['isShow'] = 0;
                    }
                    $rechargePayway['online'][] = $this->payConfig[$value];
                }
            }

        	$this->success('', $rechargePayway);
        }elseif ($this->param['type'] == 'goods') {//获得卖品的支付渠道
            $goodsOrderInfo = S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId']);
            if (empty($goodsOrderInfo)) {
                $goodsOrderInfo=D('Goods')->getGoodsOrderInfo('', array('id' => $this->param['orderId']));
                S('getBuyPaywayGoodsOrderInfo' . $this->param['orderId'], $goodsOrderInfo, 900);
            }

            $otherPayInfo = json_decode($goodsOrderInfo['otherPayInfo'], true);

            //取得当前可支付方式
            $payWayMap['cinemaCode'] = $goodsOrderInfo['cinemaCode'];
            $payWayMap['tokenId'] = $this->param['tokenId'];
            $goodsPayway = getNowPayWay($payWayMap, 'goods');

            // print_r($goodsPayway);

            $isUnCard = false;


            $price = $goodsOrderInfo['price'];

            if (in_array('sale', $goodsPayway)) {//卖品券
            unset($goodsPayway[array_search("sale",$goodsPayway)]);
            $voucerMap['memberId'] = $userInfo['id'];
            $voucerMap['validData'] = array('EGT', strtotime(date('Y-m-d')));
            $voucerMap['isUnlock'] = 0;
            $voucerMap['isUse'] = 0;
            $voucerMap['typeClass'] = 2;
            $memberVoucherList = D('Member')->getMemberVoucherList('typeId, voucherName, voucherNum, typeClass, voucherValue, createdDatetime, validData', $voucerMap);
            $useNum = 0;
            foreach ($memberVoucherList as $key => $value) {
                $memberVoucherList[$key]['isUse'] = 0;
                foreach ($otherPayInfo[2] as $vKey => $vValue) {
                    foreach ($vValue as $k => $v) {
                        

                        $voucherInfo = D('Voucher')->checkVoucher($v);
                        if ($value['voucherNum'] == $v && $voucherInfo['status'] == 0) {
                            $memberVoucherList[$key]['isUse'] = 1;
                            $useNum++;
                            $price = $price - intval($voucherInfo['data']['typeValue']);
                        }
                    }
                    
                }
                
            }
            $rechargePayway['voucher'][0] = array(
                'type' => 'voucher',
                'voucherClass' => 2,
                'name' => '卖品券',
                'content' => '您有' . count($memberVoucherList) . '张卖品券可用',
                'list' => $memberVoucherList,
                'useNum' => intval(1 - $useNum),
                'isShow' => 1
            );

            if (empty($memberVoucherList)) {
                    unset($rechargePayway['voucher'][0]['list']);
                }

            }else{
                $rechargePayway['voucher'][0] = array(
                'type' => 'voucher',
                'voucherClass' => 2,
                'name' => '卖品券',
                'content' => '您有0张卖品券可用',
                'useNum' => 0,
                'isShow' => 0
            );
            }

            $price = $price >=0 ? $price : 0;
            $allIsShow = 1;
            if ($price == 0) {
                $allIsShow = 0;
            }

            if (in_array('integral', $goodsPayway)) {//积分
                unset($goodsPayway[array_search("integral",$goodsPayway)]);
                if (!empty($otherPayInfo['integral'])) {
                    $isIntegral = 1;
                    $allIntegral = $price * $this->appInfo['cinemaGroupInfo']['proportion'];
                    if ($allIntegral >= $userInfo['integral']) {
                        $useIntegral = $userInfo['integral'];
                        $price -= round($userInfo['integral'] / $this->appInfo['cinemaGroupInfo']['proportion'],2);
                    }else{
                        $useIntegral = $allIntegral;
                        $price = 0;
                    }

                }
                $rechargePayway['integral'][] = array(
                    'type' => 'integral',
                    'name' => '积分',
                    'content' => '您有' . intval($userInfo['integral']) . '积分可用',
                    'integral' => intval($userInfo['integral'] - $useIntegral),
                    'proportion' => $this->appInfo['cinemaGroupInfo']['proportion'],
                    'isUse' => intval($isIntegral),
                    'isShow' => $allIsShow
                );

            }

            $price = $price >=0 ? $price : 0;

            if ($price == 0) {
                $allIsShow = 0;
            }

            if (in_array('account', $goodsPayway) && empty($userInfo['cardNum'])) {//余额
                if ($isUnCard) {
                    $this->payConfig['account']['isShow'] = 0;
                }else{
                    $this->payConfig['account']['isShow'] = $allIsShow;
                }
                
                
                if (!empty($otherPayInfo['account'])) {
                    $this->payConfig['account']['isUse'] = 1;
                    if ($price >= $userInfo['userMoney']) {
                        $price -= $userInfo['userMoney'];
                        $this->payConfig['account']['userMoney'] = 0;
                    }else{
                        $this->payConfig['account']['userMoney'] -= $price;
                        $price = 0;
                    }
                }else{
                    $this->payConfig['account']['isUse'] = 0;
                }
                $rechargePayway['account'][] = $this->payConfig['account'];
            }

            unset($goodsPayway[array_search("account",$goodsPayway)]);
            $price = $price >=0 ? $price : 0;

            if ($price == 0) {
                $allIsShow = 0;
            }


            $order['price'] = $price > 0 ? round($price, 1) : 0;
            $rechargePayway['orderInfo'] = $order;


            foreach ($goodsPayway as $key => $value) {
                if ($price == 0) {
                    $this->payConfig[$value]['isShow'] = 0;
                }
                $rechargePayway['online'][] = $this->payConfig[$value];
            }

            $this->success('', $rechargePayway);


        }elseif ($this->param['type'] == 'round') {//获得团购的支付渠道
            $cinemaInfo = D('Cinema')->getCinemaInfoBycinemaCode('appPayWay', $this->param['cinemaCode']);
            if (!empty($cinemaInfo['appPayWay'])) {
                $rechargePaywayArray = explode(',', $cinemaInfo['appPayWay']);
            }

            foreach ($rechargePaywayArray as $key => $value) {
                if($value != 'account'){
                    $rechargePayway['online'][] = $this->payConfig[$value];
                }
            }
            $this->success('', $rechargePayway);
        }
    }
    
    /**
     * 购票支付
     */
    function pay(){
    	$user = $this->getBindUserInfo($this->appInfo['userInfo']);
    	D('order')->updateOrder($user['id']); //同步订单状态



		$orderid=$this->param['orderid'];
		$mypay=S('paymoney_'.$orderid);
		if(!empty($mypay)){
			// $this->error('正在支付，不要重复操作'.$orderid);
		}
		$order = D('Order')->findObj($orderid);
		if(!empty($order['status'] )){
			$this->error('该订单状态已改变'.$orderid );
		}else{
			$cinemaInfo = D('Cinema')->getCinemaInfo('cinemaName, payConfig', array('cinemaCode' => $order['cinemaCode']));
			

            $payType = $this->param['payType'];


            $buyAmount = D('Voucher')->getMyOrderPrice($orderid, $user, $this->appInfo['proportion']);
            if (isset($buyAmount['content'])) {
                $this->error($buyAmount['content']);
            }
            $orderInfo = S('getBuyPaywayOrderInfo'. $orderid);
            if (empty($orderInfo)) {
            	$orderInfo = D('Order')->findObj($orderid);
            	S('getBuyPaywayOrderInfo' . $orderid, $orderInfo, 900);
            }
            $otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
            if (!empty($otherPayInfo[0]) && !empty($user['cardNum'])){  //兑换券
            	if ($payType == 'account') {
            		// die('立减券不可用会员卡支付');
                    $this->error('兑换券不可用会员卡补差');
            	}
            }

            if($buyAmount == 0){
            	if(empty($mypay)){
            		S('paymoney_'.$orderid,1,60);
            		$sign = md5('orderid=' . $orderid . C('singKey'));
            		$url=C('PAY_URL').'order/mobile_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/mobile_app';
            		getCurlResult($url);
            	}
            	$this->success('跳转等待状态页面');
            }
			if ($payType == 'account') {	//余额支付
				if (!empty($user['cardNum'])) {	//会员卡支付
					if($user['basicBalance']+$user['donateBalance']<$buyAmount){
						$this->error('会员卡余额不足'.$orderid);
					}else{
						if(empty($mypay)){
                            $sign = md5('orderid=' . $orderid . C('singKey'));
							$url=C('PAY_URL').'order/account_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/account_app';
							
							// file_get_contents($url);
                            getCurlResult($url);
                            S('paymoney_'.$orderid,1,60);
						}
						$this->success('跳转等待状态页面');
					}
				}else{   //手机余额支付
					if($user['mmoney'] < $buyAmount){
						$this->error('手机余额不足'.$orderid );
					}else{
						if(empty($mypay)){
							S('paymoney_'.$orderid,1,60);
                            $sign = md5('orderid=' . $orderid . C('singKey'));
							$url=C('PAY_URL').'order/mobile_app/orderid/' . $orderid . '/sign/' . $sign.'/logpath/mobile_app';
							
                            // file_get_contents($url);
                            getCurlResult($url);
						}
						$this->success('跳转等待状态页面');
					}
				}
			}else{
				$otherPayInfo = json_decode($orderInfo['otherPayInfo'], true);
				if($otherPayInfo['account']){
					$buyAmount-=$user['mmoney'];
				}
				$payConfig = json_decode($cinemaInfo['payConfig'],true);
				if ($payType == 'alipay') {//支付宝支付
				
					$alipayConfig = $payConfig['alipayConfig'];
					// print_r($alipayConfig);
					$buyAmount=0.01;
					$data['alipay'] = array(
							'notifyUrl'      => C('PAY_URL').'order/alipay_app.html',
							'PartnerID'      => $alipayConfig['partnerId'],
							'SellerID'       => $alipayConfig['sellerEmail'],
							'Md5Key'         =>'',
							'PartnerPrivKey' => getKeyInfo($alipayConfig['privateKey'], 27),
							'AlipayPubKey'   => getKeyInfo($alipayConfig['publicKey'],26),
							'outTradeNo'     => $orderid,
							"subject"        => $cinemaInfo['cinemaName'] . ' 注册会员APP购票',
							"totalFee"       => $buyAmount,
							"body"           => $cinemaInfo['cinemaName'] . ' 注册会员APP购票' . $buyAmount . '元',
							"showUrl"        => 'http://wap.zrfilm.com',
					);
					$this->success('创建支付订单成功', $data);
					
				}elseif($payType == 'weixinpay'){   //微信支付
					$weixinpayConfig =$payConfig['weixinpayConfig'];
						
					// print_r($weixinpayConfig);
					$orderno =$orderid;
					$jsApi = new \Org\Wechat\Wxjspay($weixinpayConfig);
					//统一支付接口类
					$unifiedOrder = new \Org\Wechat\UnifiedOrder($weixinpayConfig);
					/*-----------------------------必填--------------------------*/
					$unifiedOrder->setParameter('body', $cinemaInfo['cinemaName'] . ' 注册会员APP购票');//商品描述岚樨微支付平台
					$unifiedOrder->setParameter('out_trade_no', $orderno);//商户订单号
					$unifiedOrder->setParameter('total_fee', $buyAmount * 100);//总金额（微信支付以人民币“分”为单位）
					/*-------------------------------------------------------*/
					$unifiedOrder->setParameter('notify_url', C('PAY_URL').'order/weixinpay_app.html');//通知地址
					$unifiedOrder->setParameter('trade_type', 'APP');//交易类型
					$unifiedOrder->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);//交易IP
						
					$weixinPayInfo = $unifiedOrder->getPayInfo();
						
						
					if ($weixinPayInfo['return_code'] == 'FAIL') {
						$this->error($weixinPayInfo['return_msg']);
					}
					$data['weixinpay'] = array(
							'appid'     => $weixinPayInfo['appid'],
							'partnerid' => $weixinPayInfo['mch_id'],
							'prepayid'  => $weixinPayInfo['prepay_id'],
							'package'   => 'Sign=WXPay',
							'noncestr'  => $weixinPayInfo['nonce_str'],
							'timestamp' => time()
					);
					$data['weixinpay']['sign'] = $unifiedOrder->getSign($data['weixinpay']);
						
					$this->success('创建支付订单成功', $data);
					
				}elseif($payType == 'unionpay'){   //银联
					$buyAmount=0.01;   //测试1分购票
					$unionpayConfig = $payConfig['unionpayConfig'];
					$orderTitle =  $cinemaInfo['cinemaName'] . ' 注册会员APP购票';
					$unionPayKey = $unionpayConfig['unionPayKey'];
					$unionPayId = $unionpayConfig['unionPayId'];
					$conf = array(
							"version"          => '1.0.0',
							"charset"          => 'UTF-8',
							"transType"        => '01',
							"merId"            => $unionPayId,
							"frontEndUrl"      =>  'http://wap.zrfilm.com/',
							'backEndUrl'       =>C('PAY_URL').'order/unionpay_app.html',
							"orderTime"        => date('YmdHis'),
							"orderTimeout"     => date('YmdHis', time() + 20 * 60),
							"orderNumber"      => date('YmdHis') . 'N' . $orderid,
							"orderAmount"      => round($buyAmount * 100, 2),
							"orderCurrency"    => 156,
							"orderDescription" => $orderTitle,
					);
					$conf['signature'] = unionSign($conf, $unionPayKey);
					$conf['signMethod'] = 'MD5';
					$result = getHttpResponsePOST('https://mgate.unionpay.com/gateway/merchant/trade', array(CURLOPT_HTTPHEADER => array('Expect:'), CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false), $conf) ;
					parse_str($result, $result);
						
					if ( !empty($result) && isset($result['respCode']) && isset($result['tn']) && isset($result['signature']) && $result['respCode'] == '00' && $result['signature'] == unionSign($result, $unionPayKey) ) {
						$data['unionpay']['tn'] = $result['tn'];
						$this->success('创建支付订单成功', $data);
					}
					$this->error('生成订单失败');
				}
			}
		}
    }
    /**
     *生成订单
     */
	public function seatLock(){
		$mobile=$this->param['mobile'];
		$featureAppNo=$this->param['featureAppNo'];
		if(empty($mobile) || empty($featureAppNo) || empty($this->param['datas'])){
    		$this->error('参数校验信息错误', 100101);
    	}
		$user=$this->getBindUserInfo($this->appInfo['userInfo']);
		$plan=D('Plan')->getplan($featureAppNo,$user['memberGroupId'], $this->appInfo['cinemaGroupInfo']['cinemaGroupId']);
		$cinema=D('cinema')->find($plan['cinemaCode']);
		if(!empty($user['cardNum'])){
			$tflag=D('cinema')->isInCinemas($this->appInfo,$user['businessCode']);
			if($tflag=='1'){
				$this->error('该会员卡无法购买此影院影票');
			}
		}
		$datas=explode(',',$this->param['datas']);
		$str='';
		$srctr='';
		
		$hall=M('cinemaHall')->where(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo']))->find();
		foreach ($datas as $k=>$value){
			$seatinfo=explode('.',$value);
			$start=explode('排',$seatinfo[1]);
			$end=explode('座',$start[1]);
			$srctr.=$start[0].':'.$end[0].'|';
			$str.=$seatinfo[1].',';
			$seats[$k]['seatNo']=$seatinfo[0];
		}
		foreach ($seats as $k=>$v){
			if($k==0){
				$fead=D('seat')->findSectionId(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo'],'seatCode'=>$v['seatNo']));
			}else{
				$ofead=D('seat')->findSectionId(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo'],'seatCode'=>$v['seatNo']));
				if($ofead['findSectionId']!=$fead['findSectionId']){
					$this->error('座区不一样');
				}
			}
		}
		$priceConfig=json_decode($hall['price'],true);
		if(empty($priceConfig[$fead['findSectionId']])){
			$price=0;
		}else{
			$price=$priceConfig[$fead['findSectionId']];
		}
		foreach ($seats as $k=>$v){
			$seats[$k]['ticketPrice']=$plan['memberPrice']+$price;
		}
		$str=substr($str,0,strlen($str)-1);
		$map=array(
				'cinemaCode'=>$plan['cinemaCode'],
				'featureAppNo'=>$featureAppNo,
				'seatInfos'=>$seats,
				'mobile'=>$mobile,
				'seatstr'=>substr($srctr, 0,-1),
				'ticketCount'=>count($datas),
				'link'=>$cinema['link'],
				'hallNo'=>$plan['hallNo'],
				'sectionId'=>$fead['findSectionId'],
				'filmNo'=>$plan['otherfilmNo'],
				'featureNo'=>$plan['featureNo'],
				'planDate'=>$plan['startTime'],
		);
		$lock=D('ZMMove')->checkSeatState($map);
		if($lock['ResultCode']=='0'){
			$orderid=D('Order')->addObj($lock,$plan,$user,$str,$mobile,json_encode($seats),$fead, $this->appInfo['cinemaGroupInfo']['cinemaGroupId']);
			if(!empty($orderid)){
				$this->success('锁座成功',array('orderid'=>$orderid));
			}else{
				$this->error('锁座成功，添加订单数据失败');
			}
		}else{
			$this->error($lock['Message']);
		}
	}
	
	/**
	 * 取消订单
	 *
	 * @param string $orderid
	 * @return int
	 */
	public function cancelOrder(){
		$orderid=$this->param['orderid'];
		$release=D('order')->cancelOrder($orderid);
		if($release['ResultCode']=='0'){
			$this->success('',$release);
		}else{
			$this->error($release['Message']);
		}
	}
    
	/**
	 * 上映提醒
	 */
	function remind(){
		$filmId=$this->param['filmId'];
		$appInfo=$this->appInfo;
		$uid=$appInfo['userInfo']['id'];
		$result=D('member')->saveRemind($uid,$filmId);
		if($result['status']=='0'){
			$appInfo['userInfo']['onfilm'] =$result['info'];
			S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $appInfo, 604800);
			$this->success('',$result);
		}else{
			$this->error($result['info']);
		}
	}
	
	/**
	 * 取消提醒
	 */
	function unremind(){
		$filmId=$this->param['filmId'];
		$appInfo=$this->appInfo;
		$uid=$appInfo['userInfo']['id'];
		$result=D('member')->delRemind($uid,$filmId);
		if($result['status']=='0'){
			$appInfo['userInfo']['onfilm'] =$result['info'];
			S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $appInfo, 604800);
			$this->success('',$result);
		}else{
			$this->error($result['info']);
		}
	}
	
	/**
	 * 添加评论
	 */
	function addView(){
		$filmId=$this->param['filmId']; //评论id
		if(!empty($filmId)){
			$film=D('film')->find($filmId);
			$data['filmId']=$filmId;
			$data['filmName']=$film['filmName'];
		}
		$pid=$this->param['pid']; //评论id
		if(!empty($pid)){
			$data['pid']=$pid;
		}
		$data['uid']=$this->appInfo['userInfo']['id'];
		$data['content']=$this->param['content'];
		$data['time']=time();
		$data['memberGroupId']=$this->appInfo['memberGroupId'];
		if(D('film')->addView($data)){
			$this->success('',$data);
		}else{
			$this->error('评论失败');
		}
	}
	
	/**
	 * 点赞
	 */
	function addClick(){
		$pid=$this->param['pid']; //评论id
		if(empty($pid) ){
			$this->error('参数校验信息错误', 100101);
		}
		$user=$this->appInfo['userInfo'];
		$views=explode(',', $user['onview']);   //已经点过赞的评论
		if(in_array($pid, $views)){
			$this->error('已经点过赞了');
		}
		$data['id']=$user['id'];
		if(empty($user['onview'])){
			$data['onview']=$pid;
		}else{
			$data['onview']=$user['onview'].','.$pid;
		}
		if(D('member')->save($data)){
			$view=D('filmView')->find($pid);
			D('filmView')->where(array('id'=>$pid))->setInc('clickNum',1);
			$appInfo=$this->appInfo;
			$appInfo['userInfo']['onview'] =$data['onview'];
			S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $appInfo, 604800);
			$this->success('',$data);
		}else{
			$this->error('点赞失败');
		}
	}
	
	/**
	 * 取消点赞
	 */
	function delClick(){
		$pid=$this->param['pid']; //评论id
		$user=$this->appInfo['userInfo'];
		$views=explode(',', $user['onview']);   //已经点过赞的评论
		if(!in_array($pid, $views)){
			$this->error('');
		}
		$data['id']=$user['id'];
		$onview='';
		foreach ($views as $v){
			if($v!=$pid){
				$onview.=$v.',';
			}
		}
		$data['onview']=substr($onview, 0,-1);
		if(D('member')->save($data)){
			$view=D('filmView')->find($pid);
			D('filmView')->where(array('id'=>$pid))->setInc('clickNum',-1);
			$appInfo=$this->appInfo;
			$appInfo['userInfo']['onview'] =$data['onview'];
			S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $appInfo, 604800);
			$this->success('',$data);
		}else{
			$this->error('取消点赞失败');
		}
	}
	
    /**
     *查看订单状态
     */
    public function getOrderStatus(){
    	if(empty($this->param['orderid']) ){
    		$this->error('参数校验信息错误', 100101);
    	}
    	$order=D('Order')->findObj($this->param['orderid']);
    	$order['qrcode']=substr(C('IMG_URL'), 0,-1). U('home/getQRcode', array('orderid'=>$order['orderCode'],'code' => $order['printNo'] ));
    	$this->success('',$order);
    }
    /**
     * 删除影票订单
     */
    function delOrderFilm(){
    	$orderid=$this->param['orderid']; //评论id
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
     * 删除充值订单
     */
    function delOrderRecharge(){
    	$orderid=$this->param['orderid']; //评论id
    	if(empty($orderid) ){
    		$this->error('参数校验信息错误', 100101);
    	}
    	$data['id']=$orderid;
    	$data['visible']=1;
    	if(D('orderRecharge')->save($data)){
    		$this->success('删除成功',$orderid);
    	}else{
    		$this->error('删除失败');
    	}
    }
    
    /**
     * 删除卖品订单
     */
    function delOrderGoods(){
    	$orderid=$this->param['orderid']; //评论id
    	if(empty($orderid) ){
    		$this->error('参数校验信息错误', 100101);
    	}
    	$order=D('orderGoods')->find($orderid);
    	if(!empty($order)&&$order['status']=='1'&&$order['exstatus']!='1'){
    		$this->error('该订单未兑换完成，删除失败');
    	}
    	$data['id']=$orderid;
    	$data['visible']=1;
    	if(D('orderGoods')->save($data)){
    		$this->success('删除成功',$orderid);
    	}else{
    		$this->error('删除失败');
    	}
    }
    /**
     * 删除周边订单
     */
    function delOrderRound(){
    	$orderid=$this->param['orderid']; //评论id
    	if(empty($orderid) ){
    		$this->error('参数校验信息错误', 100101);
    	}
    	$order=D('goods')->getRoundStatus($orderid);
    	if(!empty($order)&&$order['status']=='1'){
    		foreach ($order['codes'] as $v){
    			if($v['status']!='1'){
    				$this->error('该订单未兑换完成，删除失败');
    			}
    		}
    	}
    	$data['id']=$orderid;
    	$data['visible']=1;
    	if(D('orderRound')->save($data)){
    		$this->success('删除成功',$orderid);
    	}else{
    		$this->error('删除失败');
    	}
    }
    /**
     * 补发短信
     */
    function supply(){
    	$orderid=$this->param['orderid']; //评论id
    	$mobile=$this->param['mobile']; //评论id
    	if(empty($orderid)|| empty($mobile)){
    		$this->error('参数校验信息错误', 100101);
    	}
    	$order=D('orderFilm')->find($orderid);
    	if(empty($order)||$order['status']!=3){
    		$this->error('订单无效');
    	}elseif($order['supply']>0){
    		$this->error('订单已补发过');
    	}
    	$appInfo = S('ALLAPPINFO' . $this->appInfo['cinemaGroupId']);
    	if (empty($appInfo)) {
    		$appAccountMap['cinemaGroupId'] = $this->appInfo['cinemaGroupId'];
    		$appInfo = D('Service')->getAppAccount('smsType, smsAccount, smsPassword, smsSign, registrationProtocol, voucherRule, androidVersion, androidDown, androidIsMust, androidExplain, iOSVersion, iOSDown, iOSIsMust, iOSExplain, proportion', $appAccountMap);
    		S('ALLAPPINFO' . $this->appInfo['cinemaGroupId'], $appInfo, 72000);
    	}
    	
    	$smsConfig['smsType'] = $appInfo['smsType'];
    	$smsConfig['smsAccount'] = $appInfo['smsAccount'];
    	$smsConfig['smsPassword'] = $appInfo['smsPassword'];
    	$smsConfig['smsSign'] = $appInfo['smsSign'];
    	if(smsajax($smsConfig,$order,$mobile)){
    		$this->success('补发成功',$mobile);
    	}else{
    		$this->error('补发失败');
    	}
    }
    /**
     * 2.4.0获取消息中心列表
     */
    public function getUserMessage()
    {

        $userInfo=$this->getBindUserInfo($this->appInfo['userInfo']);
        $nowPage = $this->param['page'] ? $this->param['page'] : 1;
        $pageNum = 5;
        $messageMap['msgType'] = $userInfo['deviceType'];
        $messageMap['delUserId'] = array('notlike', '%"' . $userInfo['id'] . '"%');
        $messageList = D('Member')->getMemberMessageList('messageId, title, content, param, addtime', $messageMap, ($nowPage - 1) * $pageNum . ',' . $pageNum, 'addTime desc');
        foreach ($messageList as $key => $value) {
            $messageList[$key]['param'] = json_decode($value['param']);
        }
        $this->success('', $messageList);

    }

    /**
     * 2.4.1删除消息中心
     */
    public function delUserMessage()
    {
        if(empty($this->param['messageId'])){
            $this->error('参数校验信息错误', 100101);
        }
        $userInfo=$this->getBindUserInfo($this->appInfo['userInfo']);
        $messageMap['messageId'] = $this->param['messageId'];
        $messageInfo = D('Member')->getMemberMessageInfo('messageId, delUserId', $messageMap);
        $messageInfo['delUserId'] = json_decode($messageInfo['delUserId']);

        if (!in_array($userInfo['id'], $messageInfo['delUserId'])) {
            
            $messageInfo['delUserId'][] = $userInfo['id'];
            $messageData['delUserId'] = json_encode($messageInfo['delUserId']);

            if(D('Member')->setMemberMessageInfo($messageData, $messageMap)){
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('删除失败');
        }
    }


}