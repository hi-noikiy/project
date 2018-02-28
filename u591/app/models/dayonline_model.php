<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/1/7
 * Time: 22:41
 */
include_once __DIR__.'/base_model.php';

class dayonline_model extends Base_model
{

    //private $bt;
    //private $et;
    //private $appid;

    public function __construct()
    {
        parent::__construct();
        //$this->bt       = $bt;
        //$this->et       = $et;
        //$this->appid    = $appid;
        $this->sday     = date('Ymd', strtotime($this->bt));
    }


    private function calculate_online_time($mins)
    {
        $seconds = $mins * 60;
        $sql    = <<<SQL
SELECT accountid,serverid,channel FROM u_dayonline
WHERE appid=? AND created_at BETWEEN ? AND ?
GROUP BY accountid HAVING SUM(online) >=?
SQL;
        $query= $this->db_sdk->query($sql,
            array($this->appid, $this->bt, $this->et, $seconds));
        if (!$query)
            return false;

        $data = $output = array();
        foreach($query->result_array() as $row) {
            $data[$row['serverid']][$row['channel']] += 1;
        }
        foreach ($data as $sid=>$row) {
            foreach ($row as $channel=>$cnt) {
                $output[] = array(
                    'serverid'  => $sid,
                    'channel'   => $channel,
                    'cnt'       => $cnt,
                );
            }
        }
//        $data = array(
//            'serverid'  => '',
//            'channel'   =>'',
//            'cnt'       =>'',
//        );
        return $output;
    }
    private function save($data, $col)
    {
        $sql = <<<SQL
INSERT INTO sum_au(serverid,channel,appid,`$col`,sday) VALUES(%REPLACE%)
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
        $str  = '';
        foreach ($data as $row) {
            $str .= "({$row['serverid']}, {$row['channel']}, $this->appid, {$row['cnt']}, {$this->sday}),";
        }
        $str = rtrim($str, ',');
        $sql = str_replace('%REPLACE%', $str, $sql);
        return $this->db->query($sql);
    }
    /**
     * 每周游戏时间≥15分钟用户
     */
    public function DAU()
    {
        $data = $this->calculate_online_time(15);
        $this->save($data, 'dau');
    }

    /**
     * 每周游戏时间≥120分钟用户
     */
    public function WAU()
    {
        $data = $this->calculate_online_time(120);
        $this->save($data, 'wau');
    }
    /**
     * 每周游戏时间≥500分钟用户
     */
    public function MAU()
    {
        $data = $this->calculate_online_time(500);
        $this->save($data, 'mau');
    }

    /**
     * 活跃度 小时为单位，以在线人数为判断
     */
    public function activeness($groupby='viplev')
    {
        $sql = <<<SQL
        SELECT COUNT(*) AS cnt,serverid,channel,`$groupby` FROM u_dayonline
        WHERE appid=? AND created_at BETWEEN ? AND ?
        GROUP BY $groupby,serverid,channel
SQL;
        $query = $this->db_sdk->query($sql, [
            $this->appid,
            $this->bt,
            $this->et,
        ]);
        return $query->result_array();
    }

    /**
     * 每小时在线数据
     *
     * @param $t1 int 开始时间
     * @param $t2 int 结束时间
     * @param $hour int 小时
     * @param $date int 日期(Ymd)
     * @return mixed
     */
    public function hour_counts($t1, $t2, $hour, $date)
    {
        $sql = <<<SQL
        SELECT appid,COUNT(*) AS cnt,serverid,channel,$hour as 'hour',$date as 'date' FROM u_dayonline
        WHERE appid=? AND created_at BETWEEN ? AND ?
        GROUP BY serverid,channel
SQL;
        $query = $this->db_sdk->query($sql, [
            $this->appid,
            $t1,
            $t2,
        ]);
        return $query->result_array();
    }

    public function get_perhour($date1, $date2)
    {
        $sql = <<<SQL
        SELECT SUM(cnt) as cnt,`hour`,`date` FROM sum_online_hour
        WHERE appid=? AND `date` BETWEEN ? AND ? GROUP BY `date`,`hour`
        ORDER BY `date` DESC,`hour` ASC
SQL;
        //echo $sql;exit;

        $query = $this->db1->query($sql,[
            $this->appid,
            $date1,
            $date2,
        ]);
        return $query->result_array();

    }

    public function day_counts($date)
    {
        $sql = <<<SQL
SELECT SUM(cnt) AS cnt,serverid,channel,appid,`date` FROM sum_online_hour
WHERE appid=? AND `date`=? GROUP BY serverid,channel
SQL;
        $query = $this->db->query($sql, [$this->appid, $date]);
        if (!$query) return false;
        $data = $query->result_array();
        if (count($data)) {
            print_r($data);
            $this->db->insert_batch('sum_online_day', $data);
        }
        return true;
    }
}
