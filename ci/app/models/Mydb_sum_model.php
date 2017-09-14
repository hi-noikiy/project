<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/7
 * Time: 22:04
 */
class Mydb_sum_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}
	/**
	 * 汇总统计
	 * 
	 * @author 王涛 20170407
	 */
	public function summary($where=array(),$field='',$group='date',$order = "date")
	{
		if (!$field)$field='*';
		$sql = "SELECT $field FROM sum_summary WHERE date between {$where['begindate']} and {$where['enddate']}";
		if($group){
			$sql .= " group by $group";
		}
		if($order){
			$sql .= " order by $order";
		}
		$result = $this->db->query($sql);
		if($result){
			return $result->result_array();
		}
		return array();
	}
	/**
	 * 按渠道汇总统计
	 *
	 * @author 王涛 20170407
	 */
	public function summarybychannel($where=array(),$field='',$group='channel',$order = "channel")
	{
		if (!$field)$field='*';
		$sql = "SELECT $field FROM sum_summary_by_channel WHERE date between {$where['begindate']} and {$where['enddate']}";
		if($where['channels']){
			$sql .= " AND channel IN(".implode(',', $where['channels']).")";
		}
		if($group){
			$sql .= " group by $group";
		}
		if($order){
			$sql .= " order by $order";
		}
		$result = $this->db->query($sql);
		if($result){
			return $result->result_array();
		}
		return array();
	}
	
	/**
	 * 按渠道汇总统计
	 *
	 * @author 王涛 20170407
	 */
	public function summarybyad($where=array(),$field='',$group='',$order = "")
	{
		if (!$field)$field='*';
		$sql = "SELECT $field FROM sum_reserveusers_daily_ad WHERE sday between {$where['begindate']} and {$where['enddate']}";
		if($where['channels']){
			$sql .= " AND channel = '{$where['channels']}'";
		}
		if($group){
			$sql .= " group by $group";
		}
		if($order){
			$sql .= " order by $order";
		}
// 		echo $sql;die;
		$result = $this->db->query($sql);
		if($result){
			return $result->result_array();
		}
		return array();
	}
	
	/**
	 * 运营活动道具产销
	 * @param unknown $where
	 * @param string $field
	 * @param string $group
	 * @return multitype:
	 */
	public function sumItemByType($where=array(),$field='',$group='')
	{
		if (!$field)$field='*';
		$sql = "SELECT $field FROM sum_item_by_type WHERE logdate = ".date('Ymd',$where['begintime']);
		if($where['typeids']){
			$sql .= " AND type IN(".implode(',', $where['typeids']).")";
		}
		if($where['itemid']){
			$sql .= " AND itemid =".$where['itemid'];
		}
		if($where['params']){
			$sql .= " AND typeid IN(".implode(',', $where['params']).")";
		}
		if($group){
			$sql .= " group by $group";
		}
	
		$result = $this->db->query($sql);
		if($result){
			return $result->result_array();
		}
		return array();
	}
	/**
	 * 统计道具产销
	 * @param unknown $where
	 * @param string $field
	 * @param string $group
	 * @return multitype:
	 */
	public function sumItem($where=array(),$field='',$group='')
	{
		if (!$field)$field='*';
		$sql = "SELECT $field FROM sum_item WHERE logdate = ".date('Ymd',$where['begintime']);
		if($where['typeids']){
			$sql .= " AND type IN(".implode(',', $where['typeids']).")";
		}
		if($where['itemid']){
			$sql .= " AND itemid in ({$where['itemid']})";
		}
		if($group){
			$sql .= " group by $group";
		}
	
		$result = $this->db->query($sql);
		if($result){
			return $result->result_array();
		}
		return array();
	}
    public function sumAct($where=array(),$field='',$group='')
    {
    	if (!$field)$field='*';
    	$sql = "SELECT $field FROM sum_act_by_type WHERE logdate = ".date('Ymd',$where['begintime']);
    	if(isset($where['type'])){
    		$sql .= " AND type =".$where['type'];
    	}
        if($where['typeids']){
    		$sql .= " AND typeid IN(".implode(',', $where['typeids']).")";
    	}

    	if($group){
    		$sql .= " group by $group";
    	}

    	$result = $this->db->query($sql);
    	if($result){
    		return $result->result_array();
    	}
    	return array();
    }
    
    // 行为产销统计(多天)   zzl 20170724
    public function sumBehavior($where=array(),$field='',$group='')
    {  
    	if (!$field)$field='*';
    	$sql = "SELECT $field FROM sum_act_by_type WHERE logdate>= ".date('Ymd',$where['begintime'])." and logdate<=".date('Ymd',$where['endtime']);
    	if(isset($where['type'])){
    		$sql .= " AND type =0";
    	}
    	if($where['typeids']){    	
    		$sql .= " AND typeid = {$where['typeids']}";
    	}
    
    	if($group){
    		$sql .= " group by $group";
    	}
 
    	$result = $this->db->query($sql);    	
    	if($result){
    		return $result->result_array();
    	}
    	return array();
    }
    
}