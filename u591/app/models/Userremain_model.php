<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/2/21
 * Time: 10:06
 *
 * 用户留存模型
 */
include_once __DIR__ . '/base_model.php';
class userremain_model extends Base_model
{
    public function load()
    {
        $this->load->model('register_model');
        $this->register_model->init($this->appid, $this->bt, $this->et);
    }


    /**
     * N前注册的人在X天后的登录情况
     *
     * @param int $ctb 角色创建开始时间-时间戳
     * @param int $cte 角色创建结束时间
     * @param int $loginDate 登录日期
     * @param int $sday 记录日期
     * @return array|bool
     */
    public function loginAfterDays($ctb, $cte, $loginDate, $sday)
    {
        //获取N天前注册的人
//        $this->load->model('register_model');
        $this->register_model->init($this->appid, $ctb, $cte);
        $accountIdArr = $this->register_model->roles_create_account();

        if (!count($accountIdArr)) {
            echo 'NO DATA BWTEEEN ' .date('Y-m-d H:i:s', $ctb) . ' and ' .date('Y-m-d H:i:s',$cte). PHP_EOL;
            return false;
        }
        //print_r($accountIdArr);
        //exit;
        //$table_name='u_register', $accountid=null, $bt=0, $et=0)
        $login_date_bt = strtotime($loginDate .' 00:00:00');
        $login_date_et = strtotime($loginDate .' 23:59:59');
        $data   = $this->register_model->total_register_or_login(
            register_model::TBL_LOGIN,
            $accountIdArr,
            $login_date_bt,
            $login_date_et
        );
        //echo $sday,'---',$loginDate,':total_register_or_login:',PHP_EOL;
        //print_r($data);
        //echo  $sday,'---',$loginDate,':total_register_or_login；',PHP_EOL;
//        $insertData = array();
        $strValues  = '';
        if (count($data)) {
            foreach ($data as $d) {
                $strValues .= "({$d['serverid']}, {$d['channel']},'{$this->appid}', {$sday},{$d['cnt']}),";
            }
            return rtrim($strValues, ',');
        }
        return false;
    }

    public function _getUserRemainValues($day, $day_cnt)
    {
        /*$sql = <<<SQL
SELECT `serverid`, `appid`, `channel`, date as `sday`, `cnt`
 FROM `sum_register_day` WHERE appid={$this->appid} AND date=$day_cnt
SQL;
        $sql = <<<SQL
SELECT `serverid`, `appid`, `channel`, date as `sday`,`cnt`
 FROM `sum_newrole_day` WHERE appid={$this->appid} AND date=$day_cnt
SQL;*/
        //2016-09-19:留存统计原来是通过u_roles来统计新增角色数量，现在要改成从u_players来统计
        $sql = <<<SQL
SELECT `serverid`, `appid`, `channel`, date as `sday`,`cnt`
 FROM `sum_register_day` WHERE appid={$this->appid} AND date=$day_cnt
SQL;
        echo $sql . PHP_EOL;
        $query = $this->db1->query($sql);
        $new_yestoday = $query->result_array();
        if (count($new_yestoday)) {
            $strValue = '';
            foreach ($new_yestoday as $values) {
                $strValue .= "($day, {$values['serverid']},'{$this->appid}',{$values['channel']},{$values['cnt']}),";
            }
            return rtrim($strValue,',');
        }
        return '';
    }
    /**
     * 每日留存    sum_reserveusers_daily 此表不用，已有 remainDailyNew,
     */
    public function remainDaily()
    {   return true;
    /*
        log_message('info', 'Running remainDaily');
        //次日留存数：当前新增的用户，在往后1天内至少登陆过一次的用户数
//        $timestamp = is_null($yestoday) ? strtotime('-1 days') :strtotime($yestoday);
        $yestoday = date('Ymd', $this->bt);
        $strValue = $this->_getUserRemainValues($yestoday, $yestoday);

        if (strlen($strValue)) {
            $sql = <<<SQL
    INSERT INTO sum_reserveusers_daily(`sday`, `serverid`, `appid`,`channel`, `usercount`)
    VALUES $strValue
    ON DUPLICATE KEY UPDATE usercount=VALUES(usercount)
SQL;
            echo $sql;
            $this->db1->query($sql);
            $rowCount = $this->db1->affected_rows();
            if($rowCount!==false) {
                echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount .'date=' .$yestoday. PHP_EOL;
                log_message('info', 'OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount.'date=' .$yestoday);
            }
            else {
                echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_reserveusers_daily|sql='.$sql . 'date=' .$yestoday.PHP_EOL;
                log_message('info','FAIL|Insert Into sum_reserveusers_daily|sql='.$sql.'date=' .$yestoday);
            }
            echo 'rowCount:' . $rowCount . PHP_EOL;

        }
        $dayList = array(1, 2, 3, 4, 5, 6, 7, 15, 30);
//        foreach ($dayList as $i) {
        foreach ( $dayList as $i){
            //if($i>8 && $i<15) continue;
            //if($i>15 && $i<30) continue;
            //bt = 20140520
            $day_idx = $i;
            if ($i>1) $day_idx = $i-1;
            $tm              = strtotime("- $day_idx days", $this->bt);
            $sday            = date('Ymd', $tm);//20140519,18
            $createTimeBegin = strtotime(date('Y-m-d 00:00:00', $tm));//19
            $createTimeEnd   = strtotime(date('Y-m-d 23:59:59', $tm));//19
            $col             = "day{$i}";
            $loginDate       = date('Ymd', strtotime("+$day_idx days", $tm));//20,20
            echo '<br/>','sday=', $sday,';$loginDate=', $loginDate,"\n";
            $strValues       = $this->loginAfterDays($createTimeBegin, $createTimeEnd, $loginDate, $sday);
            //exit;
            if (!strlen($strValues)) {
                continue;
            }
            $sql = <<<SQL
INSERT INTO sum_reserveusers_daily(`serverid`, `channel`, `appid`, `sday`, `$col`)
VALUES $strValues
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
            //echo $sql,'<br/>';
            $this->db1->query($sql);
            $rowCount = $this->db1->affected_rows();
            if ($rowCount!==false) {
                echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount .'date=' .$sday. PHP_EOL;
                log_message('info', 'OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount.'date=' .$sday);
            }
            else {
                echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_reserveusers_daily|sql='.$sql . 'date=' .$sday.PHP_EOL;
                log_message('info', 'FAIL|Insert Into sum_reserveusers_daily|sql='.$sql.'date=' .$sday);
            }
        }
        */
        return true;
    }
    /**
     * N前注册的人在X天后的登录情况 新
     *
     * @param int $ctb 角色创建开始时间-时间戳
     * @param int $cte 角色创建结束时间
     * @param int $loginDate 登录日期
     * @param int $sday 记录日期
     * @return array|bool
     * @author 王涛 20170112
     */
    public function loginAfterDaysNew($ctb, $cte, $loginDate, $sday)
    {
    	//获取N天前注册的人
    	//$Ym = substr($loginDate, 0,6);
   echo 	$sql = "select count(*) as cnt,channel,appid from (SELECT accountid,channel,appid FROM u_register WHERE reg_date=$sday) a,(SELECT accountid FROM u_login_{$loginDate}) b
    	 where  a.accountid = b.accountid group by channel";
    	$query = $this->db_sdk->query($sql);
    	$data = array();
    	if($query){
    		$data = $query->result_array();
    	}
    	if (count($data)) {
    		foreach ($data as $d) {
    			$strValues .= "( {$d['channel']},'{$d['appid']}', {$sday},{$d['cnt']}),";
    		}
    		return rtrim($strValues, ',');
    	}
    	return false;
    }
    /*public function loginAfterDaysNew($ctb, $cte, $loginDate, $sday)
    {
    	//获取N天前注册的人
    	//        $this->load->model('register_model');
    	$this->register_model->init($this->appid, $ctb, $cte);
    	$accountIdArr = $this->register_model->roles_create_account();
    	if (!count($accountIdArr)) {
    		echo 'NO DATA BWTEEEN ' .date('Y-m-d H:i:s', $ctb) . ' and ' .date('Y-m-d H:i:s',$cte). PHP_EOL;
    		return false;
    	}
    	//print_r($accountIdArr);
    	//exit;
    	//$table_name='u_register', $accountid=null, $bt=0, $et=0)
    	$login_date_bt = strtotime($loginDate .' 00:00:00');
    	$login_date_et = strtotime($loginDate .' 23:59:59');
    	$data   = $this->register_model->total_login(
    			'u_login_'.$loginDate,
    			$accountIdArr,
    			$login_date_bt,
    			$login_date_et
    	);
    	//echo $sday,'---',$loginDate,':total_register_or_login:',PHP_EOL;
    	//print_r($data);
    	//echo  $sday,'---',$loginDate,':total_register_or_login；',PHP_EOL;
    	//        $insertData = array();
    	$strValues  = '';
    	if (count($data)) {
    		foreach ($data as $d) {
    			$strValues .= "( {$d['channel']},'{$this->appid}', {$sday},{$d['cnt']}),";
    		}
    		return rtrim($strValues, ',');
    	}
    	return false;
    }*/
    public function _getUserRemainValuesNew($day, $day_cnt)
    {
    	/*$sql = <<<SQL
    	 SELECT `serverid`, `appid`, `channel`, date as `sday`, `cnt`
    	 FROM `sum_register_day` WHERE appid={$this->appid} AND date=$day_cnt
    	 SQL;
    	 $sql = <<<SQL
    	 SELECT `serverid`, `appid`, `channel`, date as `sday`,`cnt`
    	 FROM `sum_newrole_day` WHERE appid={$this->appid} AND date=$day_cnt
    	 SQL;*/
    	//2016-09-19:留存统计原来是通过u_roles来统计新增角色数量，现在要改成从u_players来统计
    	
    	$sql = <<<SQL
SELECT  `appid`, `channel`, date as `sday`,sum(`cnt`) cnt
 FROM `sum_register_day` WHERE appid={$this->appid} AND date=$day_cnt group by channel
SQL;
    	echo $sql . PHP_EOL;
    	$query = $this->db1->query($sql);
    	if($query)$new_yestoday = $query->result_array();
    	
    	$sql = <<<SQL
SELECT  `appid`, 0 as channel, date as `sday`,sum(`cnt`) cnt
 FROM `sum_register_day` WHERE appid={$this->appid} AND date=$day_cnt
SQL;
    	echo $sql . PHP_EOL;
    	$query1 = $this->db1->query($sql);
    	if($query1)$new_yestoday = array_merge($new_yestoday,$query1->result_array());
    	if (count($new_yestoday)) {
    		$strValue = '';
    		foreach ($new_yestoday as $values) {
    			$strValue .= "($day, '{$this->appid}',{$values['channel']},{$values['cnt']}),";
    		}
    		return rtrim($strValue,',');
    	}
    	return '';
    }
    /**
     * 新每日留存
     * 
     * @author 王涛 20170112
     */
    public function remainDailyNew()
    {
    	log_message('info', 'Running remainDailyNew');
    	//次日留存数：当前新增的用户，在往后1天内至少登陆过一次的用户数
    	//        $timestamp = is_null($yestoday) ? strtotime('-1 days') :strtotime($yestoday);
    	$yestoday = date('Ymd', $this->bt);
    	$strValue = $this->_getUserRemainValuesNew($yestoday, $yestoday);

    	if (strlen($strValue)) {
    		$sql = <<<SQL
    INSERT INTO sum_reserveusers_daily_new(`sday`, `appid`,`channel`, `usercount`)
    VALUES $strValue
    ON DUPLICATE KEY UPDATE usercount=VALUES(usercount)
SQL;
    		echo $sql;
    		$this->db1->query($sql);
    		$rowCount = $this->db1->affected_rows();
    		if($rowCount!==false) {
    			echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_reserveusers_daily_new|rowCount='.$rowCount .'date=' .$yestoday. PHP_EOL;
    			log_message('info', 'OK|Insert Into sum_reserveusers_daily_new|rowCount='.$rowCount.'date=' .$yestoday);
    		}
    		else {
    			echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_reserveusers_daily_new|sql='.$sql . 'date=' .$yestoday.PHP_EOL;
    			log_message('info','FAIL|Insert Into sum_reserveusers_daily_new|sql='.$sql.'date=' .$yestoday);
    		}
    		echo 'rowCount:' . $rowCount . PHP_EOL;
    
    	}
    	//$dayList = array(1, 2, 3, 4, 5, 6, 7, 15, 30);
    	$dayList = array(1, 2, 3, 4, 5, 6, 7,8,9,10,11,12,13,14,15, 30);
    	//        foreach ($dayList as $i) {
    	foreach ( $dayList as $i){
    		//if($i>8 && $i<15) continue;
    		//if($i>15 && $i<30) continue;
    		//bt = 20140520
    		$day_idx = $i;
    		if ($i>1) $day_idx = $i-1;
    		$tm              = strtotime("- $day_idx days", $this->bt);
    		$sday            = date('Ymd', $tm);//20140519,18
    		$createTimeBegin = strtotime(date('Y-m-d 00:00:00', $tm));//19
    		$createTimeEnd   = strtotime(date('Y-m-d 23:59:59', $tm));//19
    		$col             = "day{$i}";
    		$loginDate       = date('Ymd', strtotime("+$day_idx days", $tm));//20,20
    		echo '<br/>','sday=', $sday,';$loginDate=', $loginDate,"\n";
    		$strValues       = $this->loginAfterDaysNew($createTimeBegin, $createTimeEnd, $loginDate, $sday);
    		//exit;
    		if (!strlen($strValues)) {
    			continue;
    		}
    		$sql = <<<SQL
INSERT INTO sum_reserveusers_daily_new( `channel`, `appid`, `sday`, `$col`)
VALUES $strValues
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
    		//echo $sql,'<br/>';
    		$this->db1->query($sql);
    		$rowCount = $this->db1->affected_rows();
    		if ($rowCount!==false) {
    			echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_reserveusers_daily_new|rowCount='.$rowCount .'date=' .$sday. PHP_EOL;
    			log_message('info', 'OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount.'date=' .$sday);
    		}
    		else {
    			echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_reserveusers_daily_new|sql='.$sql . 'date=' .$sday.PHP_EOL;
    			log_message('info', 'FAIL|Insert Into sum_reserveusers_daily_new|sql='.$sql.'date=' .$sday);
    		}
    	}
    	return true;
    }

    public function _getUserRemainValuesAd($day, $day_cnt)
    {
    	$Y = substr($day_cnt, 0,6);
    	$sql = <<<SQL
SELECT  count(*) cnt,media_source as channel,logdate as `sday`
 FROM `ad_login_{$Y}` WHERE  logdate=$day_cnt group by media_source
SQL;
    	echo $sql . PHP_EOL;
    	$query = $this->db_sdk->query($sql);
    	if($query)$new_yestoday = $query->result_array();
    	
    	if (count($new_yestoday)) {
    		$strValue = '';
    		foreach ($new_yestoday as $values) {
    			$strValue .= "($day, '{$values['channel']}',{$values['cnt']}),";
    		}
    		return rtrim($strValue,',');
    	}
    	return '';
    }
    /**
     * N前注册的人在X天后的登录情况 新
     * 
     * @author 王涛 20170912
     */
    public function loginAfterDaysAd($ctb, $cte, $loginDate, $sday)
    {
    	$Y = substr($loginDate, 0,6);
    	$sql = "select count(*) as cnt,media_source as channel from (SELECT accountid,media_source FROM ad_register WHERE logdate=$sday) a,(SELECT accountid FROM ad_login_{$Y} 
    	WHERE logdate=$loginDate) b where  a.accountid = b.accountid group by media_source";
    	$query = $this->db_sdk->query($sql);
    	$data = array();
    	if($query){
    		$data = $query->result_array();
    	}
    	if (count($data)) {
    		foreach ($data as $d) {
    			$strValues .= "( '{$d['channel']}', {$sday},{$d['cnt']}),";
    		}
    		return rtrim($strValues, ',');
    	}
    	return false;
    }
    /**
     * 渠道每日留存
     *
     * @author 王涛 20170912
     */
    public function remainDailyAd()
    {
    	log_message('info', 'Running remainDailyAd');
    	//次日留存数：当前新增的用户，在往后1天内至少登陆过一次的用户数
    	//        $timestamp = is_null($yestoday) ? strtotime('-1 days') :strtotime($yestoday);
    	$yestoday = date('Ymd', $this->bt);
    	$strValue = $this->_getUserRemainValuesAd($yestoday, $yestoday);
    //插入登录数
    	if (strlen($strValue)) {
    		$sql = <<<SQL
    INSERT INTO sum_reserveusers_daily_ad(`sday`, `channel`, `usercount`)
    VALUES $strValue
    ON DUPLICATE KEY UPDATE usercount=VALUES(usercount)
SQL;
    		echo $sql;
    		$this->db1->query($sql);
    		$rowCount = $this->db1->affected_rows();
    		if($rowCount!==false) {
    			echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_reserveusers_daily_ad|rowCount='.$rowCount .'date=' .$yestoday. PHP_EOL;
    			log_message('info', 'OK|Insert Into sum_reserveusers_daily_ad|rowCount='.$rowCount.'date=' .$yestoday);
    		}
    		else {
    			echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_reserveusers_daily_ad|sql='.$sql . 'date=' .$yestoday.PHP_EOL;
    			log_message('info','FAIL|Insert Into sum_reserveusers_daily_ad|sql='.$sql.'date=' .$yestoday);
    		}
    		echo 'rowCount:' . $rowCount . PHP_EOL;
    
    	}
    	//插入注册数
    	$sql = <<<SQL
    SELECT count(*) as registernum,media_source as channel,logdate as sday FROM ad_register WHERE logdate=$yestoday group by media_source
SQL;
    	$query = $this->db_sdk->query($sql);
    	if($query){
    		$this->insert_batch('sum_reserveusers_daily_ad', $query->result_array(),$this->db1);
    	}
    	$dayList = array(1, 2, 3, 4, 5, 6, 7, 15, 30);
    	foreach ( $dayList as $i){
    		$day_idx = $i;
    		if ($i>1) $day_idx = $i-1;
    		$tm              = strtotime("- $day_idx days", $this->bt);
    		$sday            = date('Ymd', $tm);//20140519,18
    		$createTimeBegin = strtotime(date('Y-m-d 00:00:00', $tm));//19
    		$createTimeEnd   = strtotime(date('Y-m-d 23:59:59', $tm));//19
    		$col             = "day{$i}";
    		$loginDate       = date('Ymd', strtotime("+$day_idx days", $tm));//20,20
    		echo '<br/>','sday=', $sday,';$loginDate=', $loginDate,"\n";
    		$strValues       = $this->loginAfterDaysAd($createTimeBegin, $createTimeEnd, $loginDate, $sday);
    		//exit;
    		if (!strlen($strValues)) {
    			continue;
    		}
    		$sql = <<<SQL
INSERT INTO sum_reserveusers_daily_ad( `channel`,  `sday`, `$col`)
VALUES $strValues
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
    		echo $sql,'<br/>';
    		$this->db1->query($sql);
    		$rowCount = $this->db1->affected_rows();
    		if ($rowCount!==false) {
    			echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_reserveusers_daily_ad|rowCount='.$rowCount .'date=' .$sday. PHP_EOL;
    			log_message('info', 'OK|Insert Into sum_reserveusers_daily_ad|rowCount='.$rowCount.'date=' .$sday);
    		}
    		else {
    			echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_reserveusers_daily_ad|sql='.$sql . 'date=' .$sday.PHP_EOL;
    			log_message('info', 'FAIL|Insert Into sum_reserveusers_daily_ad|sql='.$sql.'date=' .$sday);
    		}
    	}
    	return true;
    }
    
    public function insert_batch($table,$savedatas,$db='')
    {
    	if($savedatas){
    		$this->data_multi = $savedatas;
    	}
    	$sql = "insert into $table(".implode(',', array_keys($this->data_multi[0])).") values";
    	foreach ($this->data_multi as $key=>$value){
    		//$sql .= "(".implode(',', array_values($value))."),";
    		$sql .= "(".$this->implode_new(',', array_values($value))."),";
    	}
    	//$result = $this->db->query();
    	$msql = rtrim($sql,',') . " ON DUPLICATE KEY UPDATE ";
    	foreach ($this->data_multi[0] as $k=>$v){
    		$msql .= "$k=values($k),";
    	}
    	if(!$db){
    		$db = $this->load->database('sdk',true);
    	}
    	$result = $db->query(rtrim($msql,','));
    	if($result){
    		return true;
    	}
    	return false;
    }
    function implode_new($sp , $data){
    	$str = '';
    	foreach ($data as $v){
    		$str .= ( preg_match('/^\d+$/i', $v)?$v:"'{$v}'")."$sp";
    	}
    	return rtrim($str,"$sp");
    }
}