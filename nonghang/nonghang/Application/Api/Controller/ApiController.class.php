<?php
// +----------------------------------------------------------------------
// | 系统基础控制器
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Api\Controller;
use Think\Controller;
class ApiController extends Controller {

	protected $param = '';
	protected $startTime = '';
    protected $cacheName = '';
    protected $userInfo = '';
    protected $payConfig = '';
    protected $cinemaGroupInfo = '';
    protected $appInfo = '';
    protected $pageNum = 10;
    /**
     * 系统基础控制器初始化
     */
    protected function _initialize(){
    	$this->startTime = microtime(true);
        $this->param = I('request.');

        $endParam['param'] = $this->param;
        $endParam['startTime'] = $this->startTime;
        $endParam['actionName'] = strtolower(MODULE_NAME . '_' . CONTROLLER_NAME . '_' . ACTION_NAME);
        \Think\Hook::listen('app_end', $endParam); 
        
        
        if(!in_array(strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME) , array('api/service/gettoken','api/service/showcache','api/home/getqrcode','api/home/rounddetail'))){
            if(!checkToken($this->param)){
                $this->error('', '10001');//令牌失效！
            }
        }
        session('tokenId', $this->param['tokenId']);
        
        if (!empty($this->param['tokenId'])) {
            $this->appInfo = S('APPINFOUserInfotokenId_' . $this->param['tokenId']);

            $this->cinemaGroupInfo = $this->appInfo['cinemaGroupInfo'];
        }
        if (in_array(strtolower(MODULE_NAME . '/' . CONTROLLER_NAME), array('api/user','api/sale')) && !in_array(strtolower(ACTION_NAME), array('registered', 'login', 'findpasswd'))) {
            
            $userInfo = $this->getBindUserInfo($this->appInfo['userInfo']);
            // print_r($userInfo);
            if (empty($userInfo)) {
                $this->error('', '20001');//用户未登录
            }else{
                $this->payConfig['account'] = array(
                    'type' => 'account',
                    'icon' => 'http://testapi.zmaxfilm.com:8181/Public/static/app/icon/account.png',
                    'name' => '余额支付',
                    'instruction' => '您前帐户余额为' . floatval($userInfo['userMoney']) . '元',
                    'userMoney' => floatval($userInfo['userMoney']),
                    'isShow' => 1
                );

                $this->payConfig['alipay'] = array(
                    'type' => 'alipay',
                    'icon' => 'http://testapi.zmaxfilm.com:8181/Public/static/app/icon/alipay.png',
                    'name' => '支付宝支付',
                    'instruction' => '推荐安装支付宝客户端使用',
                    'isShow' => 1
                );

                $this->payConfig['weixinpay'] = array(
                    'type' => 'weixinpay',
                    'icon' => 'http://testapi.zmaxfilm.com:8181/Public/static/app/icon/weixinpay.png',
                    'name' => '微信支付',
                    'instruction' => '推荐安装支付宝客户端使用',
                    'isShow' => 1
                );

                $this->payConfig['unionpay'] = array(
                    'type' => 'unionpay',
                    'icon' => 'http://testapi.zmaxfilm.com:8181/Public/static/app/icon/unionpay.png',
                    'name' => '银联支付',
                    'instruction' => '推荐安装支付宝客户端使用',
                    'isShow' => 1
                );
            }
        }

        

        $this->cacheName = strtolower(MODULE_NAME.'_'.CONTROLLER_NAME.'_'.ACTION_NAME);
        $paramValue = getCacheName($this->param);

        $this->cacheName .= str_replace($this->param['tokenId'], '', $paramValue);


        $cacheValue = S($this->cacheName);
        if($cacheValue){
            $apiAllCacheName = S(C('CACHE_NAME_LIST'));
            $cacheInfo = $apiAllCacheName[$this->cacheName];
            $cacheValue['timeOut'] =$cacheInfo['expiration'] - (time() - strtotime($cacheInfo['createTime']));
            die(str_replace(':null', ':""', json_encode($cacheValue)));
        }


        $tempCheckStr = str_replace($this->param['sign'], '', $paramValue);

        $newTempCheckStr = md5(sha1(mb_strlen($tempCheckStr,'utf-8')));

        $checkStr = substr($newTempCheckStr, 0, 8) . substr($newTempCheckStr, -8);

        wlog('客户端：' . $this->param['sign'] . '服务端:' . $checkStr . '完整：' . $newTempCheckStr . '所有值：'. $tempCheckStr . '长度：' . mb_strlen($tempCheckStr,'utf-8'), 'testLog');
        if ($checkStr != $this->param['sign'] && empty($this->param['jsoncallback'])) {
            // $this->error('签名错误，参数验证错误！' . $newTempCheckStr  . '长度：' . mb_strlen($tempCheckStr,'utf-8'), '11002');
        }
        
    }

    // 10001:令牌失效
    // 11001:参数错误
    /**
    * 成功输出信息
    * @param  
    * @return null
    * @author 
    */
    public function success($text='', $successInfo='', $timeOut = 0, $cacheName = '')
    {
        $result = array(
            'status' => 0,
            'data' => $successInfo, 
            'text' => $text,
            'timeOut' => $timeOut,
        );
        $cacheName = $cacheName ? $cacheName : $this->cacheName;
        if($timeOut != 0){
            S('APPINFO' . $cacheName, $result, $timeOut);
        }
        // print_r($this->param);
        if (!empty($this->param['jsoncallback'])) {
            $strBing = $this->param['jsoncallback'] . '(';
            $strEnd = ')';
        }

        die($strBing . str_replace(':null', ':""', json_encode($result)) . $strEnd);
    }   

    /**
    * 成功输出信息
    * @param  
    * @return null
    * @author 
    */
    public function error($errorInfo, $status = 1, $data = '')
    {
        $result = array(
            'status' => $status,
            'data' => $data, 
            'text' => $errorInfo,
            'timeOut' => 0,
        );


        if (!empty($this->param['jsoncallback'])) {
            $strBing = $this->param['jsoncallback'] . '(';
            $strEnd = ')';
        }
        die($strBing . str_replace(':null', ':""', json_encode($result)) . $strEnd);
    }
    
    /**
     * 设置app展示用户信息
     * @param unknown $mobile
     */
    function setAppUserInfo($user){
        $cinemaGroupId = $this->appInfo['cinemaGroupInfo']['id'];
    	$appUserInfo['integral'] = intval($user['integral']);
    	if(!empty($user['cardNum'])){
    		$appUserInfo['userMoney'] = $user['basicBalance']+$user['donateBalance'];
    		$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$cinemaGroupId));
            $appUserInfo['bindmobile']=$bind['mobile'];
            $appUserInfo['bindcardId']=$user['cardNum'];
            $appUserInfo['memberGroupId']=$user['memberGroupId'];
    	}else{
    		$appUserInfo['userMoney'] = $user['mmoney'];
    		$appUserInfo['memberGroupId']=$user['memberGroupId'];
    		$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'cinemaGroupId'=>$cinemaGroupId));
    		$appUserInfo['bindcardId']=$bind['cardId'];
    		$appUserInfo['bindmobile']=$user['mobile'];
            if (!empty($bind)) {
                $bindUserInfo = $this->getBindUserInfo($user);
                $appUserInfo['memberGroupId']=$bindUserInfo['memberGroupId'];
                $appUserInfo['integral'] = intval($bindUserInfo['integral']);
                $appUserInfo['userMoney'] = $bindUserInfo['basicBalance']+$bindUserInfo['donateBalance'];
            }
    	}


        if (empty($user['headImage'])) {
            $appUserInfo['userIcon'] =C('IMG_URL').'Uploads/'. 'userIcons/default/defaultIcon.png';
        }else{
        	$appUserInfo['userIcon'] = C('IMG_URL').'Uploads/'.$user['headImage'];
        }
        $appUserInfo['userMoney']=round( $appUserInfo['userMoney'],2);
        $userGroupList = S('userGroupList' . $this->appInfo['cinemaGroupId']);
        if (empty($userGroupList)) {
            $userGroupList = M('CinemaMemberGroup')->where(array('cinemaGroupId'=>$this->appInfo['cinemaGroupId']))->select();
            foreach ($userGroupList as $key => $value) {
                $newUserGroupList[$value['groupId']] = $value;
            }
            unset($userGroupList);
            $newUserGroupList['99101']['groupName'] = '注册会员';
            S('userGroupList' . $this->appInfo['cinemaGroupId'], $newUserGroupList, 7200);
            $userGroupList = $newUserGroupList;
        }
        // print_r($userGroupList);
        $appUserInfo['servicePhone'] = '110-110-110';
        $appUserInfo['updateTxt'] = '当前版本';
        $appUserInfo['userNickname'] = $user['otherName'];
    	$appUserInfo['userSex'] = $user['sex'];
    	$appUserInfo['userBirthday'] = $user['birthday'];
        $appUserInfo['email'] = $user['email'];
        $appUserInfo['memberGroupName'] = $userGroupList[$appUserInfo['memberGroupId']]['groupName'];
    
    	// wlog(json_encode($appUserInfo) . json_encode($_SERVER), 'testLog');
    	return $appUserInfo;
    }
    
    /**
     * 设置手机对应用户信息
     * @param unknown $mobile
     **/
    function getBindUserInfo($user){
    	if(!empty($user)){
    		if(empty($user['cardNum'])){
    			$bind=D('member')->getBindInfo(array('mobile'=>$user['mobile'],'cinemaGroupId'=>$this->appInfo['cinemaGroupInfo']['id']));
    			if(!empty($bind)){
    				$cardUser=D('member')->getUser(array('cardNum'=>$bind['cardId'],'businessCode'=>$bind['cinemaCode'],'cinemaGroupId'=>$this->appInfo['cinemaGroupInfo']['id']));
    				if(!empty($cardUser)){
    					$user=$cardUser;
    				}
    			}
    		}
    		$user=D('member')->find($user['id']);  //同步价格
    		if(empty($user['cardNum'])){
    			$user['userMoney']=$user['mmoney'];
    		}else{
    			$user['userMoney']=$user['basicBalance']+$user['donateBalance'];
    		}
    	}
    	return $user;
    }
    /**
     * 设置会员卡对应用户信息
     * @param unknown $mobile
     */
    function getBindCardInfo($user){
    	if(!empty($user)){
    		if(!empty($user['cardNum'])){
    			$bind=D('member')->getBindInfo(array('cardId'=>$user['cardNum'],'cinemaCode'=>$user['businessCode'],'cinemaGroupId'=>$this->appInfo['cinemaGroupInfo']['id']));
    			if(!empty($bind)){
    				$mobileUser=D('member')->getUser(array('mobile'=>$bind['mobile'],'cinemaGroupId'=>$this->appInfo['cinemaGroupInfo']['id']));
    				if(!empty($mobileUser)){
    					$user=$mobileUser;
    				}
    			}
    		}
    	}
    	return $user;
    }
    
}