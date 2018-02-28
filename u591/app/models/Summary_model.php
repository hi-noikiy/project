<?php

/**
 * Created by PhpStorm.
 * User: chenguangpeng
 * Date: 7/16-016
 * Time: 14:23
 */
class Summary_model extends CI_Model
{
    public function __construct()
    {
        //$this->db  = $this->load->database('default', TRUE);
        parent::__construct();
    }

    /**
     * 查询客户端bug
     *
     *@author 王涛 20170223
     */
    public function getClientBug($where=array(),$field='*')
    {
    	$db_sdk = $this->load->database('sdk', TRUE);
    	$sql = "select $field from client_bug where 1=1";
    	foreach ($where as $k=>$v){
    		$sql .= " and $k='".$v."'";
    	}
    	$query = $db_sdk->query($sql);
    	if($query){
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 按区服汇总数据
     *
     * @param $appid
     * @param $bt
     * @param $et
     * @param int $serverid
     * @param int $channel
     * @return array
     */
    public function getDataByServer($appid, $bt, $et, $serverid=0, $channel=0)
    {
    	$data = [];
    	//AU
    	$this->load->model('Player_analysis_model');
    	$this->load->model('Register_model');
    	$data     = $this->Player_analysis_model->getRealAuData($appid, $bt, $et, $serverid, $channel, 2);
    	//留存数据
    	//$data['remain'] =  $this->Player_analysis_model->getRemainData($appid, $bt, $et, $serverid, $channel, 2);
    	//print_r($data['remain']);
    	//新增/转化率
    	//$data['reg'] =  $this->Register_model->getRegisterDay($appid, $bt, $et, $serverid, $channel, 2);
    	//$data['role']= $this->Register_model->getRoleDay($appid, $bt, $et, $serverid, $channel, 2);
    	return $data;
    }
    public function getData($appid, $bt, $et, $serverid=0, $channel=0)
    {
        $data = [];
        //AU
        $this->load->model('Player_analysis_model');
        $this->load->model('Register_model');
        $this->load->model('Real_time_model');
        $begintime = strtotime($bt. ' 00:00:00');
        $endtime = strtotime($et. ' 23:59:59');
        $data = $this->Real_time_model->DeviceActiveData($appid,$begintime , $endtime, $channel);
        $data['au']     = $this->Player_analysis_model->getRealAuData($appid, $bt, $et, $serverid, $channel);
        //留存数据
        $data['remain'] =  $this->Player_analysis_model->getRemainData($appid, $bt, $et, $serverid, $channel);
        //新增/转化率
        $data['reg'] =  $this->Register_model->getRegisterDay($appid, $bt, $et, $serverid, $channel);
        $data['role']= $this->Register_model->getRoleDay($appid, $bt, $et, $serverid, $channel);
        //最大在线
        $data['max_online'] = $this->getMaxOnline($appid, $bt, $et, $serverid, $channel);
        //平均在线时间
        $data['avg_online'] = $this->getAvgOnline($appid, $bt, $et, $serverid, $channel);   
        return $data;
    }

    /**
     * 按渠道汇总数据
     *
     * @param $appid
     * @param $bt
     * @param $et
     * @param int $serverid
     * @param int $channel
     * @return array
     */
    public function getDataByChannel($appid, $bt, $et, $serverid=0, $channel=0)
    {
        $data = [];
        //AU
        $this->load->model('Player_analysis_model');
        $this->load->model('Register_model');
        $this->load->model('Real_time_model');
        $begintime = strtotime($bt. ' 00:00:00');
        $endtime = strtotime($et. ' 23:59:59');
        $data = $this->Real_time_model->DeviceActiveData($appid, $begintime, $endtime, $channel,1);
        $data['au']     = $this->Player_analysis_model->getRealAuData($appid, $bt, $et, $serverid, $channel, 1);
        //留存数据
        $data['remain'] =  $this->Player_analysis_model->getRemainData($appid, $bt, $et, $serverid, $channel, 1, 1);
        //print_r($data['remain']);
        //新增/转化率
        $data['reg'] =  $this->Register_model->getRegisterDay($appid, $bt, $et, $serverid, $channel, 1);
        $data['role']= $this->Register_model->getRoleDay($appid, $bt, $et, $serverid, $channel, 1);
        return $data;
    }
    /**
     * 最大在线
     *
     * @param $appid
     * @param $bt
     * @param $et
     * @param int $serverid
     * @param int $channel
     * @return mixed
     */
    public function getMaxOnline($appid, $bt, $et, $serverid=0, $channel=0)
    {
        $db_sdk = $this->load->database('sdk', TRUE);
        $bt  = date('ymd0000', strtotime($bt));
        $et  = date('ymd2359', strtotime($et));
        $sql = "SELECT MAX(WorldOnline) as cnt,concat('20', substring(daytime,1,6)) as date,left(serverid,1) as ls FROM online WHERE appid=$appid and daytime BETWEEN $bt AND $et ";
        //$sql = "SELECT MAX(cnt) as cnt,`date`,SUM(cnt) as sum_cnt FROM sum_online_hour WHERE appid=$appid AND `date` BETWEEN $bt AND $et";
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";

        //if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        //elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
        $sql .= " group by `date`,ls order by `date` asc";
        //echo $sql;
        $query = $db_sdk->query($sql);
        $data = $query->result_array();
        $query->free_result();
        return $data;
    }

    public function getAvgOnline($appid, $bt, $et, $serverid=0, $channel=0)
    {
        $this->load->model('Online_analysis_model');
        $this->Online_analysis_model->init( $appid, $bt, $et, 0, null, $this->db);
        $onlineAvg = $this->Online_analysis_model->GetOnlineTimeAvg($serverid, $channel);
        return $onlineAvg;
    }
}