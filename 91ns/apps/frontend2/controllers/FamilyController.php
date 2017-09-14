<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class FamilyController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '家族';
            $this->view->ns_active = 'family';
        }
        parent::initialize();
    }

    public function indexAction(){
        $this->view->ns_active = 'family';
        //获取家族信息
        $user = $this->userAuth->getUser();
        if (!$user) {
//            return $this->redirect('/');
            $this->view->familyInfo = '';
            $this->view->familyMembers = '';
            $this->view->applyBtn = '';
        }else{
            $uid = $user->getUid();
            $result = $this->familyMgr->getFamilyInfoByUid($uid);
            $this->view->familyInfo = $result['data'];

            //家族成员
            $familyId = isset($result['data']['id']) ? intval($result['data']['id']) : 0;
            $familyMember = $this->familyMgr->getFamilyMemberInfo($familyId, 1);
            if ($familyMember['code'] == $this->status->getCode('OK')) {
                $this->view->familyMembers = $familyMember['data']['data'];
            }else{
                $this->view->familyMembers = array();
            }
            // var_dump($this->familyMgr->getFamilyListNew(1,1,25));die;

            // $this->view->applyBtn = $this->familyMgr->getFamilyStatus($uid);
            // $this->cornMgr->getFamilyAnchorRanks();die;
            $this->view->applyBtn = $this->familyMgr->getFamilyStatus($uid);
        }

//        $familySkin = $this->config->familySkin;
//        if($familySkin){
//            foreach($familySkin as &$val){
//                $val['URL'] = $this->url->getStatic($val['URL']);
//                $val['smallURL'] = $this->url->getStatic($val['smallURL']);
//            }
//
//            $this->view->familySkin = $familySkin;
//        }else{
//            $this->view->familySkin = array();
//        }

    }

    /**
     * 获得家族皮肤默认列表
     *
     * @return mixed
     */

    public function getFamilySkinAction(){
        $familySkin = $this->config->familySkin;
        if($familySkin){
            foreach($familySkin as &$val){
                $val['backgroundImg'] = $this->url->getStatic($val['backgroundImg']);
                $val['smallBackgroundImg'] = $this->url->getStatic($val['smallBackgroundImg']);
            }
        }else{
            $familySkin = array();
        }

        return $this->status->ajaxReturn($this->status->getCode('OK'), $familySkin);
    }

    public function familyAction(){
        if ($this->request->isGet()) {
            $familyId = $this->request->get('familyid');
            if(!$familyId){
                $this->redirect('');
            }
            $familyResult = $this->familyMgr->getFamilyInfoNew($familyId);
            $creatorInfo = $this->familyMgr->getFamilyCreatorInfo($familyResult['data']['creatorUid']);
            if ($creatorInfo['code'] == $this->status->getCode('OK')) {
                $familyResult['data']['avatar'] = $creatorInfo['data']['avatar'];
            }
            if ($familyResult['code'] == $this->status->getCode('OK')) {
                $this->view->familyInfo = $familyResult['data'];
            }else{
                $this->view->familyInfo = array();
            }
            $user = $this->userAuth->getUser();
            if(!$user){
                $this->view->applyBtn = 0;
            }else{
                $this->view->applyBtn = $this->familyMgr->getAnchorStatus($user->getUid(), $familyId);
            }

            $result = $this->familyMgr->getFamilySkinInfo($familyId);
            if($result['code'] == $this->status->getCode('OK')){
                $familySkinInfo = $result['data'];
            }else{
                $familySkinInfo = array();
            }

            $this->view->familySkinInfo = $familySkinInfo;

            if($user){
                $result = $this->familyMgr->getFamilyInfoByUid($user->getUid());
                if($user->getUid() == $familyResult['data']['creatorUid']){
                    $this->view->familyCreator = 1;
                }else{
                    $this->view->familyCreator = 0;
                }
            }else{
                $this->view->familyCreator = 0;
            }
        }else{
            $this->redirect('');
        }
    }

    /**
     * 获得家族皮肤
     */
    public function getFamilySkinInfoAction(){
        if($this->request->isPost()){
            $fid = $this->request->getPost('fid');
            $result = $this->familyMgr->getFamilySkinInfo($fid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function createAction(){
        $this->view->familyStatus = $this->familyMgr->getFamilyApplyStatus();
        $this->view->swfUrl = $this->config->url->swfUrl;
    }

    public function uploadFamilyPosterAction(){
        if($this->request->isPost()){
            $fid = $this->request->getPost('fid');
            $result = $this->familyMgr->uploadNewFamilyPoster($fid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    function specificationAction(){

    }

    function hetAction(){

    }

}