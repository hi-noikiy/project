<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-4
 * Time: 上午9:02
 * 用户留存统计
 */

class UserRemain extends Analysis{

    /**
     * 统计昨天登录或注册的数量
     *
     * @param bool $cntLogin $true为统计登录数，false统计注册数
     * @throws Exception
     */
    public function SumLoginOrNew($cntLogin=false)
    {
        //$bt = $this->bt;
//        echo $bt;
        if ($cntLogin) {
            $tbname_sum = 'sum_login_daily_detail';
            $tbname_src = 'loginmac';
            $t_column = 'logintime=' . $this->bt;
        }
        else {
            $tbname_sum = 'sum_newuser_daily_detail';
            $tbname_src = 'newmac';
            $t_column = 'createtime>=' . date('ymd0000', $this->timestamp)
                . ' AND createtime<=' . date('ymd2359', $this->timestamp) ;
        }
        $sql_chk = "SELECT id FROM `{$tbname_sum}` WHERE `sday`=$this->bt LIMIT 1";
        $stmt = $this->_sum_db->prepare($sql_chk);
        $stmt->execute();
        if ($stmt->fetchAll(PDO::FETCH_COLUMN)) {
            throw new Exception("The data of `{$tbname_sum}` on [$this->bt] had added to the DATABASE.");
        }
        $sql = <<<SQL
          SELECT fenbaoid,serverid,count(*) as cnt
          FROM `$tbname_src`
          WHERE gameid=$this->gameid AND $t_column
          GROUP BY fenbaoid,serverid
SQL;
//        echo $sql;
//        exit;
        $q = $this->_souce_db->prepare($sql);
        $q->execute();
        $lists = $q->fetchAll(PDO::FETCH_ASSOC);
        $data_insert = array();
        foreach($lists as $list) {
            $data_insert[] = array(
                'fenbaoid'      => $list['fenbaoid'],
                'cnt'           => $list['cnt'],
                'gameid'        => $this->gameid,
                'serverid'      => $list['serverid'],
                'sday'          => $this->bt,
            );
        }
        if (!count($data_insert)) {
            echo date('Y-m-d H:i:s') . '|FAIL|NO DATA INSERT TO '.$tbname_sum . PHP_EOL;
            writeLog('FAIL|NO DATA INSERT TO  '.$tbname_sum, LOG_PATH.'/db_insert_sum_login_daily_detail.log');
            return false;
        }
        $rowCount = $this->Insert($data_insert, $tbname_sum);
        if(is_numeric($rowCount) && $rowCount>0) {
            echo date('Y-m-d H:i:s') . '|OK|Insert Into '.$tbname_sum.'|rowCount='.$rowCount . PHP_EOL;
            writeLog('OK|Insert Into '.$tbname_sum.'|rowCount='.$rowCount, LOG_PATH.'/db_insert_sum_login_daily_detail.log');
        }
        else {
            echo date('Y-m-d H:i:s') . '|FAIL|Insert Into '.$tbname_sum.'|MSG='.$rowCount . PHP_EOL;
            writeLog('FAIL|Insert Into '.$tbname_sum.'|MSG='.$rowCount, LOG_PATH.'/db_insert_sum_login_daily_detail.log');
        }

    }

    /**
     * 每日留存
     */
    public function remainDaily()
    {
        //次日留存数：当前新增的用户，在往后1天内至少登陆过一次的用户数
//        $timestamp = is_null($yestoday) ? strtotime('-1 days') :strtotime($yestoday);
        $yestoday = $this->bt;
        $strValue = $this->_getUserRemainValues($yestoday, $yestoday);

        if (strlen($strValue)) {
            $sql = <<<SQL
    INSERT INTO sum_reserveusers_daily(`sday`, `serverid`, `gameid`,`fenbaoid`, `usercount`)
    VALUES $strValue
    ON DUPLICATE KEY UPDATE usercount=VALUES(usercount)
SQL;
//            echo $sql;
            try{
                $rowCount = $this->_sum_db->exec($sql);
                if($rowCount!==false) {
                    echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount .'date=' .$yestoday. PHP_EOL;
                    writeLog('OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount.'date=' .$yestoday, LOG_PATH.'/sum_reserveusers_daily.log');
                }
                else {
                    echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_reserveusers_daily|sql='.$sql . 'date=' .$yestoday.PHP_EOL;
                    writeLog('FAIL|Insert Into sum_reserveusers_daily|sql='.$sql.'date=' .$yestoday, LOG_PATH.'/sum_reserveusers_daily.log');
                }
                echo 'rowCount:' . $rowCount . PHP_EOL;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        $dayList = array(1, 2, 3, 4, 5, 6, 7, 15, 30);
//        foreach ($dayList as $i) {
        foreach ( $dayList as $i){
            //if($i>8 && $i<15) continue;
            //if($i>15 && $i<30) continue;
            //bt = 20140520
            $tm   = strtotime("- $i days", $this->timestamp);
            $sday  = date('Ymd', $tm);//20140519,18
            $createTimeBegin = date('ymd0000', $tm);//19
            $createTimeEnd = date('ymd2359', $tm);//19
            $col = "day{$i}";//day8
            $loginTime = date('Ymd', strtotime("+$i days", $tm));//20,20

            $strValues = $this->loginAfterDays($createTimeBegin, $createTimeEnd, $loginTime, $sday);

            //exit;
            if (!strlen($strValues)) {
                continue;
            }
            $sql = <<<SQL
INSERT INTO sum_reserveusers_daily(`serverid`, `fenbaoid`, `gameid`, `sday`, `$col`)
VALUES $strValues
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
//            echo $sql;
            $rowCount = $this->_sum_db->exec($sql);
            if ($rowCount!==false) {
                echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount .'date=' .$sday. PHP_EOL;
                writeLog('OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount.'date=' .$sday, LOG_PATH.'/sum_reserveusers_daily_2.log');
            }
            else {
                echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_reserveusers_daily|sql='.$sql . 'date=' .$sday.PHP_EOL;
                writeLog('FAIL|Insert Into sum_reserveusers_daily|sql='.$sql.'date=' .$sday, LOG_PATH.'/sum_reserveusers_daily_2.log');
            }
        }
    }

    /**
     *
     *
     * @param $createTimeBegin
     * @param $createTimeEnd
     * @param $loginTime
     * @param $sday
     * @return string
     */
    public function loginAfterDays($createTimeBegin, $createTimeEnd, $loginTime, $sday)
    {
        //echo $createTimeBegin,'----',$createTimeEnd,PHP_EOL;
        $sql_cnt_newlogin = <<<SQL
       SELECT accountid
       FROM newmac WHERE gameid =?
       AND createtime >= ? AND createtime <= ?
SQL;
        //echo $sql_cnt_newlogin;
        $stmt = $this->_souce_db->prepare($sql_cnt_newlogin);
        $stmt->execute(array($this->gameid,$createTimeBegin, $createTimeEnd ));
        $accountIdArr = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!count($accountIdArr)) {
            echo 'NO DATA AT ' . $createTimeBegin . PHP_EOL;
            return false;
        }
        //echo count($accountIdArr);
        $accountid  = implode(',', $accountIdArr);

        $sql_login = " SELECT fenbaoid, serverid, count(*) AS cnt FROM loginmac WHERE logintime=$loginTime AND accountid IN({$accountid}) GROUP BY fenbaoid,serverid";
        $stmt = $this->_souce_db->prepare($sql_login);
        $stmt->execute();
        $lists_login = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        print_r($lists_login);
//        echo count($lists_login);
//        exit;
        $strValues = '';
        foreach ($lists_login as $d) {
            $strValues .= "({$d['serverid']}, {$d['fenbaoid']},{$this->gameid}, {$sday},{$d['cnt']}),";
        }
        $strValues = rtrim($strValues, ',');
        return $strValues;
    }
    /**
     * 新增登录
     */
    public function archiveNewLogin($t1=null, $t2=null, $t3=null, $t4=null, $ret=false)
    {
        $t1 = is_null($t1) ? date('ymd0000', $this->timestamp) : $t1;
        $t2 = is_null($t2) ? date('ymd2359', $this->timestamp) : $t2;
        $t3 = is_null($t3) ? date('Ymd', $this->timestamp) : $t3;
        $t4 = is_null($t4) ? $this->bt : $t4;
        //echo $t1 ,'-----', $t2,'------', $t3;
        $sql_cnt_newlogin = <<<SQL
       SELECT fenbaoid, serverid, count(*) AS cnt
       FROM newmac WHERE gameid =?
       AND createtime >= ? AND createtime <= ?
       GROUP BY fenbaoid,serverid
SQL;
        $stmt = $this->_souce_db->prepare($sql_cnt_newlogin);
        $stmt->execute(array($this->gameid,$t1, $t2 ));
        $lists_new_login = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $strValues = '';
        foreach ($lists_new_login as $d) {
            $strValues .= "({$d['serverid']}, {$d['fenbaoid']},{$this->gameid}, {$t4},{$d['cnt']}),";
        }
        if (!count($lists_new_login)) {
            echo date('Y-m-d H:i:s'). '|FAIL|NO DATA EXIST'. PHP_EOL;
            return false;
        }
        $strValues = rtrim($strValues, ',');
        if($ret) {
            return $strValues;
        }

        $sql = <<<SQL
INSERT INTO sum_reserveusers_daily(`serverid`, `fenbaoid`, `gameid`, `sday`, `newlogin`)
VALUES $strValues
ON DUPLICATE KEY UPDATE `newlogin`=VALUES(`newlogin`)
SQL;
//        echo $sql;
//        print_r($dataInsertDetail);
//        exit;
        $rowCount = $this->_sum_db->exec($sql);
        if($rowCount===false) {
            echo date('Y-m-d H:i:s')
                . '|FAIL|Insert Into sum_reserveusers_daily count newlogin|MSG='.$rowCount . PHP_EOL;
            writeLog('FAIL|Insert Into sum_reserveusers_daily count newlogin|MSG='.$rowCount,
                LOG_PATH.'/sum_au_daily.log');
        }
        else {
            echo date('Y-m-d H:i:s')
                . '|OK|Insert Into sum_reserveusers_daily count newlogin|rowCount='.$rowCount
                . PHP_EOL;
            writeLog('OK|Insert Into sum_reserveusers_daily count newlogin|rowCount='.$rowCount
                , LOG_PATH.'/sum_au_daily.log');
        }
    }

    /**
     * 统计活跃度
     */
    public function archiveAU()
    {
        //dau
        echo '=========DAU==========' . PHP_EOL;
        // $sql_cnt_login = <<<SQL
          // SELECT fenbaoid,serverid,count(DISTINCT accountid) as cnt
          // FROM palyerday
          // WHERE gameid=? AND day=?
          // GROUP BY fenbaoid,serverid
// SQL;
        $sql_cnt_login = <<<SQL
          SELECT fenbaoid,serverid,count(DISTINCT accountid) as cnt
          FROM loginmac
          WHERE gameid=? AND logintime=?
          GROUP BY fenbaoid,serverid
SQL;
        $stmt = $this->_souce_db->prepare($sql_cnt_login);
        $stmt->execute(array($this->gameid, $this->bt));
        $loginDauArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($loginDauArr)) {
            foreach ( $loginDauArr as $login ) {
                $insertData[$login['serverid'].'_'.$login['fenbaoid']]['dau']= $login['cnt'];
            }
        }


        //wau
        echo '=========WAU==========' . PHP_EOL;
        $week = date('w', $this->timestamp);
        // $cnt_login_w = "SELECT fenbaoid,serverid,count(DISTINCT accountid) as cnt"
            // ." FROM palyerday WHERE gameid=? AND day>=? AND day<=?"
            // ." GROUP BY fenbaoid,serverid";
         $cnt_login_w = "SELECT fenbaoid,serverid,count(DISTINCT accountid) as cnt"
            ." FROM loginmac WHERE gameid=? AND logintime>=? AND logintime<=?"
            ." GROUP BY fenbaoid,serverid";
        $week_bt = date('Ymd', strtotime("-$week days", $this->timestamp));
        $stmt = $this->_souce_db->prepare($cnt_login_w);
        $stmt->execute(array($this->gameid, $week_bt, $this->bt));
        $loginWauArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($loginWauArr)) {
            foreach ( $loginWauArr as $login ) {
                $insertData[$login['serverid'].'_'.$login['fenbaoid']]['wau']= $login['cnt'];
            }
        }


        //mau
        echo '=========MAU==========' . PHP_EOL;
        $month_bt = date('Ym01', $this->timestamp);
        $stmt = $this->_souce_db->prepare($cnt_login_w);
        $stmt->execute(array($this->gameid, $month_bt, $this->bt));
        $loginMauArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($loginMauArr)) {
            foreach ( $loginMauArr as $login ) {
                $insertData[$login['serverid'].'_'.$login['fenbaoid']]['mau']= $login['cnt'];
            }
        }


        $dataInsertDetail = array();
        $i = 0;
        foreach ($insertData as $key=>$val) {
            list($serverid, $fenbaoid) = explode('_', $key);
            if(count($val)<3) {
                continue;
            }
            $dataInsertDetail[$i] = array(
                'fenbaoid'=> $fenbaoid,
                'serverid'=> $serverid,
                'sday'    => $this->bt,
                'gameid'  => $this->gameid,
            );
            foreach($val as $k=>$v) {
                $dataInsertDetail[$i][$k] = $v;
            }
            $i += 1;
        }
        if (!count($dataInsertDetail)) {
            writeLog('OK|No Data to Insert|Insert Into sum_reserveusers_daily count AU|rowCount=0'
                , LOG_PATH.'/sum_au_daily.log');
            return;
        }
//        print_r($dataInsertDetail);exit;
        $strValues = '';
        foreach ($dataInsertDetail as $d) {
            $fenbaoid = $d['fenbaoid']? $d['fenbaoid'] : 0;
            $strValues .= "('{$d['serverid']}', '{$fenbaoid}','{$d['gameid']}', '{$d['sday']}','{$d['dau']}','{$d['wau']}','{$d['mau']}'),";
        }
        $strValues = rtrim($strValues, ',');
        $sql = <<<SQL
INSERT INTO sum_reserveusers_daily(`serverid`, `fenbaoid`, `gameid`, `sday`, `dau`, `wau`, `mau`)
VALUES $strValues
ON DUPLICATE KEY UPDATE `dau`=VALUES(`dau`),`wau`=VALUES(`wau`),`mau`=VALUES(`mau`)
SQL;
        echo $sql;
        //exit;
//        print_r($dataInsertDetail);
//        exit;
        $rowCount = $this->_sum_db->exec($sql);
        if(is_numeric($rowCount) && $rowCount>0) {
            echo date('Y-m-d H:i:s')
                . '|OK|Insert Into sum_reserveusers_daily count AU|rowCount='.$rowCount
                . PHP_EOL;
            writeLog('OK|Insert Into sum_reserveusers_daily count AU|rowCount='.$rowCount
                , LOG_PATH.'/sum_au_daily.log');
        }
        else {
            echo date('Y-m-d H:i:s')
                . '|FAIL|Insert Into sum_reserveusers_daily count AU|MSG='.$rowCount . PHP_EOL;
            writeLog('FAIL|Insert Into sum_reserveusers_daily count AU|MSG='.$rowCount,
                LOG_PATH.'/sum_au_daily.log');
        }

    }


    private function _getUserRemainValues($day, $day_cnt, $tbname='sum_newuser_daily_detail')
    {
        $sql_new_yestoday = "SELECT `serverid`, `gameid`, `fenbaoid`, `sday`, `cnt` as usercount FROM `$tbname` WHERE sday=$day_cnt AND gameid=".$this->gameid;
//        echo $sql_new_yestoday . PHP_EOL;
        $stmt = $this->_sum_db->prepare($sql_new_yestoday);
        $stmt->execute();
        $new_yestoday = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($new_yestoday)) {
            $strValue = '';
            foreach ($new_yestoday as $values) {
                $strValue .= "($day, {$values['serverid']},{$values['gameid']},{$values['fenbaoid']},{$values['usercount']}),";
            }
            return rtrim($strValue,',');
        }
        return '';
    }
}