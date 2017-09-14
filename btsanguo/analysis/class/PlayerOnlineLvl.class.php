<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-10
 * Time: 下午3:40
 * palyeronline表的数据，不能出现重复，否则无法统计。
 */

class PlayerOnlineLvl extends Base{
    /**
     *
     * 统计玩家在线时长
     * (每天新增注册的在线数据),online单位是秒。
     * 统计的是角色数，因为不同区服不同，玩家的角色不同！
     *
     */
    public function run()
    {
        //今天注册的玩家
        $sql_new = "SELECT accountid FROM newmac WHERE createtime>=? AND createtime<=?";
        $dbsmt = $this->_souce_db->prepare($sql_new);
        $dbsmt->execute(array(date('ymd0000', $this->timestamp),
            date('ymd2359', $this->timestamp)));
        $accountidsArr = $dbsmt->fetchAll(PDO::FETCH_COLUMN);
        echo count($accountidsArr).PHP_EOL;
        if (!$accountidsArr) {
            writeLog('OK|Theres is NO New Player Exists.', LOG_PATH.'/db_sum_playeronline.log');
            return false;
        }
        //TODO:viplev是否为付费玩家->修正：total_rmb大于0为付费玩家
        //$sum_players = 0;
        $timeLvlArea = array(0, 4, 10, 20, 30, 40, 50,
            60, 70, 80, 90, 100, 110,
            120,240, 300, 360, 420, 480 );
        //时间等级
        $lvl_list = array(
            '0-4','5-10', '11-20', '21-30',
            '31-40',  '41-50', '51-60',
            '61-70', '71-80','81-90',
            '91-100','101-110','111-120',
            '121-240','241-300','301-360',
            '361-420', '421-480', '>=481',
        );
        $nowstamp = $_SERVER['REQUEST_TIME'];
        $daytime = date('1ymd', $this->timestamp);
        //echo $daytime;
        $len = count($timeLvlArea);
        $accountids = implode(',', $accountidsArr);
        echo '--account id length=', count($accountidsArr), PHP_EOL;
//        $sql_tmp = "SELECT COUNT(DISTINCT userid) FROM palyeronline WHERE accountid IN($accountids) and total_rmb>0";
//        $sql_tmp = "SELECT COUNT(*) FROM dayonline WHERE accountid IN($accountids) and total_rmb>0";
//        $dbsmt_4    = $this->_souce_db->prepare($sql_tmp);
//        $dbsmt_4->execute();
//        echo 'rmb count=',$dbsmt_4->fetchColumn(0);
//        exit;
      $sql_q = <<<SQL
SELECT serverid,fenbaoid,online,accountid,total_rmb FROM dayonline
WHERE accountid IN($accountids) ORDER BY NULL
SQL;
//        echo $sql_q . PHP_EOL;
        $dbsmt_4    = $this->_souce_db->prepare($sql_q);
        $dbsmt_4->execute();
        $lists      = $dbsmt_4->fetchAll(PDO::FETCH_ASSOC);
        echo '$lists length=',count($lists) , PHP_EOL;
//        $strinsert  = '';
        $data  = array();
        if (!count($lists)) {
            return false;
        }
//        $rmb = $notrmb = 0;
        foreach ($lists as $list) {
            $onlineMins = ceil($list['online']/60);
            if ($onlineMins>480) {
                $lvl = $len-1;
            }
            elseif ($onlineMins==0) {
                $lvl = 0;
            }
            else {
                $lvl = halfSearch($timeLvlArea, $onlineMins);
            }
//            if (!$lvl) {
//                echo PHP_EOL, $lvl,'-------',$onlineMins;
//            }
            $key = $list['serverid'].'_'.$list['fenbaoid'].'_'.$lvl;
            if ($list['total_rmb']>0) {
                $data[$key]['rmb'] += 1;
            }
            else {

                $data[$key]['notrmb'] += 1;
            }
        }

//        print_r($data);
//        return;
        $insdata = array();
        foreach($data as $keys=>$player) {
            list($serverid, $fenbaoid, $lv) = explode('_', $keys);
//            $notrmb += $player['notrmb'];
//            $rmb += $player['rmb'];
//            if (!$lvl_list[$lv]) {
//                echo PHP_EOL, $lv,'-------',$key;
//            }
            $insdata[] = array(
                'serverid'          => $serverid,
                'fenbaoid'          => $fenbaoid,
                'rmb'               => isset($player['rmb']) ? $player['rmb'] : 0,
                'not_rmb'           => isset($player['notrmb']) ? $player['notrmb'] : 0,
                'player'            => $player['rmb'] + $player['notrmb'],
                'online_lvl'        => $lv,
                'online_lvl_txt'    => $lvl_list[$lv],
                'sday'              => $this->sday,
                'daytime'           => $nowstamp,
            );
//            $strinsert .= "({$this->sday},{$serverid},{$fenbaoid},"
//                ."{$cnt['rmb']},{$cnt['not_rmb']},{$lv}),";
        }
//        echo PHP_EOL . 'rmb=' .$rmb . ',notrmb=' . $notrmb;
//        echo PHP_EOL .'$insdata length=' . count($insdata);
//        exit;
        $rowCount = $this->Insert($insdata, 'sum_playeronline');
//        $rowCount = 0;
        if(is_numeric($rowCount) && $rowCount>0) {
            echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_rmbused|rowCount='.$rowCount . PHP_EOL;
            writeLog('OK|Insert Into sum_playeronline|rowCount='
                .$rowCount.'date='
                .$this->sday, LOG_PATH.'/sum_playeronline.log');
        }
        else {
            writeLog('FAIL|Insert Into sum_playeronline|msg='.
                $rowCount.'date='
                .$this->sday, LOG_PATH.'sum_playeronline.log');

        }
//        $strinsert = rtrim($strinsert, ',');
//        $sql_insert = <<<SQL
//    INSERT INTO sum_playeronline(sday,serverid,fenbaoid,rmb,not_rmb,online_lvl)
//    VALUES $strinsert
//SQL;
////        echo $sql_insert . PHP_EOL;
//        $rowCount = $this->_sum_db->exec($sql_insert);
//        if($rowCount===false) {
//            echo date('Y-m-d H:i:s')
//                . '|FAIL|Insert Into sum_playeronline|msg='
//                .$rowCount .'date=' .$this->sday. PHP_EOL;
//            writeLog('FAIL|Insert Into sum_playeronline|msg='.
//                $rowCount.'date='
//                .$this->sday, LOG_PATH.'sum_playeronline.log');
//        }
//        else {
//            writeLog('OK|Insert Into sum_playeronline|rowCount='
//                .$rowCount.'date='
//                .$this->sday, LOG_PATH.'/sum_playeronline.log');
//        }
    }
} 