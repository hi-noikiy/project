<?php

namespace Micro\Frameworks\Logic\User;

//use Phalcon\DI\FactoryDefault;

class UserFactory {

    /**
     * 
     * @return UserAbstract
     */
    public static function getInstance($uid){
        $user = self::user($uid);
        return $user;
    }

    public static function user($uid){
        return new User($uid);
    }

    public static function isRobot($uid){
        $di = \Phalcon\DI\FactoryDefault::getDefault();
        $config = $di->get('config');
        if($config->robotMinUid <= $uid && $uid <= $config->robotMaxUid){
            //if(User::getRobotData($uid)){
            return true;
            //}
        }
        return false;
    }

    public static function getRobotData($uid){
        $data = User::getRobotData($uid);
        $data['vipLevel'] = 0;
        $data['anchorLevel'] = 0;
        $data['guardLevel'] = 0;
        $data['fansLevel'] = 0;
        $data['accountId'] = $uid;
        $data['userId'] = $uid;
        $data['points'] = 0;
        $data['badge'] = array();
        $data['superManageType'] = 0;
        $data['manageType'] = 0;
        $data['level'] = 1;
        return $data;
    }

    public static function getRobotData_detail($uid){
        $data = self::getRobotData($uid);
        $data['signature'] = '';
        $data['email'] = 0;
        $data['gender'] = 0;
        $data['birthday'] = 0;
        $data['seclevel'] = 0;
        $data['fanscount'] = 0;
        $data['focuscount'] = 0;
        $data['coin'] = 0;
        $data['cash'] = 0;
        $data['anchorExp'] = 0;
        $data['richerExp'] = 0;
        $data['fansExp'] = 0;
        $data['charmExp'] = 0;
        return $data;
    }

    public static function getRobotData_levelInfo($uid){
        $di = \Phalcon\DI\FactoryDefault::getDefault();
        $configMgr = $di->get('configMgr');
        $status = $di->get('status');

        $data = self::getRobotData($uid);

        $data['anchorLevel'] = 0;
        //$data['richerLevel'] = $userInfo['richerLevel'];
        $data['fansLevel'] = 0;
        $data['vipLevel'] = 0;
        $data['anchorExp'] = 0;
        $data['richerExp'] = 0;

        $data['fansExp'] = 0;
        // 得到主播等级上下限配置值
        $conditions = "level = :level:";
        $parameters = array("level" => $data['anchorLevel']);
        $result = $configMgr->getAnchorConfigInfoEx($conditions, $parameters);
        if ($result['code'] == $status->getCode('OK')) {
            $data['anchorLevelHigher'] = $result['data']['higher']+1;
            $data['anchorLevelLower'] = $result['data']['lower'];
        }
        $conditions= "level = :level:";
        $parameters=array("level"=>($data['anchorLevel']+1));
        $result=$configMgr->getAnchorConfigInfoEx($conditions,$parameters);
        if ($result['code'] == $status->getCode('OK')) {
            $data['nextAnchorLevel'] = $result['data']['level'];
            $data['nextAnchorName'] = $result['data']['name'];
        } else if ($result['code'] == $status->getCode('DATA_IS_NOT_EXISTED')) {//满级
            $data['nextAnchorLevel'] = $data['anchorLevel'];
        }
        // 得到主播粉丝上下限配置值
        $conditions = "level = :level:";
        $parameters = array("level" => $data['fansLevel']);
        $result = $configMgr->getFansConfigInfoEx($conditions, $parameters);
        if ($result['code'] == $status->getCode('OK')) {
            $data['fansLevelHigher'] = $result['data']['higher']+1;
            $data['fansLevelLower'] = $result['data']['lower'];
        }
        $conditions= "level = :level:";
        $parameters=array("level"=>($data['fansLevel']+1));
        $result=$configMgr->getFansConfigInfoEx($conditions,$parameters);
        if($result['code'] == $status->getCode('OK')){
            $data['nextfansLevel']=$result['data']['level'];
            $data['nextFansName']=$result['data']['name'];
        } else if ($result['code'] == $status->getCode('DATA_IS_NOT_EXISTED')) {//满级
            $data['nextfansLevel'] = $data['fansLevel'];
        }
        // 得到富豪等级上下限配置值
        $conditions = "level = :level:";
        $parameters = array("level" => $data['richerLevel']);
        $result = $configMgr->getRicherConfigInfoEx($conditions, $parameters);
        if ($result['code'] == $status->getCode('OK')) {
            $data['richerLevelHigher'] = $result['data']['higher']+1;
            $data['richerLevelLower'] = $result['data']['lower'];
        }
        $conditions= "level = :level:";
        $parameters=array("level"=>($data['richerLevel']+1));
        $result=$configMgr->getRicherConfigInfoEx($conditions,$parameters);
        if($result['code'] == $status->getCode('OK')){
            $data['nextRicherLevel']=$result['data']['level'];
            $data['nextRicherName']=$result['data']['name'];
        } else if ($result['code'] == $status->getCode('DATA_IS_NOT_EXISTED')) {//满级
            $data['nextRicherLevel'] = $data['richerLevel'];
        }
        return $data;
    }
}
