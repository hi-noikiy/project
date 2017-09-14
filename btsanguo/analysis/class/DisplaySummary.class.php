<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 下午5:38
 */

class DisplaySummary extends Display{

    private $where = '';
    private $whereFenbao = '';
    private $group = '';
    private $limit = '';


    public function Show(Array $serverid = array(),Array $fenbaoid=array(),
                         $limit, $offset, $isApple=false)
    {
        $this->limit = " LIMIT $limit, $offset";
        $this->where = " WHERE 1=1";//gameid={$this->gameid}
        if ($this->bt==$this->et) {
            $this->where .= " AND `sday`={$this->bt}";
        }
        else {
            $this->where .= " AND (`sday`>={$this->bt} AND `sday`<={$this->et})";
        }

        $lenServer = count($serverid);
        if ($lenServer==1) {
            $this->where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $this->where .= " AND serverid IN(" . implode(',', $serverid).')';
        }
        //TODO:Apple数据统计，有专门的渠道id和区服id
        if ($isApple) {
            $fenbaoid = array();
        }

        $lenFenbao = count($fenbaoid);
        if ($lenFenbao==1) {
            $this->whereFenbao .= " AND fenbaoid=" . array_shift($fenbaoid);
        }
        elseif ($lenFenbao>1) {
            $this->whereFenbao .= " AND fenbaoid IN(" . implode(',', $fenbaoid).')';
        }

        if($lenServer>0 || $lenFenbao>0) {
            return $this->Detail();
        }
        else {
            return $this->All();
        }
    }
    public function All($isApple=false)
    {
        $this->group = " GROUP BY sday";
        $sql_cnt = "SELECT COUNT(*) FROM sum_daily_archive_all " . $this->where;
//        echo $sql_cnt;
        $stmt = $this->_db->prepare($sql_cnt);
        $stmt->execute();
        $total = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $sql = "SELECT *, '----' as serverid,'----' as fenbaoid FROM sum_daily_archive_all"
            . $this->where
            . ' ORDER BY sday DESC'
            . $this->limit;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ret = array(
            'type'  => 'all',
            'total' => array_shift($total),
            'list'  => $list,
            'um'    => $this->AllUserRemain(),
        );
        if (!$isApple) {
            $ret['ol'] = $this->AllOnline();
        }
        return $ret;
    }

    public function Detail($isApple=false)
    {
        $this->group = " GROUP BY serverid, fenbaoid";
        $sql_cnt = "SELECT COUNT(*) FROM sum_daily_archive "
            . $this->where;
        $stmt = $this->_db->prepare($sql_cnt);
        $stmt->execute();
        $total = $stmt->fetchColumn(0);
        $sql = "SELECT * FROM sum_daily_archive"
            . $this->where . $this->whereFenbao
            . ' ORDER BY sday DESC'
            . $this->limit;
//        echo $sql;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ret = array(
            'type'  => 'detail',
            'total' => $total,
            'list'  => $list,
            'um'    => $this->DetailUserRmain(),
        );
        if (!$isApple) {
            $ret['ol'] = $this->AllOnline();
        }

        return $ret;
    }

    public function AllOnline()
    {
        $sql = <<<SQL
  SELECT `sday`, MAX(sum_worldMaxOnline) AS maxol, SUM(avg_online) AS avgol
  FROM sum_online {$this->where}
  GROUP BY sday ORDER BY NULL
SQL;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ol = array();
        foreach ($list as $l) {
            $ol[$l['sday']] = array(
                'maxol' => $l['maxol'],
                'avgol' => $l['avgol']
            );
        }
        return $ol;
    }


    public function AllUserRemain()
    {
        $sql = <<<SQL
        SELECT sday, SUM(usercount) as usercount,
        SUM(day1) as day1, SUM(day3) as day3,SUM(day7) as day7,
        SUM(day15) as day15,SUM(day30) as day30 FROM sum_reserveusers_daily
        {$this->where} GROUP BY sday ORDER BY NULL {$this->limit}
SQL;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($list as $l) {
            $um[$l['sday']] = $l;
        }
        return $um;
    }

    public function DetailUserRmain()
    {
        $sql = <<<SQL
        SELECT sday, SUM(usercount) as usercount,serverid as s,fenbaoid as f,
        SUM(day1) as day1, SUM(day3) as day3,SUM(day7) as day7,
        SUM(day15) as day15,SUM(day30) as day30 FROM sum_reserveusers_daily
        {$this->where} {$this->whereFenbao} GROUP BY serverid,fenbaoid ORDER BY NULL {$this->limit}
SQL;
//        echo $sql;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($list as $l) {
            $um[$l['sday'].'_'.$l['s'].'_'.$l['f']] = $l;
        }
        return $um;
    }
} 