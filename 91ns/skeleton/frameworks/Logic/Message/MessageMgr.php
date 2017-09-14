<?php

namespace Micro\Frameworks\Logic\Message;
use Phalcon\DI\FactoryDefault;
use Micro\Models\PrivateMessage;
use Micro\Models\PrivateMessageConfig;
use Micro\Frameworks\Logic\User\UserFactory;

class MessageMgr{
    
    protected $di;
    protected $status;
    protected $validator;
    protected $typeConfigData;
    protected $config;
    protected $userAuth;
    protected $request;
    protected $baseCode;
    protected $comm;
    protected $modelsManager;
    protected $pushserver;
    protected $roomModule;
    protected $session;
    protected $pathGenerator;
    protected $storage;
    protected $pushMgr;
    public function __construct()
    {   
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->validator = $this->di->get('validator');
        $this->request = $this->di->get('request');
        $this->baseCode = $this->di->get('baseCode');
        $this->config = $this->di->get('config');
        $this->userAuth = $this->di->get('userAuth');
        $this->comm = $this->di->get('comm');
        $this->pushserver = $this->di->get('pushserver');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->roomModule = $this->di->get('roomModule');
        $this->session = $this->di->get('session');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->storage = $this->di->get('storage');
        $this->pushMgr = $this->di->get('pushMgr');
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【ConfigMgr】 error : '.$errInfo);
    }

    public function getMessageList($limit = 500){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        if($this->request->isGet()){
            // 获得置顶列表
            $sql = "select pm.id,pm.pcId,pm.sendUid,pm.type,pm.content,pm.addtime,pm.status from (select * from pre_private_message order by id desc) as pm left join pre_privatemessage_config as pc on pm.pcId=pc.id left join pre_user_profiles as up on ((pm.sendUid=up.uid and pm.toUid={$uid}) or (pm.toUid=up.uid and pm.sendUid={$uid})) where pm.isdel != {$uid} and pc.shield=0  and pc.top=1 group by pc.id order by up.level2 desc,up.level4 desc,up.level3 desc ";
            $db = $this->di->get('db');
            $topRes = $db->fetchAll($sql);
            if(empty($topRes)){
                $topRes = array();
            }

            // 获得非置顶用户
            $sql = "select pm.id,pm.pcId,pm.sendUid,pm.type,pm.toUid,pm.content,pm.addtime,pm.status from (select * from pre_private_message order by id desc) as pm , pre_privatemessage_config as pc where pm.pcId=pc.id and pm.isdel != {$uid} and pc.shield=0 and (pm.toUid={$uid} or pm.sendUid={$uid}) and pc.top=0 group by pc.id order by pm.status asc limit $limit";
            $db = $this->di->get('db');
            $commRes = $db->fetchAll($sql);
            if(empty($commRes)){
                $commRes = array();
            }

            $newRes = array_merge($topRes, $commRes);
            $data = array();
            if(!empty($newRes)){
                foreach($newRes as $key => &$val){
                    $otherUid = $val['sendUid'] == $uid ? $val['toUid'] : $val['sendUid'];
                    if($otherUid){
                        $user = UserFactory::getInstance($otherUid);
                        $userBaseInfo = $user->getUserInfoObject()->getUserInfo();
                        $vip = $user->getUserInfoObject()->getVipLevel();
                        $val['otherUserInfo'] = array(
                            'uid' => $otherUid,
                            'nickName' => $userBaseInfo['nickName'],
                            'avatar' => $userBaseInfo['avatar'],
                            'vip' => $vip,
                        );
                    }
//                    $sendUid = $val['sendUid'];
//                    $user = UserFactory::getInstance($sendUid);
//                    if(empty($user)){
//                        unset($newRes[$key]);
//                        continue;
//                    }
//
//                    $userBaseInfo = $user->getUserInfoObject()->getUserInfo();
//                    $val['nickName'] = $userBaseInfo['nickName'];
//                    $val['avatar'] = $userBaseInfo['avatar'];
                    if($val['status'] == 0){
                        // 统计有多少未读
                        $count = PrivateMessage::count("pcId={$val['pcId']} and toUid={$uid} and status=0");
                        // 获得列表
                        $list = PrivateMessage::find("pcId={$val['pcId']} and toUid={$uid} and status=0")->toArray();
                        $val['list'] = $list;
                        $val['count'] = $count;
                    }else{
                        $val['count'] = 0;
                        $val['list'] = array();
                    }

                    $data[$key] = array(
                        'id' => $val['id'],
                        'pcId' => $val['pcId'],
                        'sendUid' => $val['sendUid'],
                        'toUid' => $val['toUid'],
                        'list' => $val['list'],
                        'type' => $val['type'],
                        'content' => $val['content'],
                        'addtime' => $val['addtime'],
                        'status' => $val['status'],
                        'count' => $val['count'],
                        'otherUserInfo' => $val['otherUserInfo']
                    );
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }

        return $this->status->retFromFramework($this->status->getCode('URI_ERROR'));
    }

    public function getMessageInfo($toUid){
        $data = $otherUserInfo = array();
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        $postData['uid'] = $toUid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 获得pcid
        $privateMessageConfig = PrivateMessageConfig::findFirst("(uid={$uid} and toUid={$toUid}) or (uid={$toUid} and toUid={$uid})");
        if($privateMessageConfig){
            $pcId = $privateMessageConfig->id;
        }

        if(!empty($pcId)){
            $data = PrivateMessage::find("pcId = $pcId AND (toUid={$uid} OR sendUid={$uid})")->toArray();
//            if($data){
//                foreach($data as $key => $val){
//                    $otherUid = $val['sendUid'] == $uid ? $val['toUid'] : $val['sendUid'];
//                }
//            }
        }

        if($toUid){
            $user = UserFactory::getInstance($toUid);
            $userBaseInfo = $user->getUserInfoObject()->getUserInfo();
            $vip = $user->getUserInfoObject()->getVipLevel();
            $otherUserInfo = array(
                'uid' => $toUid,
                'nickName' => $userBaseInfo['nickName'],
                'avatar' => $userBaseInfo['avatar'],
                'vip' => $vip
            );
        }

        $result['data'] = $data;
        $result['otherUserInfo'] = $otherUserInfo;
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function uploadMessageImg(){
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                // 自身业务的验证
                $userdata = $this->session->get($this->config->websiteinfo->authkey);
                $uid = $userdata['uid'];
                if (empty($uid)) {
                    return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
                }

                try {
                    foreach ($this->request->getUploadedFiles() as $file) {
                        $fileNameArray = explode('.', strtolower($file->getName()));
                        $fileExt = $fileNameArray[count($fileNameArray) - 1];
                        $filePath = $this->pathGenerator->getMessagePath($uid);
                        $fileName = time() . '.' . $fileExt;
                        $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                        try {
                            $messageImg = $this->pathGenerator->getFullMessagePath($uid, $fileName);
                            return $this->status->retFromFramework($this->status->getCode('OK'), $messageImg);
                        } catch (\Exception $e) {
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
            }
        }

        return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
    }

    public function sendMessage(){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $sendUid = $user->getUid();
        if($this->request->isPost()){
            $toUid = $this->request->getPost('toUid');
            $content = $this->request->getPost('content');
            $type = intval($this->request->getPost('type')); // 类型，默认为0，1表示图片
            $postData['uid'] = $toUid;
            $postData['content'] = $content;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }

            try{
                // 获得私信配置
                $privateMessageConfig = PrivateMessageConfig::findFirst("(uid={$sendUid} and toUid={$toUid}) or (uid={$toUid} and toUid={$sendUid})");
                if($privateMessageConfig){
                    $pcId = $privateMessageConfig->id;
                }else{
                    // 插入配置信息
                    $messageConfig = new PrivateMessageConfig();
                    $messageConfig->uid = $sendUid;
                    $messageConfig->toUid = $toUid;
                    $messageConfig->top = 0;
                    $messageConfig->shield = 0;
                    $messageConfig->lastTime = time();
                    $messageConfig->save();
                    $pcId = $messageConfig->id;
                }

                $message = new PrivateMessage();
                $message->pcId = $pcId;
                $message->sendUid = $sendUid;
                $message->toUid = $toUid;
                $message->status = 0;
                $message->isdel = 0;
                $message->type = intval($type);
                $message->content = $content;
                $message->addtime = time();
                $res = $message->save();
                if($res){
                    $user = UserFactory::getInstance($sendUid);
                    $nickName = $user->getUserInfoObject()->getNickName();
                    $userData = $user->getUserInfoObject()->getUserInfo();
                    $touser = UserFactory::getInstance($toUid);
                    $touserBaseInfo = $touser->getUserInfoObject()->getUserInfo();
                    $vip = $touser->getUserInfoObject()->getVipLevel();
                    $otherUserInfo = array(
                        'uid' => $toUid,
                        'nickName' => $touserBaseInfo['nickName'],
                        'avatar' => $touserBaseInfo['avatar'],
                        'vip' => $vip
                    );

                    // 添加推送
                    $message = array(
                        'action' => 'privateMessage',
                        'sendUid' => $sendUid,
                        'toUid' => $toUid,
                        'pcId' => $pcId,
                        'content' => $content,
                        'imgUrl' => $type == 1 ? $content : '',
                        'type'  => $type,
                        'addtime' => time(),
                        'otherUserInfo' => $otherUserInfo,
                    );

                    $content = $nickName . ":" . ($type == 1 ? '图片' : $content);
                    $uidsList = array($toUid);
                    if($uidsList){
                        $this->pushMgr->sendMessage($uidsList, $message, $content);

//                        // 获得ios推送列表
//                        $tokenListRes = $this->roomModule->getRoomMgrObject()->getTokenByUid($this->config->pushservice->type->ios, $uidsList, 'devicetoken');
//                        if($tokenListRes['code'] == $this->status->getCode('OK') && !empty($tokenListRes['data'])){
//                            $this->pushserver->pushAPNMessageToList($tokenListRes['data'], json_encode($message), $content);
//                        }
//
//
//                        // 获得安卓推送列表
//                        $tokenListRes = $this->roomModule->getRoomMgrObject()->getTokenByUid($this->config->pushservice->type->android, $uidsList, 'clientID');
//                        if($tokenListRes['code'] == $this->status->getCode('OK') && !empty($tokenListRes['data'])){
//                            $message['content'] = $content;
//                            $this->pushserver->pushMessageToList($tokenListRes['data'], json_encode($message));
//                        }
//
                        $ArraySubData['controltype'] = "privateMessage";
                        $broadData['content'] = $content;
                        $broadData['userdata'] = $userData;
                        $ArraySubData['data'] = $broadData;
                        // 网页单点广播
                        $user = UserFactory::getInstance($toUid);
                        $accountId = $user->getUserInfoObject()->getAccountId();
                        $roomList = $this->roomModule->getRoomMgrObject()->getUsersWhereIn($toUid);
                        if($roomList){
                            foreach($roomList as $roomVal){
                                $this->comm->roomNotify($roomVal['roomid'], $accountId, $ArraySubData);
                            }
                        }
                    }

                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }else{
                    return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                }
            }catch (\Exception $e){
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }

        }

        return $this->status->retFromFramework($this->status->getCode('URI_ERROR'));
    }

    public function setMessageRead(){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        if($this->request->isPost()){
            $pcId = $this->request->getPost('pcId');
            $postData['id'] = $pcId;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }

            try{
                $messageData = PrivateMessage::find("pcId={$pcId} and toUid={$uid}");
                if($messageData){
                    $sql = "update \Micro\Models\PrivateMessage set status=1 where pcId={$pcId} and toUid={$uid}";
                    $query = $this->modelsManager->createQuery($sql);
                    $res = $query->execute();
                    if($res){
                        return $this->status->retFromFramework($this->status->getCode('OK'));
                    }

                    return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                }else{
                    return $this->status->retFromFramework($this->status->getCode('MESSAGE_NOT_EXIST'));
                }
            }catch (\Exception $e){
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }
        }

        return $this->status->retFromFramework($this->status->getCode('URI_ERROR'));
    }


    public function setMessageTop(){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        if($this->request->isPost()){
            $pcId = $this->request->getPost('pcId');
            $status = $this->request->getPost('status');
            $postData['id'] = $pcId;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }

            if(!in_array($status, array(0, 1))){
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }

            try{
                $messageData = PrivateMessageConfig::findFirst("id={$pcId}");
                if($messageData){
                    $updateData = array(
                        'top' => $status,
                    );

                    $res = $messageData->update($updateData);
                    if($res){
                        return $this->status->retFromFramework($this->status->getCode('OK'));
                    }

                    return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                }else{
                    return $this->status->retFromFramework($this->status->getCode('MESSAGE_NOT_EXIST'));
                }
            }catch (\Exception $e){
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }
        }

        return $this->status->retFromFramework($this->status->getCode('URI_ERROR'));
    }

    public function delMessage(){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        if($this->request->isPost()){
            $pcId = $this->request->getPost('pcId');
            $postData['id'] = $pcId;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }

            try{
                $messageData = PrivateMessage::findFirst("pcId={$pcId}");
                if($messageData){
                    // 判断里面的删除字段
                    if($messageData->isdel > 0){
                        if($messageData->isdel == $uid){
                            // 该用户已经删除过
                            return $this->status->retFromFramework($this->status->getCode('MESSAGE_NOT_EXIST'));
                        }else{
                            // 对方双方都请求删除，则全部删除
                            $res = PrivateMessage::find("pcId={$pcId}")->delete();
                            if($res){
                                return $this->status->retFromFramework($this->status->getCode('OK'));
                            }else{
                                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                            }
                        }
                    }else{
                        $sql = "update \Micro\Models\PrivateMessage set isdel=$uid where pcId={$pcId}";
                        $query = $this->modelsManager->createQuery($sql);
                        $res = $query->execute();
                        if($res){
                            return $this->status->retFromFramework($this->status->getCode('OK'));
                        }else{
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                        }
                    }
                }else{
                    return $this->status->retFromFramework($this->status->getCode('MESSAGE_NOT_EXIST'));
                }
            }catch (\Exception $e){
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }
        }

        return $this->status->retFromFramework($this->status->getCode('URI_ERROR'));
    }
}