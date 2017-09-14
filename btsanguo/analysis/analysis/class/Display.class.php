<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-27
 * Time: 下午1:53
 */

class Display {
    public $_db;
    public $gameid;
    public $bt;
    public $et;
    public function __construct(PDO $db, $gameid=5, $bt=null, $et=null)
    {
//        $this->_db->exec("SET NAMES 'utf8';");
        $this->_db = $db;
        $this->gameid = $gameid;
        $this->bt = is_null($bt) ? date('Ymd', strtotime('-7 days')) :date('Ymd', strtotime($bt));
        $this->et = is_null($et) ? date('Ymd', strtotime('-1 days')) :date('Ymd', strtotime($et));
    }

    /**
     * 在线时长统计
     *
     * @param int $serverid
     * @param int $fenbaoid
     * @return array
     */
    public function ShowSumPlayOnline( $serverid=0, $fenbaoid=0)
    {
        $where = " WHERE `sday`={$this->bt}";

        $lenServer = count($serverid);
        if ($lenServer==1) {
            $where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $where .= " AND serverid IN(" . implode(',', $serverid).')';
        }
        $lenFenbao = count($fenbaoid);
        if ($lenFenbao==1) {
            $where .= " AND fenbaoid=" . array_shift($fenbaoid);
        }
        elseif ($lenFenbao>1) {
            $where .= " AND fenbaoid IN(" . implode(',', $fenbaoid).')';
        }
        $sql = "SELECT id, serverid, fenbaoid, sum(rmb) as rmb, sum(not_rmb) as not_rmb,"
            ." sum(player) as player,online_lvl,online_lvl_txt, `sday`, daytime FROM sum_playeronline "
            .$where
            ." GROUP BY online_lvl ORDER BY online_lvl ASC";
//        echo $sql;
        $q = $this->_db->prepare($sql);
        $q->execute();
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        $ret  = array();
        foreach ($data as $d) {
            $ret[$d['online_lvl']] = $d;
        }

        $sql_sum = "SELECT SUM(rmb)+sum(not_rmb) AS players FROM sum_playeronline ".$where;
//        echo $sql_sum;
        $q = $this->_db->prepare($sql_sum);
        $q->execute();
        $sum_player = $q->fetchColumn(0);
        return array('list'=>$ret, 'total'=>$sum_player);
    }


    /**
     * 元宝消耗统计
     *
     * @param Array $serverid
     * @param Array $fenbaoid
     * @param int $limit
     * @param int $offset
     * @param bool $isCnt
     * @return array
     * @throws Exception
     */
    public function ShowSumRmbUse(Array $serverid=array(), Array $fenbaoid=array(),
                                  $limit=0, $offset=10, $isCnt=false)
    {
        $where = ' WHERE 1=1';

        if ($this->bt==$this->et) {
            $where .= " AND `sday`={$this->bt}";
        }
        else {
            $where .= " AND (`sday`>={$this->bt} AND `sday` <= {$this->et})";
        }
        $lenServer = count($serverid);
        if ($lenServer==1) {
            $where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $where .= " AND serverid IN(" . implode(',', $serverid).')';
        }
        $lenFenbao = count($fenbaoid);
        if ($lenFenbao==1) {
            $where .= " AND fenbaoid=" . array_shift($fenbaoid);
        }
        elseif ($lenFenbao>1) {
            $where .= " AND fenbaoid IN(" . implode(',', $fenbaoid).')';
        }

        //计算总数
        $sql_cnt ="SELECT COUNT(*) FROM sum_rmbused"
            . $where .' GROUP BY `sday`';
//        echo $sql_cnt;
        $cnt = $this->_db->prepare($sql_cnt);
        $cnt->execute();
        $total = $cnt->fetchAll(PDO::FETCH_COLUMN);

        $sql = <<<SQL
    SELECT `sday`,SUM(rmb_sum) AS rmb_sum,SUM(rmb_pay) AS rmb_pay,
    SUM(rmb_sys) AS rmb_sys,SUM(rmb_used) AS rmb_used,SUM(cnt) as cnt,
    SUM(rmb_left) AS rmb_left
    FROM sum_rmbused
    $where
    GROUP BY `sday`
    ORDER BY `sday` DESC
    LIMIT $limit, $offset
SQL;
       // echo $sql;
        $q = $this->_db->prepare($sql);
        $q->execute();
        return array(
            'total'=>count($total),
            'list'=>$q->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     *
     * 消费
     *
     * @param $type 消费类型（1：消费行为，2：商城消费）
     * @param Array $serverid
     * @param Array $fenbaoid
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function ShowMarketPay($type, Array $serverid=array(),
                                  Array $fenbaoid=array(),
                                  $limit=0, $offset=20)
    {
//        return false;
        $where = ' WHERE 1=1';

        if ($this->bt==$this->et) {
            $where .= " AND `sday`={$this->bt}";
        }
        else {
            $where .= " AND (`sday`>={$this->bt} AND `sday` <= {$this->et})";
        }
        $lenServer = count($serverid);
        if ($lenServer==1) {
            $where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $where .= " AND serverid IN(" . implode(',', $serverid).')';
        }
        $lenFenbao = count($fenbaoid);
        if ($lenFenbao==1) {
            $where .= " AND fenbaoid=" . array_shift($fenbaoid);
        }
        elseif ($lenFenbao>1) {
            $where .= " AND fenbaoid IN(" . implode(',', $fenbaoid).')';
        }
        $group = ' GROUP BY ';
        if ($type===Analysis::ConsumptionMarket) {
            $table_name = 'sum_market_pay';
            $column = 'itemtype';
            $group .= 'itemtype';

            $sql = <<<SQL
      SELECT sday, SUM(sum_emoney) AS sum_emoney, `$column`, SUM(ratio) AS sum_ratio
      FROM `$table_name`
      $where $group ORDER BY `sday` ASC,sum_emoney DESC
      LIMIT $limit, $offset
SQL;
        }
        elseif ($type===Analysis::ConsumptionBehavior) {
            $table_name = 'sum_behavior_pay';
            $column = 'stype';
            $group = 'stype';

            $sql = <<<SQL
      SELECT SUM(sum_emoney) AS sum_emoney, `$column`, SUM(ratio) AS sum_ratio
      FROM `$table_name`
      $where $group ORDER BY `stype` ASC
      LIMIT $limit, $offset
SQL;
        }
        //计算总数
        $sql_cnt_1 = "SELECT COUNT(*) as cnt FROM `$table_name`" . $where ." $group";
//        echo $sql_cnt_1;
        $cnt = $this->_db->prepare($sql_cnt_1);
        $cnt->execute();
        $cnt_arr = $cnt->fetchAll(PDO::FETCH_COLUMN);

         echo $sql;
        $q = $this->_db->prepare($sql);
        $q->execute();

        $sql_total_emoney = "SELECT SUM(sum_emoney) FROM `$table_name`" . $where;
//        echo $sql_total_emoney;
        try{
            $qt = $this->_db->prepare($sql_total_emoney);
            $qt->execute();
            $total_emoney = $qt->fetchAll(PDO::FETCH_COLUMN);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        return array(
            'total'=>count($cnt_arr),
            'lists'=>$q->fetchAll(PDO::FETCH_ASSOC),
            'total_emoney'=>$total_emoney[0]
        );
    }


    /**
     * 获取道具名称
     *
     * @return array
     */
    public static function GetVipGoods($db)
    {
        $sql ="SELECT id, `name` FROM u_vipgoods";
        $q = $db->prepare($sql);
        $q->execute();
        $goods = $q->fetchAll(PDO::FETCH_ASSOC);
        $output = array();
        foreach($goods as $good) {
            $output[$good['id']] = $good['name'];
        }
        return $output;
    }

    /**
     * 获取消费类型
     *
     * @return array
     */
    public function GetEmoneyTypes()
    {
        $sql ="SELECT `type`,`type_name` FROM emoney_type";
        $q = $this->_db->prepare($sql);
        $q->execute();
        $goods = $q->fetchAll(PDO::FETCH_ASSOC);
        $output = array();
        foreach($goods as $good) {
            $output[$good['type']] = $good['type_name'];
        }
        return $output;
    }

    /**
     * 用户信息
     *
     * @param PDO $db
     * @param $bt
     * @param $et
     * @param int $serverid
     * @param int $fenbaoid
     * @param int $limit
     * @param int $offset
     * @param int $gameid
     * @return array|string
     * @throws Exception
     */
    public function PlayerInfo(PDO $db, $bt, $et, $serverid=0, Array $fenbaoid=array(),
                               $limit=0, $offset=40, $gameid=5)
    {

        $tsb = strtotime($bt);
        $tse = strtotime($et);
        $tdf = ($tse-$tsb)/60/60/24 ;

        if ($tdf>100) {
            throw new Exception('时间范围超出！不能超出100天~');
        }
        $time_query_begin = date('ymdHi', $tsb);
        $time_query_end   = date('ymdHi', $tse);
        $where = "WHERE `gameid`=$gameid AND createtime>=$time_query_begin AND createtime<=$time_query_end";

        $lenServer = count($serverid);
        if ($lenServer==1) {
            $where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $where .= " AND serverid IN(" . implode(',', $serverid).')';
        }
        $lenFenbao = count($fenbaoid);
        if ($lenFenbao==1) {
            $where .= " AND fenbaoid=" . array_shift($fenbaoid);
        }
        elseif ($lenFenbao>1) {
            $where .= " AND fenbaoid IN(" . implode(',', $fenbaoid).')';
        }
        //TODO::统计行数
        $sqlCnt = "SELECT COUNT(*) FROM newmac {$where}";
//            echo($sqlCnt);
        $stmt = $db->prepare($sqlCnt);
        $stmt->execute();
        $total = $stmt->fetchColumn();
        if (!$total) {
            return array();
        }
        $sqlNewMac = <<<SQL
        SELECT accountid,createtime,ip,mac,clienttype,fenbaoid,serverid
        FROM newmac {$where}
        LIMIT $limit,$offset
SQL;
//                echo $sqlNewMac;
        $q = $db->prepare($sqlNewMac);
        $q->execute();
        $listMacs = $q->fetchAll(PDO::FETCH_ASSOC);
        $newMacArr = array();
        foreach ($listMacs as $list) {
            $newMacArr[$list['accountid']] = $list;
            //$newMacArr[$list['accountid']]['createtime'] = $list['createtime'];
        }

        $newMacAccount = implode(',', array_keys($newMacArr));
        //p.serverid,p.fenbao as fenbaoid,
        $sqlPlayer = <<<SQL
   SELECT p.`name`, p.userid, p.prof, p.accountid,
   MAX(l.logintime) AS logintime,l.ip FROM player AS p
   LEFT JOIN loginmac AS l ON l.accountid=p.accountid
   WHERE p.accountid IN($newMacAccount)
   GROUP BY l.accountid
SQL;
//            echo $sqlPlayer;
        $q2 = $db->prepare($sqlPlayer);
        $q2->execute();
        $listPlayers = $q2->fetchAll(PDO::FETCH_ASSOC);
        foreach ($listPlayers as $player) {
            $newMacArr[$player['accountid']]['name'] = iconv('gb2312', 'utf-8', $player['name']);
            $newMacArr[$player['accountid']]['userid'] = $player['userid'];
            $newMacArr[$player['accountid']]['prof'] = $player['prof'];
//            $newMacArr[$player['accountid']]['serverid'] = $player['serverid'];
//            $newMacArr[$player['accountid']]['fenbaoid'] = $player['fenbaoid'];
            $newMacArr[$player['accountid']]['logintime'] = $player['logintime'];
            $newMacArr[$player['accountid']]['loginip'] = $player['ip'];
        }
        return array( 'total'=> $total, 'list'=> $newMacArr);
    }

    public function PlayerSearch(PDO $db, $accountid, $username, $serverid='', 
        $tbname=array('player'=>'player','newmac'=>'newmac','loginmac'=>'loginmac'))
    {
        $sqlPlayer = <<<SQL
   SELECT `name`, userid, prof, serverid,accountid,fenbao as fenbaoid FROM {$tbname['player']}
   WHERE 1=1
SQL;
        $search = array();
        if (!is_null($username)) {
            //echo $username;
            $sqlPlayer .= " AND `name` ='" . iconv( 'UTF-8', 'GBK', $username)."'";
            // $sqlPlayer .= " AND `name`='" .$username."'";
        }
        if ($accountid>0) {
            $sqlPlayer .= " AND accountid=?";
            $search[] = $accountid;
        }
        if(is_array($serverid)) {
            $sqlPlayer .= " AND serverid IN(" . implode(',', $serverid) . ')';
        }
        elseif(is_numeric($serverid)) {
            $sqlPlayer .= " AND serverid={$serverid}";
        }
        $stmt = $db->prepare($sqlPlayer);
        $stmt->execute($search);
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // print_r($list);
        $length = count($list);
//        print_r($list);
        if ($length==1) {
            $list[0]['name'] = iconv('gbk', 'utf-8',$list[0]['name']);
//            $userInfo  = array_shift($lists);
//            $accountid = $list['accountid'];
            $login = "SELECT logintime,ip FROM {$tbname['loginmac']} WHERE accountid=? ORDER BY logintime DESC LIMIT 1";
            $reg   = "SELECT createtime,ip FROM {$tbname['newmac']} WHERE accountid=? LIMIT 1";
            $stmt  = $db->prepare($login);
            $stmt->execute(array($accountid));
            $loginData = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt  = $db->prepare($reg);
            $stmt->execute(array($accountid));
            $newData = $stmt->fetch(PDO::FETCH_ASSOC);

            $list[0]['logintime'] = $loginData['logintime'];
            $list[0]['loginip']   = $loginData['ip'];

            $list[0]['createtime'] = $newData['createtime'];
            $list[0]['ip'] = $loginData['ip'];
            return array('total'=>1, 'list'=>$list);
        }
        elseif($length>1) {
//            $accountids = array();
            $newMacArr = array();
            foreach ($list as $key=>$l) {
                $newMacArr[$l['accountid']] = $l;
            }
            $accountids = implode(',', array_keys($newMacArr));
//            $login = "SELECT MAX(logintime),ip FROM loginmac WHERE accountid IN($accountids) GROUP BY accountid ORDER BY NULL ";
//            $reg   = "SELECT createtime,ip FROM newmac WHERE accountid IN($accountids) ORDER BY NULL";

            $sqlJoin = "SELECT n.accountid, n.createtime,n.ip as regip,n.mac,n.clienttype, MAX(logintime) AS logintime,l.ip as loginip "
                        ."FROM {$tbname['newmac']} n LEFT JOIN {$tbname['loginmac']} l ON l.accountid=n.accountid WHERE n.accountid IN($accountids) GROUP BY n.accountid ORDER BY NULL";
//            echo $sqlJoin;
            $stmt  = $db->prepare($sqlJoin);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

//            $stmt  = $db->prepare($reg);
//            $stmt->execute();
//            $newData = $stmt->fetch(PDO::FETCH_ASSOC);

            foreach ($data as $player) {
                $newMacArr[$player['accountid']]['name'] = iconv('gbk', 'utf-8', $newMacArr[$player['accountid']]['name']);
                $newMacArr[$player['accountid']]['mac']        = $player['mac'];
                $newMacArr[$player['accountid']]['clienttype'] = $player['clienttype'];
                $newMacArr[$player['accountid']]['createtime'] = $player['createtime'];
                $newMacArr[$player['accountid']]['ip']         = $player['regip'];
                $newMacArr[$player['accountid']]['logintime']  = $player['logintime'];
                $newMacArr[$player['accountid']]['loginip']    = $player['loginip'];
            }
            //print_r($newMacArr);exit;
            return array('total'=>$length, 'list'=>$newMacArr);
        }
        return array('total'=>0);
    }

    /**
     * 注册转化统计
     *
     * @param Array $serverid
     * @param Array $fenbaoid
     * @param int $limit
     * @param int $offset
     * @param int $gameid
     * @return array|string
     * @throws Exception
     */
    public function SumRegTransTotal(Array $serverid=array(),
                        Array $fenbaoid=array(), $limit=0, $offset=40, $gameid=5)
    {
        $time_query_begin = date('Ymd', strtotime($this->bt));
        $time_query_end   = date('Ymd', strtotime($this->et));
        $where = "WHERE `gameid`=$gameid AND `sday`>=$time_query_begin AND `sday`<=$time_query_end";
//        echo $where;
        $lenServer = count($serverid);
        if ($lenServer==1) {
            $where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $where .= " AND serverid IN(" . implode(',', $serverid).')';
        }
        $lenFenbao = count($fenbaoid);
        if ($lenFenbao==1) {
            $where .= " AND fenbaoid=" . array_shift($fenbaoid);
        }
        elseif ($lenFenbao>1) {
            $where .= " AND fenbaoid IN(" . implode(',', $fenbaoid).')';
        }
        //TODO::统计行数

        $sqlCnt = "SELECT COUNT(*) as c FROM (SELECT COUNT(*) FROM sum_reg_trans {$where} GROUP BY sday,prof) t";
//            echo($sqlCnt);
        $stmt = $this->_db->prepare($sqlCnt);
        $stmt->execute();
        $total =  $stmt->fetchColumn();


        $sqlPlayer = <<<SQL
        SELECT prof, sum_reg_trans.`sday`, SUM(sum_new) AS sum_new,
        SUM(sum_cre) AS sum_cre
        FROM sum_reg_trans
        $where
        GROUP BY sday,prof
        ORDER BY id DESC
        LIMIT $limit,$offset
SQL;
        $q2 = $this->_db->prepare($sqlPlayer);
        $q2->execute();
        return array('total'=>$total, 'list'=>$q2->fetchAll(PDO::FETCH_ASSOC));
    }


    /**
     * 实时在线数据
     *
     * @param PDO $db
     * @param  $bt
     * @param Array $serverid
     * @param int $gameid
     * @return array|string
     */
    public function ShowOnlineRealTime(PDO $db, $bt, Array $serverid=array(), $gameid=5)
    {
//        $bt = date('ymd', strtotime('-15 mins'));
        $where = "gameid=$gameid";
//        $where .= ' AND daytime>=' . date('ymdHi', strtotime('-15 mins'))
        $where .= ' AND daytime='. date('ymdHi', strtotime($bt));
        $lenServer = count($serverid);
        if ($lenServer==1) {
            $where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $where .= " AND serverid IN(" . implode(',', $serverid).')';
        }


//        //TODO:统计各个服务区
//        $sql_ids = "SELECT MAX(id) FROM `online` WHERE $where GROUP BY `serverid`";
//        echo $sql_ids;
//        exit;
//        $stmt = $db->prepare($sql_ids);
//        $stmt->execute();
//        $idArr = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $lists = array();
        // (count($idArr)) {
//            $idStr = implode(',', $idArr);
            $sql = <<<SQL
    SELECT servername, `online`, MaxOnline, WorldOnline, WorldMaxOnline,
    daytime, gameid, serverid
    FROM `online`
    WHERE $where
    ORDER BY serverid ASC
SQL;
            //ORDER BY serverid ASC
//        echo $sql;
            $q = $db->prepare($sql);
            $q->execute();
            $lists = $q->fetchAll(PDO::FETCH_ASSOC);
        //}
        return $lists;
    }


    public function ShowOnlineBefore($bt, $et, Array $serverid=array(),
                                     $limit=0,$offset=40, $gameid=5)
    {
        if (!$gameid) {
            throw new Exception('请选择游戏！');
        }
        if (!$bt) {
            throw new Exception('请选择开始时间');
        }

        $bt = date('Ymd', strtotime($bt));
        $et = date('Ymd', strtotime($et));
        $where = "gameid=$gameid";
        $where .= ' AND `sday`>=' . $bt;
        $where .= $et>0 ? ' AND `sday`<='. $et : '';
        $group = ' GROUP BY sday';

        $lenServer = count($serverid);
        if ($lenServer==1) {
            $flag = 1;
            $group .= ",serverid";
            $where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $flag = 1;
            $group .= ",serverid";
            $where .= " AND serverid IN(" . implode(',', $serverid).')';
        }
        $sql = "SELECT COUNT(*) as cnt FROM sum_online WHERE {$where} {$group}";
//        echo $sql;
        $q = $this->_db->prepare($sql);
        $q->execute();
        $total =  $q->fetchAll(PDO::FETCH_COLUMN);

        //print_r($total);exit;
        //如果有按区服或查询
        if (isset($flag)) {
            $sql = <<<SQL
  SELECT serverid,`sday`, sum_maxOnline, sum_worldMaxOnline, avg_online, avg_worldOnline
  FROM sum_online
  WHERE {$where} GROUP BY sday,serverid
  ORDER BY `sday` DESC,serverid ASC
  LIMIT $limit,$offset
SQL;
//            echo $sql;
        }
        //查询全部总数
        else {
            $sql = <<<SQL
  SELECT `sday`, MAX(sum_worldMaxOnline) AS sum_maxOnline,
  MAX(sum_worldMaxOnline) AS sum_worldMaxOnline,
  SUM( avg_online) AS avg_online, SUM( avg_worldOnline) AS avg_worldOnline
  FROM sum_online
  WHERE {$where}  GROUP BY sday
  ORDER BY `sday` DESC
  LIMIT $limit,$offset
SQL;
        }
//        echo $sql;
        $q = $this->_db->prepare($sql);
        $q->execute();
        return array(
            'total'=>count($total),
            'list'=>$q->fetchAll(PDO::FETCH_ASSOC));
    }

    public function __destruct()
    {
        $this->_db = null;
    }
} 