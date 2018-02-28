<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/16
 * Time: 21:03
 */
//ini_set('display_errors', 'On');
class RegisterProcess_model extends CI_Model
{
    private $db_sdk;
    public function __construct()
    {
        parent::__construct();
        $this->db_sdk = $this->load->database('sdk', true);
    }
/**
 * 新注册流程统计
 * @param unknown $appid
 * @param unknown $startTime
 * @param unknown $endTime
 * @param string $type_list
 * @param string $channel
 * @return boolean
 * 
 * @author 王涛 20170220
 */
    public function summary($appid, $startTime, $endTime, $type_list=false, $channel=false , $version='')
    {
    	$date = date('Ymd',$startTime);
    	$sql = "SELECT count(*) as cnt,type_id,from_unixtime(`created_at`, '%k') as hour FROM u_register_process_$date WHERE appid=$appid AND created_at BETWEEN $startTime AND $endTime";
    	if ($type_list!=false) {
    		$sql .= " AND type_id IN(".implode(',', $type_list).")";
    	}
    	if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
    	elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
    	if($version){
    		$sql .= " AND client_version = $version";
    	}
    	$sql .= " GROUP BY type_id,hour ORDER BY hour DESC";
    	$q = $this->db_sdk->query($sql);
    	if ($q) {
    		return $q->result_array();
    	}
    	return false;
    }
    /*public function summary($appid, $startTime, $endTime, $type_list=false, $channel=false)
    {
        $sql = "SELECT count(*) as cnt,type_id,from_unixtime(`created_at`, '%k') as hour FROM u_register_process_new WHERE appid=$appid AND created_at BETWEEN $startTime AND $endTime";
        if ($type_list!=false) {
            $sql .= " AND type_id IN(".implode(',', $type_list).")";
        }
        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
        $sql .= " GROUP BY type_id,hour ORDER BY hour DESC";
        $q = $this->db_sdk->query($sql);
        if ($q) {
            return $q->result_array();
        }
        return false;
    }*/

    public function detail($appid, $startTime, $endTime, $type_id)
    {
        $sql = "SELECT count(*) as cnt,type_id,from_unixtime(`created_at`, '%H') as hour FROM u_register_process_new WHERE appid=$appid AND created_at BETWEEN $startTime AND $endTime AND type_id =$type_id";
        $sql .= " GROUP BY hour,type_id ORDER BY hour ASC";
        //echo $sql;
        $q = $this->db_sdk->query($sql);
        if ($q) {
            return $q->result_array();
        }
        return false;
    }
}
