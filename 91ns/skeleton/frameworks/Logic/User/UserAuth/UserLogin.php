<?php

namespace Micro\Frameworks\Logic\User\UserAuth;
use Phalcon\DI\FactoryDefault;
use Micro\Models\Users;
use Micro\Frameworks\Logic\User\UserFactory;

class UserLogin{
    public static function setLoginStatus($uid) {
        $di = FactoryDefault::getDefault();
        $session = $di->get('session');
        $config = $di->get('config');
        $status = $di->get('status');
        try{
            if($uid){
                $user = Users::findFirst($uid);

                if($user && $user->status == 1){
                    $data =  array(
                       // 'accountId' => $user->accountId,
                        'accountId' => $user->uid,
                        'uid' => $user->uid,
                        'name' => $user->userName,
                        'userType' => $user->userType,
                    );

                    //$logger = $di->get('logger');
                    //$logdata = json_encode($data, JSON_UNESCAPED_UNICODE);
                    //$logger->error('setLoginStatus sessionId = '.$session->getId().'  userLogin data : '.$logdata);
                    $session->set($config->websiteinfo->authkey, $data);

                    $user->updateTime = time();
                    $user->save();
                    
                    //登录日志记录
                    $log = new \Micro\Frameworks\Logic\Base\BaseStatistics();
                    $log->setLoginLog($uid);
                    
                    //添加新手引导-登录任务
                    $taskMgr = $di->get('taskMgr');
                    $taskMgr->setUserTask($user->uid, $config->taskIds->login);

                    //判断是否app的手机或者微信登录
                    // if($isMobile && ($user->userType == $this->config->userType->telephone || $user->userType == $this->config->userType->weixin)){
                    $isRec = 1;
                    $sql = 'select up.level3,rl.id as recId,rd.id as dealId from pre_user_profiles as up '
                        . ' left join pre_recommend_log as rl on up.uid = rl.beRecUid left join pre_rec_refuse_log as rd on rd.uid = up.uid '
                        . ' where up.uid = ' . $uid;
                    $connection = $di->get('db');
                    $result = $connection->fetchOne($sql);
                    if($result && $result['level3'] == 0 && !$result['recId'] && !$result['dealId']){
                        $isRec = 0;
                    }
                    

                    //查询用户渠道
                    //$loginUser = UserFactory::getInstance($user->uid);
                    //$ns_sources = $loginUser->getUserInfoObject()->getUserSource();
                    // $data['ns_source'] = $ns_sources['ns_source'];
                    //$data['utm_medium'] = $ns_sources['utm_medium'];
                    
                    //下发用户当前渠道
                   // $cookies = $di->get('cookies');
                  //  $data['ns_source'] = trim($cookies->get($config->websitecookies->utm_source)->getValue());
                   // $data['utm_medium'] = trim($cookies->get($config->websitecookies->utm_medium)->getValue());
                    $data['ns_source'] = '';
                    $data['utm_medium'] = '';
                    $data['isRec'] = $isRec;//是否推荐

                    return $status->retFromFramework($status->getCode('OK'), $data);
                }
                return $status->retFromFramework($status->getCode('USER_NOT_EXIST'));
            }
        }catch (\Exception $e) {
            return $status->retFromFramework($status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public static function userLogout(){
        $di = FactoryDefault::getDefault();
        $config = $di->get('config');
        $session = $di->get('session');
        $status = $di->get('status');

        /*$logger = $di->get('logger');
        if ($session->get($config->websiteinfo->authkey) != NULL) {
            $userData = $session->get($config->websiteinfo->authkey);
            $data = json_encode($userData, JSON_UNESCAPED_UNICODE);
            $logger->error('sessionId='.$session->getId().'  userLogout data : '.$data);
        }
        else {
            $logger->error('sessionId='.$session->getId().'  userLogout not data : ');
        }*/

        //$session->set($config->websiteinfo->authkey, '');
        $session->remove($config->websiteinfo->authkey);
        //$session->destroy();
        return $status->retFromFramework($status->getCode('OK'));
    }


}