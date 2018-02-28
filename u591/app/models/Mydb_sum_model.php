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
	 * 按注册渠道 时间统计
	 *
	 * @author 陈燕彬 20171206
	 */
	public function summarybytimechannel($where=array(),$field='',$group='channel',$order = "channel")
	{
		if (!$field)$field='*';
		$sql = "SELECT channel, SUM(device) as device, SUM(macregister) as macregister,  SUM(reg) as reg FROM sum_summary_by_channel WHERE date between {$where['begindate']} and {$where['enddate']}";
		if($where['channels']){
			$sql .= " AND channel IN(".implode(',', $where['channels']).")";
		}
		if($group){
			$sql .= " group by $group";
		}

		$sql .= " order by reg desc";

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

        /*
     *
     */
        /*
     * 新设备使用旧账号登录数量              安装新包使用旧账号登录数量 zzl 20171018
     */
    public function accountDevice($assign_date)
    {
        if(!empty($assign_date)){            
           $date = $assign_date;            
           $date2 = date('Ymd', strtotime($assign_date)+86400);
            
        } else {
            
          $date = date('Ymd', strtotime('-1 days'));            
           $date2 = date('Ymd', time());
            
        }
        
       echo $date;
        $unix_time1 = strtotime($date);
        $unix_time2 = strtotime($date2);
        
        $dbsdk = $this->load->database('sdk', true);
        
     echo   $sql_1 = " SELECT COUNT(*) as device_old_account  FROM u_login_{$date} a,(SELECT mac FROM `u_device_unique` WHERE created_at>={$unix_time1} and created_at<{$unix_time2} and mac not in (SELECT mac FROM u_register WHERE reg_date={$date})) b WHERE a.mac=b.mac";
        
        $result_1 = $dbsdk->query($sql_1);
        if ($result_1) {
            $result_1_data = $result_1->result_array();
        }
        
  echo     $sql_2 = " SELECT COUNT(*) as install_old_account  FROM u_login_{$date} a,(SELECT mac FROM `u_device_active` WHERE created_at>={$unix_time1} and created_at<{$unix_time2} and mac not in (SELECT mac FROM u_register WHERE reg_date={$date})) b WHERE a.mac=b.mac";
        
        $result_2 = $dbsdk->query($sql_2);
        if ($result_2) {
            $result_2_data = $result_2->result_array();
        }
        
        $sql = "UPDATE sum_summary set  device_old_account={$result_1_data[0][device_old_account]} , install_old_account={$result_2_data[0][install_old_account]}  WHERE date={$date}";
        $result = $this->db->query($sql);
    }
    
    
}