<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-5
 * Time: 上午8:57
 */

class Archive extends Base{
    /**
     * 总数据统计
     */
    public function run()
    {
//        $bt_timestamp = strtotime(date('Y-m-d 00:00:00', $this->timestamp));
//        $et_timestamp = strtotime(date('Y-m-d 23:59:59', $this->timestamp));

        $loginCnt = $regCnt = $nlCnt = $auCnt = $payCnt = 0;
        $incomeCnt = 0;//总收入
        $payNOPAll = 0;//付费人数
        $payNOPNew = 0; //新增付费人数
        $payNOPNewMoney = 0;
        $roleCnt = 0;//创建数
        $cntDau  = 0;
        $cntWau  = 0;
        $cntMau  = 0;
        $insertData = array();


        //TODO:登录数，（在当前时间段至少登录过一次的用户数），从留存表获取dau
        //TODO:统计新增登录用户数(在当前时间段新注册并登录应用的用户数)
        //TODO:活跃用户数（登录数-新增用户数）
        //TODO:活跃度
        echo '----login-----'.PHP_EOL;
        $sql_cnt_login = <<<SQL
      SELECT serverid,fenbaoid,dau,wau,mau,newlogin FROM sum_reserveusers_daily
      WHERE gameid={$this->gameid} AND sday={$this->sday}
SQL;
//        echo $sql_cnt_login;
        $stmt = $this->_sum_db->prepare($sql_cnt_login);
        $stmt->execute();//array($this->gameid, $this->sday)
        $lists_login = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ( $lists_login as $login ) {
            $key  =$login['serverid'].'_'.$login['fenbaoid'];
            $au = $login['dau'] - $login['newlogin'];

            $loginCnt += $login['dau'];
            $nlCnt  += $login['newlogin'];
            $auCnt  += $au;
            $cntDau += $login['dau'];
            $cntWau += $login['wau'];
            $cntMau += $login['mau'];

            $insertData[$key]['nl_cnt'] = $login['newlogin'];//新增登录
            $insertData[$key]['au_cnt'] = $au;//活跃用户数
            $insertData[$key]['dau']    = $login['dau'];//dau
            $insertData[$key]['wau']    = $login['wau'];//arpu
            $insertData[$key]['mau']    = $login['mau'];//arpu
        }
        echo '_________AU____' . PHP_EOL;
       // print_r($insertData);
        echo '_________AU End____' . PHP_EOL;
        //TODO:统计注册数,(注册数：在当前时间段内新注册的用户数)
        //print_r($insertData);
        echo '_________reg End____' . PHP_EOL;

        //TODO:创建角色：在当前时间段内新创建的角色数
        echo '------role------' . PHP_EOL;
        $sql_cnt_role = <<<SQL
        SELECT SUM(sum_new) AS sum_new,SUM(sum_cre) AS sum_cre,`serverid`, `fenbaoid`
        FROM sum_reg_trans WHERE sday=?
        GROUP BY `serverid`, `fenbaoid`
        ORDER BY NULL
SQL;
//        $sql_cnt_role = <<<SQL
//SELECT y.server_id as serverid,count(*) as cnt,p.fenbao as fenbaoid
//FROM u_yreg_newbie y LEFT JOIN player p
//ON p.userid=y.player_id AND p.serverid=y.server_id
//WHERE y.time>=? AND y.time<=? GROUP BY y.server_id, p.fenbao ORDER BY NULL
//SQL;
//        $stmt = $this->_souce_db->prepare($sql_cnt_role);
//        $stmt->execute(array( $bt_timestamp, $et_timestamp));
        $stmt = $this->_sum_db->prepare($sql_cnt_role);
        $stmt->execute(array( $this->sday ));
        $lists_new_role = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($lists_new_role as $role) {
            $roleCnt += $role['sum_cre'];
            $key = $role['serverid'].'_'.$role['fenbaoid'];
//            $data_new_role[$role['serverid'].'_'.$role['fenbaoid']] = $role['cnt'];
            $insertData[$key]['role_cnt']  = $role['sum_cre'];
            //$insertData[$key]['role_rate'] = $role['sum_new']>0 ? round($role['sum_cre']/$role['sum_new'], 4) * 100 : 0;

//            if ($insertData[$key]['nl_cnt']>0) {
//                $insertData[$key]['role_rate']= $role['cnt']/$insertData[$key]['nl_cnt'];
//            }
//            else {
//                $insertData[$key]['role_rate']= 0;
//            }
        }
        //print_r($insertData);
        //exit;
        //TODO:付费用户数(在当前时间段有提交过订单的用户数)
        //if (0) {
        $sqlPay = "SELECT `serverid`, `gameid`, `fenbaoid`,"
            ."`paynopall`, `paynopnew`,`paynopnew_money`, `paycnt`, `income`, `arpu` FROM sum_pay_daily WHERE sday=?";
        $stmt = $this->_sum_db->prepare($sqlPay);
        $stmt->execute(array($this->sday));
        $payArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //print_r($payArr);

        //充值Arpu：充值金额/充值人数
        //注册Arpu：充值金额/注册数
        foreach ($payArr as $pay) {
            $key = $pay['serverid'] . '_' . $pay['fenbaoid'];
            $incomeCnt += $pay['income'];
            $payNOPAll += $pay['paynopall'];
            $payNOPNew += $pay['paynopnew'];
            $payNOPNewMoney    += $pay['paynopnew_money'];
            $payCnt     += $pay['paycnt'];

            $insertData[$key]['pay_nop']   = $pay['paynopall'];//付费人数
            $insertData[$key]['pay_nop_n'] = $pay['paynopnew'];//新增付费人数
            $insertData[$key]['pay_nop_nm'] = $pay['paynopnew_money'];//新增付费人数
            $insertData[$key]['pay_cnt']    = $pay['paycnt'];//付费次数
            $insertData[$key]['income_cnt']    = $pay['income'];//收入
//            $insertData[$key]['arpu']      =  $pay['arpu'];//充值arpu
//            $insertData[$key]['reg_arpu']  = $insertData[$key]['reg']>0 ? $pay['income']/$insertData[$key]['reg'] : 0;//注册Arpu
            //TODO:付费率：付费用户数/活跃用户数
            //$insertData[$key]['pay_rate']  = $insertData[$key]['au']>0  ? $pay['paynopall']/$insertData[$key]['au'] : 0;//付费率
        }
        //}
        $dataInsertDetail = array();
        $i = 0;
        foreach ($insertData as $key=>$val) {
            list($serverid,$fenbaoid) = explode('_', $key);
            $dataInsertDetail[$i] = array(
                'fenbaoid'=> $fenbaoid,
                'serverid'=> $serverid,
                'sday'    => $this->sday,
            );
            foreach($val as $k=>$v) {
                $dataInsertDetail[$i][$k] = $v;
            }
            $i += 1;
        }
//        print_r($insertData);return;
        $dataInsertAll = array();
        $dataInsertAll[0] = array(
            'login_cnt'   => $loginCnt,//注册数
            'nl_cnt'      => $nlCnt,//新增登录
            'role_cnt'    => $roleCnt,//创建数
            //'role_rate'   => $roleCnt / $nlCnt,//创建率
            'au_cnt'      => $auCnt,//活跃用户数
            'dau'         => $cntDau,
            'wau'         => $cntWau,
            'mau'         => $cntMau,
            'income_cnt'  => $incomeCnt,//总收入,充值金额
            'pay_nop'     => $payNOPAll,//充值人数
            'pay_nop_n'   => $payNOPNew,//新增充值人数
            'pay_nop_nm'  => $payNOPNewMoney,//新增充值人数
            'pay_cnt'     => $payCnt,//充值次数
            //'pay_rate'    => $payNOPAll/$auCnt,//总的付费率：付费用户数/活跃用户数
            'reg_cnt'     => $regCnt,//注册数
//            'arpu'        => $incomeCnt / $payCnt,//arpu:总收入/付费用户数
//            'arpdau'      => $incomeCnt / $auCnt,//arpdau:总收入/活跃用户数
//            'reg_arpu'    => $incomeCnt / $regCnt,//注册apru
            'sday'        => $this->sday,
        );
//        print_r($dataInsertAll);
//        return;
//        exit;
        $rowCount = $this->Insert($dataInsertAll, 'sum_daily_archive_all');
        if(is_numeric($rowCount) && $rowCount>0) {
            echo date('Y-m-d H:i:s')
                . '|OK|Insert Into sum_daily_archive_all|rowCount='.$rowCount. PHP_EOL;
            writeLog(date('Y-m-d H:i:s')
                . '|OK|Insert Into sum_daily_archive_all|rowCount='.$rowCount, LOG_PATH.'/sum_daily_archive.log');
        }
        else {
            echo date('Y-m-d H:i:s')
                . '|FAIL|Insert Into sum_daily_archive|MSG='.$rowCount . PHP_EOL;
            writeLog('FAIL|Insert Into sum_daily_archive|MSG='.$rowCount,
                LOG_PATH.'/sum_daily_archive.log');
        }
//        return;
        $rowCount = $this->Insert($dataInsertDetail, 'sum_daily_archive');
        if(is_numeric($rowCount) && $rowCount>0) {
            echo date('Y-m-d H:i:s')
                . '|OK|Insert Into sum_daily_archive_detail|rowCount='.$rowCount. PHP_EOL;
            writeLog('|OK|Insert Into sum_daily_archive_detail|rowCount='.$rowCount, LOG_PATH.'/sum_daily_archive_detail.log');
        }
        else {
            echo date('Y-m-d H:i:s')
                . '|FAIL|Insert Into sum_daily_archive_detail|MSG='.$rowCount . PHP_EOL;
            writeLog('FAIL|Insert Into sum_daily_archive_detail|MSG='.$rowCount,
                LOG_PATH.'/sum_daily_archive_detail.log');
        }
    }
} 