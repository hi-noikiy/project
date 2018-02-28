<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/2/21
 * Time: 10:21
 *
 * 用户注册模型
 */

include_once __DIR__.'/base_model.php';

class Login_model extends Base_model
{
    /**
     * 获取注册数据
     *
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public function get_perhour($date1, $date2)
    {
        $sql = <<<SQL
        SELECT SUM(cnt) as cnt,`hour`,`date` FROM sum_login_hour
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

/*        $sql = <<<SQL
SELECT SUM(cnt) AS cnt,serverid,channel,appid,`date` FROM sum_login_hour
WHERE appid=? AND `date`=? GROUP BY serverid,channel
SQL;*/
        echo "Login Day Counts:\n";
        /*$sql = <<<SQL
        SELECT appid,COUNT(discount accountid) AS cnt,serverid,channel,$date as 'date' FROM u_login_new
        WHERE appid=$this->appid AND logindate=$date
        GROUP BY serverid,channel
SQL;*/
        $sql = <<<SQL
        SELECT appid,COUNT(discount accountid) AS cnt,serverid,channel,$date as 'date' FROM u_login_$date
        WHERE appid=$this->appid
        GROUP BY serverid,channel
SQL;
        echo $sql,"\n";
        $query = $this->db->query($sql, [$this->appid, $date]);
        if ($query) {
            $data = $query->result_array();
            $this->db->insert_batch('sum_login_day', $data);
        }
        return true;
    }

    /**
     * 每小时注册数据
     *
     * @param $t1 int 开始时间
     * @param $t2 int 结束时间
     * @param $hour int 小时
     * @param $date int 日期(Ymd)
     * @return mixed
     */
    public function hour_counts($t1, $t2, $hour, $date)
    {
        /*$sql = <<<SQL
        SELECT appid,COUNT(*) AS cnt,serverid,channel,$hour as 'hour',$date as 'date' FROM u_login_new
        WHERE appid=? AND created_at BETWEEN ? AND ?
        GROUP BY serverid,channel
SQL;*/
        $sql = <<<SQL
        SELECT appid,COUNT(*) AS cnt,serverid,channel,$hour as 'hour',$date as 'date' FROM u_login_$date
        WHERE appid=? AND created_at BETWEEN ? AND ?
        GROUP BY serverid,channel
SQL;
        $query = $this->db_sdk->query($sql, [
            $this->appid,
            $t1,
            $t2,
        ]);
        if($query)return $query->result_array();
        return array();
    }
}