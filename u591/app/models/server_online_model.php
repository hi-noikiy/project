<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/1/14
 * Time: 23:00
 */
class server_online_model extends CI_Model
{
    private $bt;
    private $et;
    private $appid;

    public function __construct($appid,$bt, $et)
    {
        parent::__construct();
        $this->bt       = $bt;
        $this->et       = $et;
        $this->appid    = $appid;
        $this->sday     = date('Ymd', strtotime($this->bt));

        $this->db1    = $this->load->database('default', TRUE);
        $this->db_sdk = $this->load->database('sdk', TRUE);
    }

    /**
     * 实时在线
     */
    public function realtime_online()
    {
        $sql = "SELECT * FROM u_server_online WHERE appid=? AND created_at BETWEEN ? AND ?";
        $query = $this->db_sdk->query($sql, [$this->appid, $this->bt, $this->et]);
        return $query->result_array();
    }

    /**
     * 实时在线-最高在线人数
     * @return mixed
     */
    public function realtime_online_max()
    {
        $sql = <<<SQL
SELECT max(online) as max_online,serverid,created_at FROM u_server_online
WHERE appid=? AND created_at BETWEEN ? AND ?
GROUP BY serverid
SQL;
        $query = $this->db_sdk->query($sql, [$this->appid, $this->bt, $this->et]);
        return $query->result_array();
    }
}