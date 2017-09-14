<?php

namespace Micro\Controllers;
use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class FamilyController extends ControllerBase
{
     /*
     * 获取家族信息
     * */
    public function getFamilyInfo($familyId){
        $data = array(
            'familyInfo' => '',
            'familyCreatorInfo' => '',
            'familyMembers' => ''
        );

        if(!empty($familyId)){
            $result = $this->familyMgr->getFamilyInfo(intval($familyId));
            if($result['code'] == $this->status->getCode('OK')){
                $data['familyInfo'] = $result['data'];
            }else{
                return $this->status->mobileReturn($result['code'], $result['data']);
            }

            //家族长信息
            $creatorInfo = $this->familyMgr->getFamilyCreatorInfo($result['data']['creatorUid']);
            if ($creatorInfo['code'] == $this->status->getCode('OK')) {
                $levelInfo = $this->userMgr->getUserLevelInfo($result['data']['creatorUid']);
                if($levelInfo['code'] == $this->status->getCode('OK') ){
                    $creatorInfo['data']['anchorLevel'] = $levelInfo['data']['anchorLevel'];
                    $creatorInfo['data']['richerLevel'] = $levelInfo['data']['richerLevel'];
                    $creatorInfo['data']['fansLevel'] = $levelInfo['data']['fansLevel'];
                    $creatorInfo['data']['vipLevel'] = $levelInfo['data']['vipLevel'];
                }else{
                    return $this->status->mobileReturn($levelInfo['code'], $levelInfo['data']);
                }

                $data['familyCreatorInfo'] = $creatorInfo['data'];
            }else{
                return $this->status->mobileReturn($creatorInfo['code'], $creatorInfo['data']);
            }

            //家族成员
            $familyMember = $this->familyMgr->getFamilyMemberInfo($result['data']['id']);
            if ($familyMember['code'] == $this->status->getCode('OK')) {
                $data['familyMembers'] = $familyMember['data']['data'];
            }else{
                return $this->status->mobileReturn($familyMember['code'], $familyMember['data']);
            }

            return $this->status->mobileReturn($this->status->getCode('OK'), $data);
        }

        return $this->proxyError();
    }

}