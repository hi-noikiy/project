<?php
namespace Api\Controller;
// use Think\Controller\HproseController;
use Think\Controller;

/*
extends HproseController
*/
class ServiceController extends ApiController {
    public function index()
    {
        die('禁止访问');
    }

    /**
    * 获取会员ID
    * @param  
    * @return result
    *        {
    *            "status": "0",
    *            "data": md5(tokenId)
    *        }xmmgf123
    * @author 
    */
    public function getToken()
    {   
        if(empty($this->param['appAccount']) || empty($this->param['appPasswd']) || empty($this->param['deviceType']) || empty($this->param['appVersion']) || empty($this->param['deviceNumber'])){
            $this->error('参数错误！', '11001');
        }

        if(!empty($this->param['tokenId'])){
            $appAccountInfo = S('APPINFOUserInfotokenId_' . $this->param['tokenId']);
            S('APPINFOUserInfotokenId_' . $this->param['tokenId'], NULL);
        }

        if (empty($appAccountInfo)) {
            $appAccountMap['appAccount'] = $this->param['appAccount'];
            $appAccountMap['appPasswd'] = $this->param['appPasswd'];
            $cinemaGroupInfo = D('Service')->getAppAccount('id as cinemaGroupId, groupName, cinemaList, defaultLevel, smsType, smsAccount, smsPassword, smsSign, registrationProtocol, voucherRule, proportion, getProportion, channelConfig, payWay, isDebug, maxName', $appAccountMap);

            // print_r($cinemaGroupInfo);
            if (empty($cinemaGroupInfo)) {
               $this->error('APP帐号、密码不正确！');
            }

            $appAccountInfo['appVersion'] = $this->param['appVersion'];
            $appAccountInfo['deviceNumber'] = $this->param['deviceNumber'];
            $appAccountInfo['cinemaGroupId'] = $cinemaGroupInfo['cinemaGroupId'];
            $appAccountInfo['proportion'] = $appInfo['proportion'];
            $appAccountInfo['deviceType'] = $this->param['deviceType'];
            $appAccountInfo['cinemaGroupInfo'] = $cinemaGroupInfo;
            wlog('获取token详细信息' . json_encode($appAccountInfo), 'tokenId');
        }       

    	$tokenId = md5(time() + microtime() . rand(100000000,999999999) . $this->cacheName);
        $resultData['tokenId'] = $tokenId;
        $resultData['defaultLevel'] = $cinemaGroupInfo['defaultLevel'];
        S('APPINFOUserInfotokenId_' . $tokenId, $appAccountInfo, 604800);
        session(array('id'=>$tokenId,'expire'=>7200));
        session('tokenId', $tokenId);
        $this->success('', $resultData, 7200, 'tokenId_' . $tokenId);
    }


    /**
     * 提交信鸽tokenId
     */
    public function setXgTonkenId()
    {

        if(empty($this->param['xgTokenId'])){
            $this->error('参数错误！', '11001');
        }


        wlog('收到用户信鸽tokenId' . $this->param['xgTokenId'], 'xgTokenId');
        $appInfo = S('APPINFOUserInfotokenId_' . $this->param['tokenId']);
        $appInfo['xgTokenId'] = $this->param['xgTokenId'];
        
        if (!empty($appInfo['userInfo']) && $this->param['xgTokenId']) {
            $userarr['xgTokenId'] = $this->param['xgTokenId'];
            $userarr['id']=$appInfo['userInfo']['id'];
            M('member')->save($userarr);
            wlog('更新用户信鸽tokenId' . json_encode($userarr), 'xgTokenId');
        }

        S('APPINFOUserInfotokenId_' . $this->param['tokenId'], $appInfo, 604800);
        $this->success('', array('null'), 20);

    }
    /**
     * 检查更新
     */
    public function checkVersion()
    {
        $appInfo = S('ALLAPPINFO' . $this->appInfo['cinemaGroupId']);
        if (!empty($appInfo)) {
            $appAccountMap['cinemaGroupId'] = $this->appInfo['cinemaGroupId'];
            $appInfo = D('Service')->getAppAccount('smsType, smsAccount, smsPassword, smsSign, registrationProtocol, voucherRule, androidVersion, androidDown, androidIsMust, androidExplain, iOSVersion, iOSDown, iOSIsMust, iOSExplain, proportion', $appAccountMap);
            S('ALLAPPINFO' . $this->appInfo['cinemaGroupId'], $appInfo, 72000);
        }
        

        $appAccountInfo['androidVersion'] = $appInfo['androidVersion'];
        $appAccountInfo['androidDown'] = $appInfo['androidDown'];
        $appAccountInfo['androidIsMust'] = $appInfo['androidIsMust'];
        $appAccountInfo['androidExplain'] = '&lt;html&gt;&lt;body&gt;' . $appInfo['androidExplain'] . '&lt;/body&gt;&lt;/html&gt;';
        $appAccountInfo['iOSVersion'] = $appInfo['iOSVersion'];
        $appAccountInfo['iOSDown'] = $appInfo['iOSDown'];
        $appAccountInfo['iOSIsMust'] = $appInfo['iOSIsMust'];
        $appAccountInfo['iOSExplain'] = '&lt;html&gt;&lt;body&gt;' . $appInfo['iOSExplain'] . '&lt;/body&gt;&lt;/html&gt;';
        $appAccountInfo['proportion'] = $appInfo['proportion'];


        $deviceType = $appAccountInfo['deviceType'];


        $userVersion = explode('.', $appAccountInfo['appVersion']);
        $nowVersion = explode('.', $appAccountInfo[$deviceType . 'Version']);

        $nowVersionInfo['content'] = $appAccountInfo[$deviceType . 'Explain'];
        $nowVersionInfo['appUrl'] = $appAccountInfo[$deviceType . 'Down'];
        $nowVersionInfo['isMandates'] = $appAccountInfo[$deviceType . 'IsMust'];

        foreach ($nowVersion as $key => $value) {
            // wlog('比较版本号 ' . $nowVersion[$key] . '>' .  $userVersion[$key] . ';执行时间：' . (microtime(true) - $this->param['stime']) .'秒', 'checkVersion'); 
            if($nowVersion[$key] > $userVersion[$key]){
                $this->success('', $nowVersionInfo, 3600);
            }
        }
        $this->error('');
    }

    /**
     * 注册协议
     */
    public function getHtmlInfo()
    {
        if(empty($this->param['type'])){
            $this->error('参数错误！', '11001');
        }
        $appInfo = S('ALLAPPINFO' . $this->appInfo['cinemaGroupId']);
        if (empty($appInfo)) {
            $appAccountMap['cinemaGroupId'] = $this->appInfo['cinemaGroupId'];
            $appInfo = D('Service')->getAppAccount('smsType, smsAccount, smsPassword, smsSign, registrationProtocol, voucherRule, androidVersion, androidDown, androidIsMust, androidExplain, iOSVersion, iOSDown, iOSIsMust, iOSExplain, proportion', $appAccountMap);
            S('ALLAPPINFO' . $this->appInfo['cinemaGroupId'], $appInfo, 72000);
        }

        if ($this->param['type'] == 'registration') {
            $this->success('', '&lt;html&gt;&lt;body&gt;' . $appInfo['registrationProtocol'] . '&lt;/body&gt;&lt;/html&gt;', 7200);
        }elseif ($this->param['type'] == 'voucher') {
            $this->success('', '&lt;html&gt;&lt;body&gt;' . $appInfo['voucherRule'] . '&lt;/body&gt;&lt;/html&gt;', 7200);
        }
    }

    /**
    * 获取会员ID
    * @param  
    * @return result
    *        {
    *            "status": "0",
    *            "data": md5(tokenId)
    *        }xmmgf123
    * @author 
    */

    public function getCinemaList()
    {

        $map['cinemaCode'] = array('IN', $this->appInfo['cinemaGroupInfo']['cinemaList']);
        $cinemaList = D('Cinema')->getCinemaList('cinemaCode, cinemaName, shortName, longitude, latitude, phone, prov, city, address', $map);
        $this->success('', $cinemaList, 3600);
    }

    public function showCache()
    {
        // echo C('CACHE_NAME_LIST');
        $cacheName = S(C('CACHE_NAME_LIST'));
        // print_r($cacheName);
        $clear = I('request.clear');
        foreach ($cacheName as $key => $value) {
            if(!strstr($key, C('CACHE_NAME_LIST') . 'APPINFO')){
                continue;
            }
            $nowValue = S($key);
            if(!empty($nowValue)){
                echo '名称：'. $key . '<br />';
                echo '创建时间：'. $value['createTime'] . '<br />';
                echo '过期时间：'. $value['expiration'] . '秒<br />';
                echo '缓存值：';
                print_r(S($key));
                echo '<br />';
                echo '剩余时间：'. ($value['expiration'] - (time() - strtotime($value['createTime']))) . '秒<br />';
                echo '<br /><div style="border-bottom:1px solid #ff0000; padding:0 5px;"></div><br />';
            }

            if(!empty($clear)){
                if(!(strstr($key, 'tokenId_') || strstr($key, 'APPINFOUserInfotokenId_')) || $clear == 'all'){
                    S($key, null);//获取影院列表
                    echo '清空' . $key . '缓存' . "<br />\r\n";
                }else{
                    echo $key . '缓存不处理' . "<br />\r\n";
                }
            }


        }
        // print_r($cacheName);
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
    public function getValidateCode()
    {
        if(empty($this->param['userMobile']) || empty($this->param['codeType'])){
            $this->error('参数错误！', '11001');
        }

        $codeInfo = S('tokenId_getMobileVerification' . $this->param['userMobile'] . $this->param['codeType']);
        if(($codeInfo['time'] + 120) >= time()){
            $this->error('您发送的太频繁，请稍后再试');
        }


        $memberMap['cinemaGroupId'] = $this->appInfo['cinemaGroupId'];
        $memberMap['mobile'] = $this->param['userMobile'];
        if(!checkMobile($memberMap['mobile'])){
        	$this->error('手机格式不正确');
        }
        $memberInfo = D('Member')->getUser( $memberMap);

        $code = rand(100000, 999999);

        if($this->param['codeType'] == 'find'){
            if(empty($memberInfo)){
                $this->error('手机号不存在！');
            }

            $content = '您正在申请找回密码，校验码是：' . $code . '，验证码2分钟之内有效，超时请重新获取。';

        }elseif ($this->param['codeType'] == 'register') {
            if(!empty($memberInfo)){
                $this->error('手机号已被注册！');
            }
            $content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
        }elseif ($this->param['codeType'] == 'bind') {
            
            $hasUser=D('Member')->getBindInfo(array('mobile'=>$this->param['userMobile'],'cinemaGroupId'=>$this->appInfo['cinemaGroupId']));

            if (!empty($hasUser)) {
               $this->error('手机号已被绑定！');
            }
            $content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
        }elseif ($this->param['codeType'] == 'unbind') {
            
            $content = '请输入验证码：' . $code . '，验证码2分钟之内有效，超时请重新获取。';
        }else{
            wlog('参数校验信息错误！' . json_encode($this->param), 'testLog');
            $this->error('参数校验信息错误！');
        }
        

        $smsConfig['smsType'] = $this->appInfo['cinemaGroupInfo']['smsType'];
        $smsConfig['smsAccount'] = $this->appInfo['cinemaGroupInfo']['smsAccount'];
        $smsConfig['smsPassword'] = $this->appInfo['cinemaGroupInfo']['smsPassword'];
        $smsConfig['smsSign'] = $this->appInfo['cinemaGroupInfo']['smsSign'];

        // print_r($smsConfig);

        $sms = new \Think\SmsModel($smsConfig);

        $smsResult = $sms->sendSms($this->param['userMobile'], $content);
        if($smsResult['code'] != 1){
            wlog(json_encode($smsResult),'getrechargeOrderStatus');
            $this->error($smsResult['text']);
        }else{
            $codeInfo['code'] = $code;
            $codeInfo['time'] = time();
            $successData['deadline'] = 120;
            S('tokenId_getMobileVerification' . $this->param['userMobile'] . $this->param['codeType'], $codeInfo, 120);
            wlog('验证码发送成功！' . json_encode($this->param), 'testLog');
            $this->success('发送成功，2分钟内有效！', $successData);
        }

    }
}