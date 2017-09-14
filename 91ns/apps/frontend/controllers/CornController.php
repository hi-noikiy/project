<?php

namespace Micro\Controllers;

use Phalcon\Logger\Adapter\File as FileLogger;
/**
* 计划任务控制器
*/
class CornController extends ControllerBase
{
    protected $type;
    protected $logTotal;
    protected $logData;
    protected $logError;

    // 收集粉丝的列表信息
    public function collectFansListAction() {
        $beginTime = strtotime('-1 years');
        $endTime = time();
        $result = $this->cornMgr->getAllFansList($beginTime, $endTime);
        var_dump($result);die;
    }

    /*
     * bat调用接口
     * */
    public function batAction(){
        $req_url = $this->request->getHttpHost().$this->request->getURI();
        if(substr($req_url,0, 9) != '127.0.0.1'){
            $this->log('forbid --- batActio url ');die;
        }
        $this->type = $this->request->get('type');
        $this->logTotal = new FileLogger("{$this->config->directory->logsDir}/corn/total.log");
        $this->logData = new FileLogger("{$this->config->directory->logsDir}/corn/{$this->type}.log");
        $this->logError = new FileLogger("{$this->config->directory->logsDir}/corn/{$this->type}.log");

        $this->log("---------------------------Corn gen ::{$this->type}------------------------begin------------");
        $this->runAction();
        $this->log("---------------------------Corn gen ::{$this->type}------------------------end-------------");
        die;
    }

    private function runAction(){
        switch($this->type){
            case 'hours':
                $this->genFamilyRank();//家族数据（每个家族的总收益排行）  

                break;
            case 'day':
                //礼物日结算流水表1433865600 86400
                // $result = $this->cornMgr->getDayGifts(1433865600);
                // $this->saveLog($result, "getDayGifts()");

                $this->genRank('day');//日榜，每小时
                $this->genRank('week');//周榜，每周
                $this->genRank('month');//月榜，每月
                //$this->genRank('total');//总榜，每天
                //热门礼物取消计划任务 改成后台配置 by 2015/07/07.....//////////
                // $this->getHotGiftRank();//直播间热门礼物，每天
                $this->sendCarNotice();//座驾过期通知
                $this->sendVipNotice();//vip过期通知
                $this->sendGuardNotice();//守护过期通知               

                $result = $this->cornMgr->getDayGifts();
                $this->saveLog($result, "getDayGifts()");

                // 周星定时任务
                $result = $this->cornMgr->getGiftStar();
                $this->saveLog($result, "getGiftStar()");
                
                 // 推荐活动定时任务
                $result = $this->cornMgr->recIncome();
                $this->saveLog($result, "recIncome()");
                
                break;
            case 'week':
                //$this->genRank('week');//周榜，每周

                //礼物之星
                $result = $this->cornMgr->getFirstGiftRank('lastWeek');
                $this->saveLog($result, "getFirstGiftRank('week')");

                break;
            case 'month':
                //$this->genRank('month');//月榜，每月
                break;
            case '30_minutes':
                //$this->checkLivestatus();//重置直播状态，半小时
                break;
            case '10_minutes':
                //$this->syncRoomNumberOfPeople();//房间人数
                //$this->checkLivestatus();//重置直播状态，半小时
                break;
            case 'monitor':
                $this->addMonitorLog();//监控日志
                //$this->genFamilysRank('week');//家族数据（每个家族的总收益排行）
                break;
            // case 'day_every':
            //     $result = $this->cornMgr->getDayGifts();
            //     $this->saveLog($result, "getDayGifts()");
            case 'day_11':
                $result = $this->cornMgr->getDayIncomeLog();//die;
                $this->saveLog($result, "getDayIncome()");
                 $result = $this->cornMgr->getActivityDayIncomeLog();//活动奖励聊币流水
                $this->saveLog($result, "getActivityDayIncome()");
                break;
            case 'day_12':
                $result = $this->cornMgr->getMonthIncomeLog();
                $this->saveLog($result, "getMonthIncomeLog()");
                break;
            default:
                $this->log('undefined type');
                break;
        }
    }

    //生成家族列表
    private function genFamilyRank(){
        $result = $this->cornMgr->getFamilyRank();
        $this->saveLog($result, 'genFamilyRank');
    }

    //房间人数同步
    private function syncRoomNumberOfPeople(){
        $result = $this->cornMgr->syncRoomNumberOfPeople();
        $this->saveLog($result, 'syncRoomNumberOfPeople');
    }

    /*
     * 生成排行榜数据
     * */
    private function genRank($time){
        //明星排行
        $result = $this->cornMgr->genStarRank($time);
        $this->saveLog($result, "genStarRank({$time})");

        //富豪排行
        $result = $this->cornMgr->getRichRank($time);
        $this->saveLog($result, "getRichRank({$time})");

        /*//人气排行
        $result = $this->cornMgr->getFansRank($time);
        $this->saveLog($result, "getFansRank({$time})");*/

        //魅力星排行
        $result = $this->cornMgr->getCharmRank($time);
        $this->saveLog($result, "getCharmRank({$time})");
        
        //家族排行
        $result = $this->cornMgr->getFamilysRank($time);
        $this->saveLog($result, "getFamilysRank({$time})");

        //每小时
        if($time == 'day'){
            //家族列表
            $result = $this->cornMgr->getFamilyListByConsume($time);
            $this->saveLog($result, "getFamilyListByConsume({$time})");

            //礼物之星
            $result = $this->cornMgr->getFirstGiftRank('thisWeek');
            $this->saveLog($result, "getFirstGiftRank({$time})");
        }

        /*if($time == 'total'){
            //礼物之星
            $result = $this->cornMgr->getFirstGiftRank('thisWeek');
            $this->saveLog($result, "getFirstGiftRank({$time})");
        }*/

        //每周
        /*if($time == 'week'){
            //礼物之星
            $result = $this->cornMgr->getFirstGiftRank('lastWeek');
            $this->saveLog($result, "getFirstGiftRank({$time})");

            //上周观众最多主播
            $result = $this->cornMgr->getLastWeekVisitorRankAnchor();
            $this->saveLog($result, "getLastWeekVisitorRankAnchor({$time})");

            //上周最强家族
            $result = $this->cornMgr->getFamilyListByConsume($time);
            $this->saveLog($result, "getFamilyListByConsume({$time})");

            //上周魅力最强
            $result = $this->cornMgr->getLastWeekFollowRankAnchor();
            $this->saveLog($result, "getLastWeekFollowRankAnchor({$time})");
        }*/
    }

    //检查当前直播状态，如果超时，则重置
    private function checkLivestatus(){
        $result = $this->cornMgr->checkRoomsLiveStatus();
        $this->saveLog($result, "checkLivestatus");
    }

    //近30天赠送率排名前20的礼物
    private function getHotGiftRank(){
         //热门礼物取消计划任务 改成后台配置 by 2015/07/07.....//////////
        return;
       // $result = $this->cornMgr->getHotGiftData();
       // $this->saveLog($result, 'getHotGiftRank');
    }
    
    //发送座驾过期通知
    private function sendCarNotice(){
        $result = $this->cornMgr->sendExpiredCarInfo();
        $this->saveLog($result, 'sendCarNotice');
    }
    
    //发送vip过期通知
    private function sendVipNotice() {
        $result = $this->cornMgr->sendExpiredVipInfo();
        $this->saveLog($result, 'sendVipNotice');
    }

    //发送守护过期通知
    private function sendGuardNotice() {
        $result = $this->cornMgr->sendExpiredGuardInfo();
        $this->saveLog($result, 'sendGuardNotice');
    }

    protected function log($info) {
        $log = "{$this->type}:::{$info}";
        $this->logTotal->error($log);
        echo "{$log}</br>";
    }
    protected function saveLog($result, $type) {
        if($result['code'] == $this->status->getCode('OK')){
            $this->log('true '.$type);
            if(!empty($result['data']))$this->logData->error("{$type}:::{$result['data']}");
        }else{
            $this->log('false '.$type);
            if(!empty($result['data']))$this->logError->error("{$type}:::{$result['data']}");
        }
    }

    //监控日志
    private function addMonitorLog(){
        $data = file_get_contents("http://streammonitor.fastcdn.com:5922/rtmp?app=B21F9AFC3DF4DEA00FA975063F8712E0");
        
        if(!empty($data)){
            $data_decode = json_decode($data, true);
            $data_result = isset($data_decode['result']) ? $data_decode['result'] : array();
            $logtime = time();

            
            /*foreach ($data_result['datavalue'] as $k => $val) {
                $MonitorDB = new \Micro\Models\MonitorLog();
                $MonitorDB->streamname = $val['streamname'];
                $MonitorDB->inbandwidth = $val['inbandwidth'];
                $MonitorDB->lfr = $val['lfr'];
                $MonitorDB->fps = $val['fps'];
                $MonitorDB->deployaddress = $val['deployaddress'];
                $MonitorDB->inaddress = $val['inaddress'];
                $MonitorDB->bandwidth = $val['bandwidth'];
                $MonitorDB->hists = $val['hists'];
                $MonitorDB->logtime = $logtime;
                $MonitorDB->save();
            }*/

            $sql = "insert into inv_monitor_log(streamname,inbandwidth,lfr,fps,deployaddress,inaddress,bandwidth,hists,logtime) values ";
            $insert_sql = '';
            foreach ($data_result['datavalue'] as $k => $val) {
                if(empty($val['deployaddress'])) continue;//发布点为空跳出
                $insert_sql .= "(";
                $insert_sql .= "'" . $val['streamname']."',";
                $insert_sql .= $val['inbandwidth'].",";
                $insert_sql .= $val['lfr'].",";
                $insert_sql .= $val['fps'].",";
                $insert_sql .= "'" . $val['deployaddress']."',";
                $insert_sql .= "'" . $val['inaddress']."',";
                $insert_sql .= $val['bandwidth'].",";
                $insert_sql .= $val['hists'].",";
                $insert_sql .= $logtime;
                $insert_sql .= "),";
            }

            if($insert_sql){
                $full_sql = $sql . substr($insert_sql, 0, -1);
                // $query = $this->modelsManager->createQuery($full_sql);
                // $records = $query->execute();
                $connection = $this->di->get('db');
                $timeResult = $connection->execute($full_sql);
            }
        }
    }
}