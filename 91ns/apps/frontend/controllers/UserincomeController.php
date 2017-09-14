<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserIncomeController extends UserController
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_type = 'userincome';
        }
    }

    public function indexAction()
    {
        $this->view->GMQQ = $this->config->GMConfig->QQNumber;
        $user = $this->userAuth->getUser();
        if($user == NULL){
            return $this->pageError();
        }
        // $this->view->money = $user->getUserInfoObject()->getData()['money'];
        /*$dataObj = array(
            'inFamily' => array(
                'thisWeek',
                'lastWeek',
                'thisMonth',
                'lastMonth',
                'total',
            ),
            'outFamily' => array(
                'thisWeek',
                'lastWeek',
                'thisMonth',
                'lastMonth',
                'total',
            ),
            'history' => array(
                'thisMonth',
                'lastMonth',
                'total',
            ),
        );

        foreach($dataObj as $key=>$val){
            foreach($val as $time){
                $result = $this->userMgr->getIncome($key, $time, 0, 10);
                if($result['code'] != $this->status->getCode('OK')){
                    return $this->pageError();
                }
                $data[$key][$time] = $result['data']['data'];
            }
        }*/

        //判断是否家族长
        $this->view->familyHeader = $this->userMgr->checkIsFamilyHeader();

        /*$startTime = strtotime(date('Y-m') . "-01");
        $endTime = time();
        $type = 'outFamily';
        $result = $this->userMgr->getIncome($type, $startTime, $endTime, 0, 10);
        if($result['code'] != $this->status->getCode('OK')){
            return $this->pageError();
        }
        $data[$type] = $result['data']['data'];
        // var_dump($data);
        // exit;

        $this->view->income = $data;*/
    }


    public function getIncomeDataAction(){
        if ($this->request->isPost()) {

            $type = $this->request->getPost('type');
            $startTime = $this->request->getPost('startTime');// ? strtotime($this->request->getPost('startTime')) : strtotime(date('Y-m') . "-01");
            $endTime = $this->request->getPost('endTime');// ? (strtotime($this->request->getPost('endTime')) + 86399) : time();
            if(!$startTime || !$endTime){
                $day = intval(date('d'));
                if($day >= 11){
                    $startTime = strtotime(date('Y-m-11',strtotime('this month')));
                    $endTime = time();
                }else{
                    $startTime = strtotime(date('Y-m-11',strtotime('-1 month', strtotime(date('Y-m-01')))));
                    $endTime = time();
                }
            }else{
                $startTime = strtotime($startTime);
                $endTime = strtotime($endTime) + 86399;
            }
            // $endTime = $this->request->getPost('endTime');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');

            $typeArr = array(
                'inFamily',
                'outFamily',
                'history',
            );
            $dataObj = array(
                'thisWeek',
                'lastWeek',
                'thisMonth',
                'lastMonth',
                'total',
            );
            if(in_array($type, $typeArr)){//&&in_array($time, $dataObj)
                // $result = $this->userMgr->getIncome($type, $time, $page, $pageSize);
                $result = $this->userMgr->getIncome($type, $startTime, $endTime, $page, $pageSize);
                $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        $this->proxyError();
    }

    public function getRoomLogAction(){
        if ($this->request->isPost()) {
            // $uid = $this->request->getPost('uid');
            // $uid = 10292;
            $startTime = $this->request->getPost('startTime');// ? $this->request->getPost('startTime') : date('Y-m');
            $endTime = $this->request->getPost('endTime');// ? $this->request->getPost('endTime') : date('Y-m-d',time());
            if(!$startTime || !$endTime){
                $day = intval(date('d'));
                if($day >= 11){
                    $startTime = date('Y-m-11',strtotime('this month'));
                    $endTime = date('Y-m-d',time());
                }else{
                    $startTime = date('Y-m-11',strtotime('-1 month', strtotime(date('Y-m-01'))));
                    $endTime = date('Y-m-d',time());
                }
            }else{
                $startTime = $startTime;
                $endTime = $endTime;
            }
            // $endTime = $this->request->getPost('endTime');
            // $endtime = time();
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->userMgr->getRoomLog($startTime,$endTime,$page,$pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        
    }

    /**
     * 获取礼物明细
     * @param $date
     */
    public function getDayRecvGiftsAction(){
        if($this->request->isPost()){
            $date = $this->request->getPost('date');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->userMgr->getDayRecvGifts($date, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取礼物收入
     * @param $date 日期为空或者如2015-07
     * @param $type 1-主播，2-家族长
     */
    public function getDayGiftsAction(){
        if($this->request->isPost()){
            $date = $this->request->getPost('date');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $type = $this->request->getPost('type');
            $result = $this->userMgr->getDayGiftsLog($date, $type, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取收益流水
     * @param $date 日期为空或者如2015-07
     * @param $type 1-主播，2-家族长，5-活动奖励
     */
    public function getDayIncomeAction(){
        if($this->request->isPost()){
            $date = $this->request->getPost('date');
            $type = $this->request->getPost('type');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->userMgr->getDayIncomeLog($date, $type, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取佣金
     * @param $date 日期为空或者如2015-07
     * @param $type 1-主播，2-家族长
     */
    public function getMonthIncomeAction(){
        if($this->request->isPost()){
            $date = $this->request->getPost('date');
            $type = $this->request->getPost('type');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->userMgr->getMonthIncomeLog($date, $type, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取交易明细
     * @param $startTime
     * @param $endTime
     */
    public function getChangeLogAction(){
        if($this->request->isPost()){
            $startTime = $this->request->getPost('startTime');
            $endTime = $this->request->getPost('endTime');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->userMgr->getChangeLog($startTime, $endTime, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取账户信息
     * @param $uid
     */
    public function getUserAccountAction(){
        $result = $this->userMgr->getUserAccount();
        if($result['code'] == $this->status->getCode('OK')){
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 提现
     * @param $money
     */
    public function addSettleLogAction(){
        if($this->request->isPost()){
            $money = $this->request->getPost('money');
            $type = $this->request->getPost('type');
            $result = $this->userMgr->addSettleLog($money, $type);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取交易详情
     * @param $id
     */
    public function getChangeDetailAction(){
        if($this->request->isPost()){
            $id = intval($this->request->getPost('id'));
            $result = $this->userMgr->getChangeDetail($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    } 

    /**
     * 获取概况信息
     */
    public function getBasicInfoAction(){
        if($this->request->isPost()){
            $result = $this->userMgr->getBasicInfo();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
    
       /**
     * 获取礼物收入
     * @param $date 日期为空或者如2015-07
     */
    public function getActivityIncomeAction(){
        if($this->request->isPost()){
            $date = $this->request->getPost('date');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->userMgr->getActivityIncomeDayLog($date, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
 

}