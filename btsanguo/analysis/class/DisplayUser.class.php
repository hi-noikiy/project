<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-6
 * Time: 下午3:27
 * 玩家留存显示
 */
class DisplayUser extends Display{

    /**
     * 用户留存显示页面
     *
     * @param int $serverid
     * @param mixed $fenbaoid
     * @param int $limit
     * @param int $offset
     * @return array|int
     */
    public function ShowUserRemain($serverid=0, $fenbaoid=0, $limit=0, $offset=20)
    {
        $where = "WHERE gameid={$this->gameid} AND sday>={$this->bt} AND sday<={$this->et}";
        $groupByServer = false;
        $groupByFenbao = false;

        if (is_numeric($serverid) && $serverid>0) {
            $where .= " AND serverid=$serverid";
            $groupByServer = true;
        }
        elseif (is_array($serverid) && count($serverid)) {
            $where .= " AND serverid IN(".implode(',', $serverid).")";
            $groupByServer = true;
        }
        if (is_numeric($fenbaoid) && $fenbaoid>0) {
            $where .= " AND fenbaoid=$fenbaoid";
            $groupByFenbao = true;
        }
        elseif (is_array($fenbaoid) && count($fenbaoid)) {
            $where .= " AND fenbaoid IN(".implode(',', $fenbaoid).")";
            $groupByFenbao = true;
        }
//        if ($groupByServer AND $groupByFenbao) {
//            $group = "GROUP BY serverid,fenbaoid,sday";
//        }
//        elseif ($groupByServer) {
//            $group = "GROUP BY serverid,sday";
//        }
//        elseif ($groupByFenbao) {
//            $group = "GROUP BY fenbaoid, sday";
//        }
//        else {
//            $group = 'GROUP BY sday';
//        }
        $group = 'GROUP BY sday';
//        $where .= ' AND newlogin>0';
        $sql = "SELECT COUNT(*) AS cnt FROM sum_reserveusers_daily $where $group";
//            echo $sql;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $total = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $sql = <<<SQL
        SELECT sday, SUM(usercount) as usercount,SUM(dau) as dau,serverid,fenbaoid,
        SUM(wau) AS wau, SUM(mau) as mau,SUM(newlogin) as newlogin,
        SUM(day1) as day1,SUM(day2) as day2, SUM(day3) as day3,
        SUM(day4) as day4,SUM(day5) as day5, SUM(day6) as day6,SUM(day7) as day7,
        SUM(day15) as day15,SUM(day30) as day30 FROM sum_reserveusers_daily
        $where $group
        ORDER BY sday DESC
        LIMIT $limit, $offset
SQL;
        if ($_GET['test']) {
            echo '<pre>'.$sql.'</pre>';
        }
        //echo $sql;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array(
            'total' => count($total),
            'list'  => $list
        );
    }

    public function ShowUserRemainByChannel()
    {
        $where = "WHERE gameid={$this->gameid} AND sday={$this->bt}";
        $sql = <<<SQL
        SELECT sday, SUM(usercount) as usercount,fenbaoid,
        SUM(wau) AS wau, SUM(mau) as mau,SUM(newlogin) as newlogin,
        SUM(day1) as day1,SUM(day2) as day2, SUM(day3) as day3,SUM(day5) as day5,
        SUM(day7) as day7 FROM sum_reserveusers_daily
        {$where} GROUP BY fenbaoid
        ORDER BY sday DESC
SQL;
//        echo $sql;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }
    /**
     * 用户等级（流失）总数据
     *
     * @param $bt
     * @return array|mixed|null
     */
    public function ShowUserLostAll($bt)
    {
        $bt = date('Ymd', strtotime($bt));
        $where = "WHERE nop>0 AND gameid=? AND sday=?";
        $whereArr = array($this->gameid, $bt);
        //if ($isCnt) {
        $sql = "SELECT id FROM sum_player_lost $where";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute($whereArr);
        $idsArr = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!count($idsArr)) {
            return null;
        }
        $ids = implode(',', $idsArr);
        //return ;
        //}
//        $sql_page = "SELECT id FROM sum_player_lost $where LIMIT $limit, $offset";
////        echo $sql_page;
//        $stmt_page = $this->_db->prepare($sql_page);
//        $stmt_page->execute($whereArr);
//        $ids = implode(',', $stmt_page->fetchAll(PDO::FETCH_COLUMN));
//        echo $ids;
//        exit;
        //if (strlen($ids)) {
            $sql_sum = "SELECT SUM(nop) AS sum_nop,sday FROM sum_player_lost WHERE id IN($ids) GROUP BY sday";
            $stmt = $this->_db->prepare($sql_sum);
            $stmt->execute();
            $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $sum_cnt = array();
            foreach ($lists as $list) {
                $sum_cnt[$list['sday']] = $list['sum_nop'];
            }

            $sql = <<<SQL
        SELECT sday, lev,gameid, SUM(nop) as nop,SUM(lost_day1) as lost_day1,
        SUM(lost_day3) as lost_day3 FROM sum_player_lost
        WHERE id IN($ids)
        GROUP BY lev
SQL;
        if ($_GET['test']) {
            echo $sql;
        }
//            echo $sql;
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array(
                'list'  => $list,
                'nl'    => $sum_cnt,
            );
        //}
        //return null;

    }

    /**
     * 用户等级（流失）详细数据
     *
     * @param $bt
     * @param $et
     * @param int $serverid
     * @param int $fenbaoid
     * @param int $limit
     * @param int $offset
     * @return array|mixed|null
     */
    public function ShowUserLostQuery($bt, $serverid=0, $fenbaoid=0,
                                      $limit=0, $offset=20)
    {
        $bt = date('Ymd', strtotime($bt));
        $where = "WHERE nop>0 AND gameid={$this->gameid} AND sday=$bt";

        if (is_numeric($serverid) && $serverid>0) {
            $where .= " AND serverid=$serverid";
        }
        elseif (is_array($serverid) && count($serverid)) {
            $where .=  " AND serverid IN(".implode(',', $serverid).")";
        }

        if (is_numeric($fenbaoid) && $fenbaoid>0) {
            $where .= " AND fenbaoid=$fenbaoid";
        }
        elseif (is_array($fenbaoid) && count($fenbaoid)) {
            $where .=  " AND fenbaoid IN(".implode(',', $fenbaoid).")";
        }

        $sql = "SELECT COUNT(*) FROM sum_player_lost $where";
        if ($_GET['debug']) {
			echo '<pre>';
			echo $sql;
			echo '</pre>';
		}
		
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($bt));
        $total = array_pop($stmt->fetchAll(PDO::FETCH_COLUMN));
        $sql_page = "SELECT id FROM sum_player_lost $where  ORDER BY sday DESC LIMIT $limit, $offset";
//        echo $sql_page;
		if ($_GET['debug']) {
			echo '<pre>';
			echo $sql_page;
			echo '</pre>';
		}
        $stmt_page = $this->_db->prepare($sql_page);
        $stmt_page->execute(array($bt));
        $ids = implode(',', $stmt_page->fetchAll(PDO::FETCH_COLUMN));
//        echo $ids;
        if (strlen($ids)) {
            $sql_sum = "SELECT SUM(nop) AS sum_nop,sday FROM sum_player_lost WHERE id IN($ids) GROUP BY sday";
            $stmt = $this->_db->prepare($sql_sum);
            $stmt->execute();
            $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $sum_cnt = array();
            foreach ($lists as $list) {
                $sum_cnt[$list['sday']] = $list['sum_nop'];
            }

            $sql = <<<SQL
    SELECT `sday`, `gameid`,`serverid`,`fenbaoid`, `lev`, `nop`,`online_cnt`,
    `lost_day1`, `lost_day3` FROM sum_player_lost
    WHERE id IN($ids)
SQL;
//            echo $sql;
			if ($_GET['debug']) {
				echo '<pre>';
				echo $sql;
				echo '</pre>';
			}
            $stmt = $this->_db->prepare($sql);
            $stmt->execute();
            $list=$stmt->fetchAll(PDO::FETCH_ASSOC);
            $nl = $this->GetNewLogin(array($this->gameid, $bt));
            return array(
                'total' => $total,
                'list'  => $list,
                'nl'    => $sum_cnt,
            );
        }
        return null;
    }


    /**
     * 等级分布——从游戏数据库查询
     *
     * @param $bt
     * @param $et
     * @param array $serverid
     * @param array $fenbaoid
     * @return array
     */
    public function ShowUserLevel($bt,Array $serverid=array(),Array $fenbaoid=array())
    {
        $bt = date('Ymd', strtotime($bt));
//        $et = date('Ymd', strtotime($et));

        $where = " gameid={$this->gameid} AND sday={$bt}"; // AND sday<={$et}";

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

        //统计总数
        $sql = "SELECT sum(nop) as cnt FROM sum_player_level WHERE " .$where;
//        echo $sql;
//        exit;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $stmt->bindColumn('cnt', $totalPlayer);
        $stmt->fetch(PDO::FETCH_BOUND);
//        echo $totalPlayer;
        if ($totalPlayer>0) {
            $sqlLevel = "SELECT sum(nop) as cnt, lev,sday from sum_player_level "
                ."WHERE $where GROUP BY lev ORDER BY lev asc";
//            echo $sqlLevel;
            $stmt = $this->_db->prepare($sqlLevel);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return array('totalPlayer'=>$totalPlayer,'list'=>$result);

    }

    public function ShowRegRealTime($time)
    {
        $tm = strtotime($time);
        $bt = date('ymd0000', $tm);
        $et = date('ymd2359', $tm);
        $sql = "SELECT COUNT(*) as cnt,fenbaoid FROM newmac where createtime>=$bt AND createtime<=$et GROUP BY fenbaoid";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 获取今日新增登录数
     *
     * @param Array $where
     * @return mixed
     */
    public function GetNewLogin(Array $where)
    {
        $sql_all_player = "SELECT sday,SUM(newlogin) AS cnt FROM sum_reserveusers_daily WHERE gameid=? AND sday=? GROUP BY sday";
        $stmt=$this->_db->prepare($sql_all_player);
        $stmt->execute($where);
        $allPlayerArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ret = array();
        foreach ($allPlayerArr as $player) {
            $ret[$player['sday']] = $player['cnt'];
        }
        return $ret;

    }

    /**
     * 查看每日登录玩家的金币、元宝，根据等级区分
     *
     * @param $bt
     * @param array $serverid
     * @param array $fenbaoid
     * @return array
     */
    public function ShowPlayerMoney($bt,Array $serverid=array(),Array $fenbaoid=array())
    {
        $bt = date('Ymd', strtotime($bt));
//        $et = date('Ymd', strtotime($et));

        $where = " gameid={$this->gameid} AND sday={$bt}"; // AND sday<={$et}";

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

        $sql = <<<SQL
  SELECT SUM(nop) as nop,SUM(emoney) AS emoney,SUM(money) AS money,lev,sday
  FROM sum_player_money WHERE $where
  group by lev ORDER BY lev asc
SQL;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
} #EOL CLASS
