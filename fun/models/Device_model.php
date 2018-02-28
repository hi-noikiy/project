<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/2/24
 * Time: 22:41
 */
include_once __DIR__.'/base_model.php';
class Device_model extends Base_model
{
    public function day_counts($date)
    {
        /*$sql = <<<SQL
SELECT SUM(cnt) AS cnt,serverid,channel,appid,`date` FROM sum_device_active_hour
WHERE appid=$this->appid AND `date`=$date GROUP BY serverid,channel
SQL;*/
        echo "Device Day Counts:\n";
        $tm_b = strtotime($date . '000000');
        $tm_e = strtotime($date . '235959');
        $sql = <<<SQL
        SELECT appid,COUNT(*) AS cnt,serverid,channel,$date as 'date' FROM u_device_active
        WHERE appid=$this->appid AND created_at BETWEEN $tm_b AND $tm_e
        GROUP BY serverid,channel
SQL;
        echo $sql,"\n";
        $query = $this->db_sdk->query($sql);
        if (!$query) return false;
        $data = $query->result_array();
        if (count($data)) {
            $res = $this->db->insert_batch('sum_device_active_day', $data);
            var_dump($res);
        }
        return true;
    }

    /**
     * 获取激活统计数据
     *
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public function get_perhour($date1, $date2)
    {
        $sql = <<<SQL
        SELECT SUM(cnt) as cnt,`hour`,`date` FROM sum_device_active_hour
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
    /**
     * 每小时设备激活数据
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
        SELECT appid,COUNT(*) AS cnt,serverid,channel,$hour as 'hour',$date as 'date' FROM u_device_active
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
}