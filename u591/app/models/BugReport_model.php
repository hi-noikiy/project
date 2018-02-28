<?php

/**
 * Created by PhpStorm.
 * User: chenguangpeng
 * Date: 7/16-016
 * Time: 14:23
 */
class BugReport_model extends CI_Model
{
    public function __construct()
    {
        $this->db  = $this->load->database('sdk', TRUE);

    }
    public function getData($appid, $bt, $et, $serverid=0)
    {
        $sql = "SELECT * FROM u_bugreport WHERE appid=$appid AND `created_at` BETWEEN $bt AND $et";
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";
        $sql .= " ORDER BY created_at DESC LIMIT 500";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}