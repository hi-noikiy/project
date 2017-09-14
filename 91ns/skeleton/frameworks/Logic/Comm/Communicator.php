<?php

namespace Micro\Frameworks\Logic\Comm;

use Phalcon\DI\FactoryDefault;
//use Phalcon\Http\Client\Request;

use Requests;
use Exception;

class Communicator {
    public $_server ="";
    public $_port="";

    public function init($server, $port) {
        $this->_server = $server;
        $this->_port = $port;
    }

    public function errLog($errInfo) {
        $di = FactoryDefault::getDefault();
        $logger = $di->get('logger');
        $logger->error('【Communicator】 error : '.$errInfo);
    }

    private function post($uri, $params=array()) {
        try {
            $UrlBase = "http://".$this->_server.":".$this->_port."/".$uri;
            $response = Requests::post($UrlBase, array(), $params);

            if (intval($response->status_code) == 200) {
                $result = $response->body;
                if (empty($result)) {
                    return false;
                }
                $resultArray = json_decode($result, true);
                if (array_key_exists('err', $resultArray)) {    //nodejs服务器未知错误, 正式生产环境中不会有这样的情况出现
                    $this->errLog(' errorResult : ' . $result);

                    $resultData['code'] = -1;
                    return $resultData;
                }
                return $resultArray;

            }

            return false;
        } catch (Exception $e){
            //echo $e->getCode();
            //echo $e->getMessage();
            return false;
        }
    }

    // 用户注册ChatServer
    public function userReg($regname, $regpwd) {
        $UrlMiddle = "srpc/auth/reg/".$regname."/";
        $UrlLocalParams['name']=$regname;
        $UrlLocalParams['psw']=$regpwd;

        $result = $this->post($UrlMiddle, $UrlLocalParams);
        return $result;
        // if (empty($result) || ($result === false)) {
        //     return false;
        // }

        // $resultArray = json_decode($result, true);
        // return $resultArray;
    }

    // 用户账号注销（删除）ChatServer
    public function userUnreg($regname) {
        $UrlMiddle = "srpc/auth/unReg/".$regname."/";
        $UrlLocalParams['name']=$regname;

        $result = $this->post($UrlMiddle, $UrlLocalParams);
        return $result;
    }

    // 用户登录ChatServer
    public function userLogin($loginname, $loginpwd) {
        $UrlMiddle = "srpc/auth/auth/".$loginname."/";
        $UrlLocalParams['name']=$loginname;
        $UrlLocalParams['psw']=$loginpwd;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // QQ注册ChatServer
    public function qqReg($qqRegOpenId) {
        $UrlMiddle = "srpc/auth/QQReg/".$qqRegOpenId."/";
        $UrlLocalParams['openid']=$qqRegOpenId;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // QQ账号注销（删除）ChatServer
    public function qqUnreg($qqRegOpenId) {
        $UrlMiddle = "srpc/auth/QQUnReg/".$qqRegOpenId."/";
        $UrlLocalParams['openid']=$qqRegOpenId;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // QQ登录ChatServer
    public function qqLogin($qqLoginOpenId) {
        $UrlMiddle = "srpc/auth/QQAuth/".$qqLoginOpenId."/";
        $UrlLocalParams['openid']=$qqLoginOpenId;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // Sina注册ChatServer
    public function sinaReg($sinaRegOpenId) {
        $UrlMiddle = "srpc/auth/SINAReg/".$sinaRegOpenId."/";
        $UrlLocalParams['openid']=$sinaRegOpenId;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // Sina登录ChatServer
    public function sinaLogin($sinaLoginOpenId) {
        $UrlMiddle = "srpc/auth/SINAAuth/".$sinaLoginOpenId."/";
        $UrlLocalParams['openid']=$sinaLoginOpenId;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 修改ChatServer密码功能接口
    public function userChangePwd($loginName, $oldPwd, $newPwd) {
        $UrlMiddle = "srpc/auth/chgPwd/".$loginName."/";
        $UrlLocalParams['name']=$loginName;
        $UrlLocalParams['oldPsw']=$oldPwd;
        $UrlLocalParams['newPsw']=$newPwd;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 重置ChatServer密码功能接口
    public function userResetPwd($loginName, $newPwd) {
        $UrlMiddle = "srpc/auth/resetPwd/".$loginName."/";
        $UrlLocalParams['name']=$loginName;
        $UrlLocalParams['newPsw']=$newPwd;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 登录ChatServer获取LoginKey
    public function loginToNodeJS($accountid) {
        $UrlMiddle = "getLoginKey/".$accountid;

        return $this->post($UrlMiddle);
    }

    // 进入ChatServer房间
    // devicetype:字符串，表示是什么平台，是给聊天服务器做是否要T人用的
    // deviceid:字符串，表示当前登录用户的设备id，如果被t，则会触发roomKickUser消息，并将T人者的deviceid下发
    public function enterNodeJSRoom($nodejstoken, $roomid, $name, $level, $devicetype, $deviceid, 
                                    $broadcastMask, $broadcastThrowMask, $callbackMask, $userType, $forbid, $userdata) {
        $UrlMiddle = "rpc/room/enterRoom/"."[\"uid\",\"connSid\",\"sessionId\"]/".$nodejstoken."/".$roomid;
        $UrlLocalParams['roomId']=(int)$roomid;
        $UrlLocalParams['token']=$nodejstoken;
        $ArraySubData['name']=$name;
        $ArraySubData['level']=(int)$level; //应该从数据库中查找Level
        $ArraySubData['forbidTalk']=$forbid;    //是否禁言
        $ArraySubData['deviceType']=$devicetype;
        $ArraySubData['deviceId']=$deviceid;
        $ArraySubData['broadcastMask']=$broadcastMask;
        $ArraySubData['broadcastThrowMask']=$broadcastThrowMask;
        $ArraySubData['callbackMask']=$callbackMask;
        $ArraySubData['userType']=$userType;
        if ($userdata) {
            $ArraySubData['userdata']=$userdata;    //增加UserData，后面做广播使用
        }
        $UrlLocalParams['data']=$ArraySubData;
        
        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 获取房间总人数
    public function getTotalCountInRoom($roomid) {
        $UrlMiddle = "srpc/room/getTotalCount/".$roomid."/";
        $UrlLocalParams['roomId']=(int)$roomid;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }
	
	//获取房间用户列表
    public function collectMembers($nodejstoken, $roomid, $count) {
        $UrlMiddle = "rpc/room/collectMembers/"."[\"boothSid\"]/".$nodejstoken."/".$roomid."/";
        $UrlLocalParams['roomId']=(int)$roomid;
        $UrlLocalParams['token']=$nodejstoken;
        $ArraySubData['count']=$count;
        $UrlLocalParams['msg']=$ArraySubData;
        
        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    //获取房间管理员列表
    public function collectAdmins($nodejstoken, $roomid) {
        $UrlMiddle = "rpc/room/collectAdmins/"."[\"boothSid\"]/".$nodejstoken."/".$roomid."/";
        $UrlLocalParams['roomId']=(int)$roomid;
        $UrlLocalParams['token']=$nodejstoken;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 房间广播接口
    public function roomBroadcast($roomid, $broadData) {
        $UrlMiddle = "srpc/room/roomBroadcast/".$roomid."/";
        $UrlLocalParams['roomId']=$roomid;
        $UrlLocalParams['data']=$broadData;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    //单点房间通知
    public function roomNotify($roomid, $uid, $data) {
        $UrlMiddle = "srpc/room/roomNotify/".$roomid."/";
        $UrlLocalParams['roomId']=$roomid;
        $UrlLocalParams['uid']=$uid;
        $UrlLocalParams['data']=$data;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 修改用户权限
    public function levelUpPermission($nodejstoken, $roomid, $uid, $level, $oldLevel = 1) {
        $UrlMiddle = "rpc/room/levelUp/"."[\"boothSid\"]/".$nodejstoken."/".$roomid."/";
        $UrlLocalParams['roomId']=(int)$roomid;
        $UrlLocalParams['token']=$nodejstoken;
        $ArraySubData['uid']=$uid;
        $ArraySubData['level']=$level;
        $ArraySubData['oldLevel']=$oldLevel;//旧的等级
        $UrlLocalParams['msg']=$ArraySubData;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 禁言/解禁
    public function forbidTalk($nodejstoken, $roomid, $accountId, $isforbid, $superAdmin = '') {
        $UrlMiddle = "rpc/room/forbidTalk/"."[\"boothSid\"]/".$nodejstoken."/".$roomid."/";
        $UrlLocalParams['roomId']=(int)$roomid;
        $UrlLocalParams['token']=$nodejstoken;
        $ArraySubData['uid']=$accountId;
        $ArraySubData['forbid']=(int)$isforbid;
        $UrlLocalParams['msg']=$ArraySubData;
        if($superAdmin){
            $UrlLocalParams['talkMsg'] = $superAdmin;
        }

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 踢人
    public function kickUser($nodejstoken, $roomid, $uid, $superAdmin = '') {
        $UrlMiddle = "rpc/room/kickUser/"."[\"boothSid\"]/".$nodejstoken."/".$roomid."/";

        $UrlLocalParams['roomId']=(int)$roomid;
        $UrlLocalParams['token']=$nodejstoken;
        $ArraySubData['uid']=$uid;
        $UrlLocalParams['msg']=$ArraySubData;
        if($superAdmin){
            $UrlLocalParams['kickMsg'] = $superAdmin;
        }

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 同房间挤人
    public function roomRepeatKick($nodejstoken, $beKickToken, $roomid, $devicetype, $deviceid) {
        $UrlMiddle = "rpc/room/roomRepeatKick/"."[\"uid\"]/".$nodejstoken."/".$roomid;
        $UrlLocalParams['roomId']=(int)$roomid;
        $UrlLocalParams['beKickToken']=$beKickToken;
        $UrlLocalParams['deviceType']=$devicetype;
        $UrlLocalParams['deviceId']=$deviceid;
        
        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 更新用户UserData
    public function updateUserData($roomid, $uid, $userdata) {
        $UrlMiddle = "srpc/room/updateUserdata/".$roomid."/";
        $UrlLocalParams['roomId']=$roomid;
        $UrlLocalParams['uid']=$uid;
        $UrlLocalParams['userdata']=$userdata;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 获取房间所有配置信息
    public function getRoomConfig() {
        $UrlMiddle = "srpc/room/getConfig/"."null/";

        return $this->post($UrlMiddle);
    }

    // 设置机器人配置信息
    /*
    {"enabled":1,"interval":6,
    "increase":[{"minCount":0,"maxCount":100,"unitPerUser":1,"unitPerTimes":0},{"minCount":100,"maxCount":1000,"unitPerUser":2,"unitPerTimes":5}],
    "reduce":{"waitTime":1,"percent":20,"percentPerTimes":2}}
     */
    /*public function setRobotConfig($robotConfig) {
        $UrlMiddle = "srpc/room/updateConfig/"."null/";
        $UrlLocalParams['name']='robotConfig';
        $UrlLocalParams['value']=json_decode($robotConfig, true);

        return $this->post($UrlMiddle, $UrlLocalParams);
    }*/

    /**
    * 更新机器人配置
    * createTime: 2015-01-04
    */
    public function setRobotConfig($robotConfig) {
        $di = FactoryDefault::getDefault();
        $config = $di->get('config');

        $UrlMiddle = "srpc/room/updateConfig/"."null/";
        $UrlLocalParams['name'] = $config->robotVersion == '0.0.2'?'newRobotConfig':'robotConfig';
        $UrlLocalParams['time'] = time();
        $UrlLocalParams['value'] = json_decode($robotConfig, true);

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    /**
    * 更新机器人配置
    * createTime: 2015-01-04
    * @param:
    *    UID_MIN: 300000,
    *    LEVEL : {'0': 30,'1': 30,'2': 20,'3': 10,'4': 10}
    */
    public function resetRobot($data) {
        $di = FactoryDefault::getDefault();
        $config = $di->get('config');

        $UrlMiddle = "srpc/room/resetRobot/"."null/";
        $UrlLocalParams['MIN_UID'] = $config->robotMinUid;
        $UrlLocalParams['MAX_UID'] = $config->robotMaxUid;
        $UrlLocalParams['LEVEL'] = $data['LEVEL'];
        $UrlLocalParams['time'] = time();

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 设置回调地址配置信息
    /*
    {"enabled":1,"url":"http:\/\/192.168.235.197:9000\/services\/test","broadcastCallback":63}
     */
    public function setCallbackConfig($callbackConfig) {
        $UrlMiddle = "srpc/room/updateConfig/"."null/";
        $UrlLocalParams['name']='callbackConfig';
        $UrlLocalParams['value']=json_decode($callbackConfig, true);

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 更新房间状态
    public function updateRoomStatus($roomId, $liveStatus) {
        $UrlMiddle = "srpc/room/updateRoomStatus/".$roomId."/";
        $UrlLocalParams['roomId']=$roomId;
        $LocalParams['status']=$liveStatus;
        $LocalParams['roomId']=$roomId;
        $UrlLocalParams['data']=$LocalParams;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 增减机器人的个数
    public function changeRobotCount($roomId, $count, $time) {
        $di = FactoryDefault::getDefault();
        $config = $di->get('config');

        $UrlMiddle = "srpc/room/changeRobotCount/".$roomId."/";
        $UrlLocalParams['roomId']=$roomId;
        $LocalParams['changeRobotCount'] = $count;
        $LocalParams['roomId']=$roomId;

        $LocalParams['needTime'] = $config->robotVersion == '0.0.2'?$time:'';

        $UrlLocalParams['data']=$LocalParams;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 进入IM中心
    public function enterIm($nodejstoken, $uid) {
        $UrlMiddle = "rpc/im/enterIm/"."[\"uid\",\"connSid\",\"sessionId\"]/".$nodejstoken."/".$uid."/";
        $UrlLocalParams['uid']=$uid;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    // 系统广播
    public function systemNotice($data) {
        $UrlMiddle = "systemnotice/";
        $UrlLocalParams['data']=$data;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    //单点通知某个用户uid
    public function notifyMsg($uid, $data) {
        $UrlMiddle = "srpc/im/notifyMsg/".$uid."/";
        $UrlLocalParams['uidTo']=$uid;
        $UrlLocalParams['content']=$data;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }

    //被关注者发布动态，通知所有其他关注者
    public function notifyTrend($nodejstoken, $uid, $data) {
        $UrlMiddle = "rpc/im/notifyTrend/"."null/".$nodejstoken."/".$uid."/";
        $UrlLocalParams['uid']=$uid;
        $UrlLocalParams['notifyMsg']=$data;

        return $this->post($UrlMiddle, $UrlLocalParams);
    }
}