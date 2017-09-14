<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/28/14
 * Time: 10:16 AM
 */

class OnlineTest extends Analysis{
    public function SumPlayOnline()
    {
        //今天注册的玩家
        $sql_new = "SELECT accountid FROM newmac WHERE createtime>=? AND createtime<=?";
        $dbsmt = $this->_souce_db->prepare($sql_new);
        $dbsmt->execute(array(date('ymd0000', $this->timestamp),
            date('ymd2359', $this->timestamp)));
        $accountidsArr = $dbsmt->fetchAll(PDO::FETCH_COLUMN);
        //echo count($accountidsArr).PHP_EOL;
        if (!$accountidsArr) {
            writeLog('OK|Theres is NO New Player Exists.', LOG_PATH.'/db_sum_playeronline.log');
            return false;
        }
        //TODO:viplev是否为付费玩家->修正：total_rmb大于0为付费玩家
        $accountids = implode(',', $accountidsArr);
        // AND daytime>=? and daytime<=?
        $sql_q = <<<SQL
SELECT count(*) FROM dayonline
WHERE accountid IN($accountids) and daytime=? AND online>? ORDER BY NULL
SQL;

//        $sql_q = <<<SQL
//SELECT serverid,fenbaoid, `online`,total_rmb FROM dayonline
//WHERE accountid IN($accountids) and daytime=? AND online>? ORDER BY NULL
//SQL;
//        echo $sql_q;exit;
        $dbsmt_4 = $this->_souce_db->prepare($sql_q);
        $dbsmt_4->execute(array(
            date('1ymd', $this->timestamp),
            28800,
        ));
        $lists = $dbsmt_4->fetchAll(PDO::FETCH_COLUMN);
        print_r($lists);
        exit;
        $lists = $dbsmt_4->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        $players = array();

        //$sum_players = 0;

        $time_diff = array(0, 4, 10, 20, 30, 40, 50,
            60, 70, 80, 90,100,110,
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
        //$lvl = halfSearch($time_diff, 34);
        $length = count($time_diff);
        $total = 0;
        foreach($lists as $list) {
            $total += 1;
            //$sum_players += 1;
            //二分法查找出在线时间所属等级
            //online单位是秒,需要除以60转换为分钟
            $online_mins = ceil($list['online']/60);
            if ($online_mins>480) {
                $lvl = $length-1;
            }
            elseif ($online_mins==0) {
                $lvl = 0;
            }
            else {
                $lvl = halfSearch($time_diff, $online_mins);
            }
//            echo $lvl . '-----'.$online_mins . '-----------'.PHP_EOL;
            $key = $list['serverid'].'_'.$list['fenbaoid'].'_'.$lvl;
            if($list['total_rmb']>0) {
//                $total += 1;
                $players[$key]['rmb'] += 1;
            }
            else {
                $players[$key]['notrmb'] += 1;
            }
        }
        echo $total;
        //print_r($players);
//        exit;
        $daytime = $_SERVER['REQUEST_TIME'];
        foreach($players as $keys=>$player) {
            list($serverid, $fenbaoid, $lv) = explode('_', $keys);
            $data[] = array(
                'serverid'          => $serverid,
                'fenbaoid'          => $fenbaoid,
                'rmb'               => isset($player['rmb']) ? $player['rmb'] : 0,
                'not_rmb'           => $player['notrmb'],
                'player'            => $player['rmb'] + $player['notrmb'],
                'online_lvl'        => $lv,
                'online_lvl_txt'    => $lvl_list[$lv],
                'sday'              => $this->bt,
                'daytime'           => $daytime,
            );
        }
        if (count($data)) {
            $insert_values = array();
            foreach($data as $d){
                $question_marks[] = '('  . placeholders('?', sizeof($d)) . ')';
                $insert_values = array_merge($insert_values, array_values($d));
            }
            $sql = "INSERT INTO sum_playeronline (" .
                implode(",", array_keys($data[0]) ) .
                ") VALUES " . implode(',', $question_marks) .
                "ON DUPLICATE KEY UPDATE `sday`=VALUES(`sday`),".
                "`serverid`=VALUES(`serverid`),`fenbaoid`=VALUES(`fenbaoid`),".
                "`online_lvl`=VALUES(`online_lvl`)";
            $stmt = $this->_sum_db->prepare ($sql);
            try {
                $stmt->execute($insert_values);
                echo "rows count:" . $stmt->rowCount() . PHP_EOL;
            } catch (PDOException $e){
                echo $e->getMessage();
            }
        }
        else {
            echo "No Data.\n";
        }

    }
}