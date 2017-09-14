<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-10
 * Time: 下午3:40
 */

class IOS{
    private $db;
    private $sdb;
    private $gameid = 5;
    private $bt;
    private $et;
    private $where_sf = '';
    private $where_role = '';
    private $where_paylog = '';
    private $where = ' WHERE gameid =? AND createtime >= ? AND createtime <= ?';

    public function __construct(PDO $db, PDO $sdb, $bt, $et, $serverid = array(),
                                $fenbaoid=array(),$gameid=5)
    {
        $this->db  = $db;
        $this->sdb = $sdb;

        $this->bt = $bt;
        $this->et = $et;

        $this->gameid = $gameid;

        $this->bt_timestamp = strtotime($bt);
        $this->et_timestamp = strtotime($et);
        $this->bt_ymdHi     = date('ymdHi', $this->bt_timestamp);
        $this->et_ymdHi     =date('ymdHi',  $this->et_timestamp);


        $lenServer = count($serverid);
        if ($lenServer==1) {
            $server = array_shift($serverid);
            $this->where_sf     .= " AND serverid=" .$server ;
            $this->where_role   .= " AND y.server_id=" .$server;
            $this->where_paylog .= " AND ServerID=" . $server;
        }
        elseif ($lenServer>1) {
            $server = implode(',', $serverid);
            $this->where_sf     .= " AND serverid IN(" .$server .')';
            $this->where_role   .= " AND y.server_id IN(" . $server.')';
            $this->where_paylog .= " AND ServerID IN(" . $server.')';
        }

        $lenFenbao = count($fenbaoid);
        if ($lenFenbao==1) {
            $fenbao =  array_shift($fenbaoid);
            $this->where_sf     .= " AND fenbaoid=" . $fenbao;
            $this->where_role   .= " AND p.fenbao=" . $fenbao;
            $this->where_paylog .= " AND dwFenBaoID=" . $fenbao;
        }
        elseif ($lenFenbao>1) {
            $fenbao = implode(',', $fenbaoid);
            $this->where_sf     .= " AND fenbaoid IN(" .$fenbao .')';
            $this->where_role   .= " AND p.fenbao IN(" .$fenbao.')';
            $this->where_paylog .= " AND dwFenBaoID IN(" .$fenbao.')';
        }
        $this->where .= $this->where_sf;
    }

    public function Player()
    {
        $data = array();
        //TODO:新增登录数(注册即登录)
        $sql_cnt_newlogin = <<<SQL
       SELECT fenbaoid, count(*) AS cnt FROM newmac
       {$this->where}
       GROUP BY fenbaoid
SQL;
        $stmt = $this->sdb->prepare($sql_cnt_newlogin);

        $stmt->execute( array($this->gameid, $this->bt_ymdHi, $this->et_ymdHi) );
        $lists_new_login = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($lists_new_login as $d) {
            $data[$d['fenbaoid']]['reg'] = $d['cnt'];
        }
        //获取新注册玩家id
        $sql = "SELECT accountid FROM newmac $this->where";
//        echo $sql;
        $stmtAccount = $this->sdb->prepare($sql);
        $stmtAccount->execute(array($this->gameid, $this->bt_ymdHi, $this->et_ymdHi));
        $accounts = implode(',', $stmtAccount->fetchAll(PDO::FETCH_COLUMN));

        //TODO:创建角色：在当前时间段内新创建的角色数
        $sql_cnt_role = <<<SQL
        SELECT count(DISTINCT account_id) as cnt,p.fenbao as fenbaoid
        FROM u_yreg_newbie y
        LEFT JOIN player p ON p.accountid=y.account_id
        WHERE time>={$this->bt_timestamp} AND time<={$this->et_timestamp}
        AND y.account_id IN($accounts) {$this->where_role}
        GROUP BY p.fenbao
SQL;

        $stmt = $this->sdb->prepare($sql_cnt_role);
        $stmt->execute();
        $lists_new_role = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($lists_new_role as $role) {
            $data[$role['fenbaoid']]['role']= $role['cnt'];
        }
        return $data;
    }

    /**
     * 支付统计
     * TODO:付费用户数(在当前时间段有提交过订单的用户数)
     * @return array
     */
    public function Pay()
    {
        $data = array();
        //payemoney记录的是元宝数，转换成rmb需要除以10.
        $sqlNew = <<<SQL
SELECT COUNT(accountid) as cnt,SUM(payemoney) AS payemoney,
fenbaoid FROM first_rmb
WHERE daytime>=? AND daytime<=? {$this->where_sf}
GROUP BY fenbaoid
SQL;

        $stmtFirst = $this->sdb->prepare($sqlNew);
        $stmtFirst->execute(array($this->bt_ymdHi, $this->et_ymdHi));
        $FirstRmb = $stmtFirst->fetchAll(PDO::FETCH_ASSOC);
        foreach ($FirstRmb as $rmb) {
//            $key = $rmb['serverid'].'_'.$rmb['fenbaoid'];
            $data[$rmb['fenbaoid']]['first_cnt'] = $rmb['cnt'];//首次充值人数
            $data[$rmb['fenbaoid']]['first_rmb'] = $rmb['payemoney']/10;//首次充值金额
        }
        //充值次数,充值总金额
        $sqlPayCnt = <<<SQL
    SELECT COUNT(id) AS cnt, COUNT(DISTINCT PayID) AS paynopall,
    dwFenBaoID AS fenbaoid,SUM(PayMoney) as sum_money
    FROM pay_log WHERE game_id=?
    AND Add_Time >= ? AND Add_Time <=?
    $this->where_paylog
    GROUP BY fenbaoid
SQL;
//        echo $sqlPayCnt;
        $stmt = $this->db->prepare($sqlPayCnt);
        $stmt->execute(array($this->gameid, $this->bt, $this->et));
        $payCntArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($payCntArr as $pay) {
            $data[$pay['fenbaoid']]['all_times'] = $pay['cnt'];//充值次数
            $data[$pay['fenbaoid']]['all_rmb']   = $pay['sum_money'];//充值金额
            $data[$pay['fenbaoid']]['all_cnt']   = $pay['paynopall'];//充值人数
        }
        return $data;
    }
} 