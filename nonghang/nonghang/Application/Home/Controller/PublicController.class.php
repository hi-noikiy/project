<?php
/**
 * 公用控制器
 * 
 * @author 王涛
 * @package home
 */
namespace Home\Controller;
use Think\Controller;
class PublicController extends InitController {
	
	/**
	 * 注册协议
	 */
	public function getHtmlInfo(){
		$type=I('type');
		if(empty($type)){
			$this->error('参数错误！', '11001');
		}
		if ($type == 'registration') {
			$this->success('', $this->appInfo['registrationProtocol']);
		}elseif ($type == 'voucher') {
			$this->success('', $this->appInfo['voucherRule']);
		}
	}
    /**
     * 前台用户登录
     * 
     * @param string $username
     * @param string $password
     * @param string $verify
     */
    public function login(){ 
    	$reurl=I('reurl');
    	if($reurl){
    		session('url',$_SERVER['HTTP_REFERER']);
    	}
    	if(IS_POST){
    		$cinemaCode=I('cinemaCode');
    		$loginNum=I('userAccount');
    		$passWord=I('userPasswd');
    		if(empty($loginNum)|| empty($passWord)){
    			$this->error('参数错误！', '11001');
    		}
    		if(!empty($cinemaCode)){   //会员卡登录
    			$cinema=D('cinema')->find($cinemaCode);
    			$hasUser=D('member')->getBindInfo(array('mobile'=>$loginNum,'cinemaCode'=>$cinemaCode,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
    			if(!empty($hasUser)){
    				$loginNum=$hasUser['cardId'];
    			}
    			$loginarr=array('cinemaCode'=>$cinemaCode,'loginNum'=>$loginNum,'password'=>$passWord,'link'=>$cinema['link'],'cinemaName'=>$cinema['cinemaName']);
    			$loginResult = D('ZMUser')->verifyMemberLogin($loginarr);
    			// print_r($loginResult);
    			if($loginResult['ResultCode'] == 0){//登录成功
    				 
    				$result=D('member')->loginMember($loginResult,$cinemaCode,$passWord);
    				if($result['status']=='0'){
    					$this->success('登录成功', array('url' => session('url')));
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
    			$user = D('member')->getUser(array('mobile'=>$loginNum,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
                if(empty($user)){
    				$this->error('该手机号未注册');
    			}elseif($user['mpword']!=encty($passWord)){
    				$this->error('手机密码错误');
    			}else{
    				session('ftuser',$user);
    				cookie('ftuser',$user,3600);
    				$this->success('登录成功', array('url' => session('url')));
    			}
    		}
    	}else {
            // echo '11';
            // print_r($this->weiXinInfo);
            $getCinemaListMap['cinemaCode'] =  array('IN', $this->weiXinInfo['cinemaList']);
        	$cinemaList = D('cinema')->getCinemaList('',$getCinemaListMap);
        	$cinemaCode=$this->loginCinemaCode;
        	if(!empty($cinemaCode)){
        		$cinema= M('Cinema')->where($map)->find();
        		$i=1;
        		foreach ($cinemaList as $value){
        			if($value['cinemaCode']==$cinema['cinemaCode']){
        				$cinemaList[0]=$value;
        			}else{
        				$cinemaList[$i]=$value;
        				$i++;
        			}
        		}
        		ksort($cinemaList);
        	}
            $this->assign('data',$cinemaList);
            $this->assign('logo',$this->weiXinInfo['image']);
        	$this->display();
        }
    }
    /**
     *  退出登录
     */
    public function logout(){
    	cookie('ftuser',null);
    	session('ftuser',null);
    	session('url',null);
    	session('cinemaCode',null);
    	$pflag=session('pflag');
    	if(!empty($pflag)){
    		$this->redirect('Index/cinema/pflag/'.$pflag);
    	}else{
    		$this->redirect('Index/index');
    	}
    }
    function Post($curlPost,$url){
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_HEADER, false);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_NOBODY, true);
    	curl_setopt($curl, CURLOPT_POST, true);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    	$return_str = curl_exec($curl);
    	curl_close($curl);
    	return $return_str;
    }
    /**
     * xml转字符串
     * 
     * @param unknown $xml
     * @return unknown
     */
    function xml_to_array($xml){
    	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
    	if(preg_match_all($reg, $xml, $matches)){
    		$count = count($matches[0]);
    		for($i = 0; $i < $count; $i++){
    			$subxml= $matches[2][$i];
    			$key = $matches[1][$i];
    			if(preg_match( $reg, $subxml )){
    				$arr[$key] = xml_to_array( $subxml );
    			}else{
    				$arr[$key] = $subxml;
    			}
    		}
    	}
    	return $arr;
    }
    
    /**
     * 获取短信验证码
     * @param
     * @return result
     *        {
     *            "status": "0",
     *            "data": md5(tokenId)
     *        }xmmgf123
     * @author
     */
    public function getValidateCode() {
    	$userMobile=I('userMobile');
    	$codeType=I('codeType');
    	if(empty($userMobile) || empty($codeType)){
    		$this->error('参数错误！', '11001');
    	}
    
    	$codeInfo = S('tokenId_getMobileVerification' . $userMobile . $codeType);
    	if(($codeInfo['time'] + 60) >= time()){
    		$this->error('您发送的太频繁，请稍后再试');
    	}
    
        $memberMap['mobile'] = $userMobile;
        $memberMap['cinemaGroupId'] = $this->weiXinInfo['cinemaGroupId'];
    	if(!checkMobile($memberMap['mobile'])){
    		$this->error('手机格式不正确');
    	}
    	$memberInfo = D('Member')->getUser( $memberMap);
    
    	$code = rand(100000, 999999);
    
    	if($codeType == 'find'){
    		if(empty($memberInfo)){
    			$this->error('手机号不存在！');
    		}
    
    		$content = '您正在申请找回密码，校验码是：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
    
    	}elseif ($codeType == 'register') {
    		if(!empty($memberInfo)){
    			$this->error('手机号已被注册！');
    		}
    		$content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
    	}elseif ($codeType == 'bind') {
    
    		$hasUser=D('Member')->getBindInfo(array('mobile'=>$userMobile,'appid'=>$this->appInfo['appId']));
    
    		if (!empty($hasUser)) {
    			$this->error('手机号已被绑定！');
    		}
    		$content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
    	}elseif ($codeType == 'unbind') {
    
    		$content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
    	}else{
    		wlog('参数校验信息错误！' . $userMobile, 'testLog');
    		$this->error('参数校验信息错误！');
    	}
    	$smsConfig['smsType'] = $this->weiXinInfo['smsType'];
    	$smsConfig['smsAccount'] = $this->weiXinInfo['smsAccount'];
    	$smsConfig['smsPassword'] = $this->weiXinInfo['smsPassword'];
    	$smsConfig['smsSign'] = $this->weiXinInfo['smsSign'];
    	$sms = new \Think\SmsModel($smsConfig);
    	$smsResult = $sms->sendSms($userMobile, $content);
    	if($smsResult['code'] != 1){
    		wlog(json_encode($smsResult),'getrechargeOrderStatus');
    		$this->error($smsResult['text']);
    	}else{
    		$codeInfo['code'] = $code;
    		$codeInfo['time'] = time();
    		$successData['deadline'] = 120;
    		S('tokenId_getMobileVerification' . $userMobile . $codeType, $codeInfo, 120);
    		wlog('验证码发送成功！' .$userMobile, 'testLog');
    		$this->success('发送成功，2分钟内有效！', $successData);
    	}
    
    }
 	/**
     * 注册
     */
    function registered(){
    	$mobile = I('userMobile');
    	$randomCode = I('validateCode');
    	$passWord = I('userPasswd');
    	if(empty($randomCode)||empty($mobile)||empty($passWord)){
    		$this->error('参数错误！', '11001');
    	}
    	if(!checkMobile($mobile)){
    		$this->error('手机格式不正确!');
    	}
    	if(strlen($passWord)<6||strlen($passWord)>20){
    		$this->error('密码长度不小于6位并且不大于20位');
    	}
    	$send = S('tokenId_getMobileVerification' . $mobile . 'register');
    	if (empty($send)) {
    		$this->error('验证码已过期！');
    	}
    	if(!empty($send)){
    		if($randomCode != $send['code'] || empty($randomCode)){
    			$this->error('验证码填写错误');
    		}else{
    			$arr['mobile']=$mobile;
    			$arr['mpword']=encty($passWord);
    			$arr['bindTime']=time();
    			$arr['levelCode']=$this->weiXinInfo['defaultLevel'];
    			$arr['memberGroupId']=$this->weiXinInfo['defaultLevel'];
    			$arr['userName'] = $mobile;
    			$arr['otherName']=$mobile;
                $arr['cinemaGroupId'] = $this->weiXinInfo['cinemaGroupId'];
    			if(D('Member')->add($arr)){
    				$this->success('注册成功', $arr);
    				S('tokenId_getMobileVerification' . $mobile . 'register',null);
    			}else{
    				$this->error('注册提交失败');
    			}
    		}
    	}
    }
    /**
     * 注册页面
     */
    function register(){
    	$this->display();
    } 
    /**
     *密码找回页面
     */
    function find(){
    	$this->display();
    }
    /**
     * 密码找回
     */
    function findPasswd(){
    	$mobile = I('userMobile');
    	$randomCode = I('validateCode');
    	$passWord =I('newUserPasswd');
    	if(empty($randomCode)||empty($mobile)||empty($passWord)){
    		$this->error('参数错误！', '11001');
    	}
    	if(!checkMobile($mobile)){
    		$this->error('手机格式不正确!');
    	}
    	if(strlen($passWord)<6||strlen($passWord)>20){
    		$this->error('密码长度不小于6位并且不大于20位');
    	}
    	$send=S('tokenId_getMobileVerification' . $mobile . 'find');
    	if(!empty($send)){
    		if($randomCode!=$send['code'] || empty($randomCode)){
    			$this->error('验证码填写错误');
    		}else{
    			$arr['mpword']=encty($passWord);
    			$result=D('Member')->saveUser(array('mobile'=>$mobile,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']),$arr);
    			if($result!==false){
    				S('tokenId_getMobileVerification' . $mobile . 'find',null);
    				$this->success('密码修改成功');
    			}else{
    				$this->error('修改提交失败');
    			}
    		}
    	}else{
            $this->error('验证码已过期！');
        }
    }
}