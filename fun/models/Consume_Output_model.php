<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 2016/11/7
 * Time: 22:32
 * 消耗产出模型
 */
class Consume_Output_model extends CI_Model
{
    private $table = 'type_018_';
    private $db_sdk = null;
    public function __construct()
    {
        parent::__construct();
        $this->db_sdk = $this->load->database('sdk', TRUE);
    }
    public function common_where($appid, $date1, $date2, $serverid, $channel, $viplev=0, $viplev_max=0)
    {
        $where = " WHERE `appid`=$appid"; // AND `date`<=$date2
        if($date1!=$date2 && $date1<$date2) {
            $where .= " AND `client_time`>={$date1} AND `client_time`<=$date2";
        }
        else {
            $where .= "  AND `client_time`={$date1}";
        }
        if (is_numeric($serverid) && $serverid>0) $where .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $where .= " AND serverid IN(".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $where .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $where .= " AND channel IN(".implode(',', $channel).")";
        if ($viplev>0) $where .= " AND vip_level>=$viplev";
        if ($viplev_max>0) $where .= " AND vip_level<=$viplev_max";
        return $where;
    }
    /**
     * 钻石消耗产出
     */
    public function Diamond($action, $appid, $date1, $date2, $serverid, $channel, $viplev=0, $viplev_max=0)
    {
        $where = $this->common_where($appid, $date1, $date2, $serverid, $channel, $viplev, $viplev_max);
        $this->table .= $appid;

        if ($action==0) {
            $field = 'get_emoney';
            $where .= " AND get_emoney>0";
        }
        else {
            $field = 'consume_emoney';
            $where .= " AND consume_emoney>0";
        }

        $sql = "SELECT SUM(`$field`) AS emoney,counttype,"
            ."FROM_UNIXTIME(client_time,'%Y/%m/%d') as _date FROM {$this->table}"
            ." $where GROUP BY counttype,_date";
        //echo $sql;
        $q = $this->db_sdk->query($sql);
        $data = [];
        if ($q) {
            $data = $q->result_array();
        }
        return $data;
    }

    public function ShopSaleCount($appid, $date1, $date2, $serverid, $channel, $viplev=0, $viplev_max=0)
    {
        $where = $this->common_where($appid, $date1, $date2, $serverid, $channel, $viplev, $viplev_max);
        $sql = "select count(*) as cnt,sum(get_num_1) as num,param,get_item_1 as item,FROM_UNIXTIME(client_time,'%Y/%m/%d') as _date from type_018_10002 $where and counttype=1 group by _date,param,get_item_1 order by _date ASC,param asc,num desc";
        //echo $sql;
        //exit;
        $q = $this->db_sdk->query($sql);
        if ($q) return $q->result();
        return false;
    }
}