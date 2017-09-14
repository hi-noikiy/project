<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-16
 * Time: 下午1:57
 * 玩家信息
 */

class Player {

    private $db;
    private $gameid = 5;
    public function __construct(PDO $db, $gameid=5)
    {
        $this->db = $db;
        $this->gameid = $gameid;
    }
    public function Search($searchType=1, $searchVal)
    { $sql = "SELECT id, `NAME`, sex, login_date, reg_date,mac "
        ."dwFenBaoID, dwFenBaoUserID, limitType,fenbaomobile, "
        ."clienttype, last_log_time, channel_account,limitType FROM account"
        . " WHERE ";
        if($searchType==1){
            $sql .= '`NAME`=?';
        }else{
            $sql .= 'id=?';
        }
//        echo $sql;
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($searchVal));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
//        print_r($user);
        if (count($user)) {
            $vipSql = "SELECT points,vip,pointstotal FROM vippoints WHERE account_id=?";
            $stmt = $this->db->prepare($vipSql);
            $stmt->execute(array($user['id']));
            $vip = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return array('user'=>$user, 'vip'=>$vip);
    }

    /**
     * 充值金额、充值次数、最近充值时间
     *
     * @param PDO $db
     * @param $accountid
     * @return mixed
     */
    public function Pay(PDO $db, $accountid)
    {
        $sql = "SELECT SUM(PayMoney) AS PayMoney,MAX(Add_Time) AS Add_Time"
            .",COUNT(*) AS cnt FROM pay_log WHERE PayID=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($accountid));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 根据区服ID、玩家账号查找玩家角色名
     *
     * @param PDO $sdb
     * @param $accountid
     * @param $serverid
     * @return array
     */
    public function ShowUser(PDO $sdb,$accountid, $serverid)
    {
        $sql = "SELECT name,userid FROM player WHERE accountid=? AND serverid=?";
        $stmt = $sdb->prepare($sql);
        $stmt->execute(array($accountid, $serverid));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * 查看玩家充值金额
     *
     * @param $bt
     * @param $et
     * @param int $minMoney
     * @param array $fenbaoid
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function ShowPayRank($bt, $et, $minMoney=0, Array $fenbaoid=array(), Array $serverid=array())
    {
        $bt = $bt . ' 00:00:00';
        $et = $et . ' 23:59:59';
        $sql = <<<SQL
    SELECT PayID, PayName, ServerID, SUM(PayMoney) AS PayMoney, dwFenBaoID, game_id
    FROM pay_log
SQL;
//        $sqlTotal = "SELECT COUNT(*) FROM pay_log";
        $where = ' WHERE game_id=? AND Add_Time BETWEEN ? AND ? AND PayMoney>=?';
        if (count($serverid)) {
            $where .= " AND ServerID IN(".implode(',', $serverid).")";
        }
        if (count($fenbaoid)) {
            $where .= ' AND dwFenBaoID IN(' . implode(',', $fenbaoid).')';
        }
//        $sqlTotal .= $where . " GROUP BY ServerID";
////        echo $sqlTotal;
//        $stmt = $this->db->prepare($sqlTotal );
//        $stmt->execute(array($this->gameid,$bt, $et,$minMoney));
//        $totalArr = $stmt->fetchAll(PDO::FETCH_COLUMN);
//        $total = count($totalArr);
        $total = 1;
        $sql  .= $where . " GROUP BY ServerID,PayID ORDER BY PayMoney DESC";
        //echo $sql;
        $stmt = $this->db->prepare( $sql );
        $stmt->execute(array($this->gameid,$bt, $et,$minMoney ));
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        print_r($list);
        return array('total'=>$total, 'list'=>$list);
    }
    public function ShowPayRank1st($bt, $et)
    {
        $bt = $bt . ' 00:00:00';
        $et = $et . ' 23:59:59';
        $sql = <<<SQL
    SELECT PayID, ServerID, MAX(PayMoney) FROM (SELECT PayID, ServerID, SUM(PayMoney) AS PayMoney FROM pay_log WHERE Add_Time BETWEEN ? AND ? GROUP BY ServerID,PayID ORDER BY PayMoney DESC) t
SQL;
        $stmt = $this->db->prepare( $sql );
        $stmt->execute(array($bt, $et ));
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($list as $l) {

        }
        return array( 'list'=>$list);
    }
} 