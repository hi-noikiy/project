<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/7
 * Time: 22:04
 */
class Sdk_sum_model extends CI_Model
{
    protected $db = null;
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('sdk', TRUE);
    }
    /**
     * 登录表查询
     * 
     * @author 王涛 20170503
     */
    public function login($where=array(),$field='*',$group='',$order='')
    {
    	$date = date('Ymd',$where['begintime']);
    	$sql = "select $field FROM u_login_{$date} where 1=1";
   		if($where['serverids']){
			$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
		}
		if($where['channels']){
			$sql .= " AND channel IN(".implode(',', $where['channels']).")";
		}
		if($where['userid']){
			$sql .= " AND userid IN(".$where['userid'].")";
		}
    	if($group)$sql .= " group by $group";
    	if($order)$sql .= " order by $order";
    	$result = $this->db->query($sql);
    	if($result){
    		return $result->result_array();
    	}
    	return array();
    }
    /**
     * 登录流失统计
     * @param unknown $where
     * @param string $field
     * @param string $group
     * @return multitype:
     */
    public function loginlost($where=array())
    {
    	$beforesql = "select distinct accountid  from (SELECT accountid FROM u_login_".date('Ymd',strtotime("{$where['date']} -1 days"));
    	if($where['serverids']){
    		$msql = " WHERE serverid IN(".implode(',', $where['serverids']).")";
    	}
    	$beforesql .= $msql;
    	for($i=2;$i<=3;$i++){
    		$beforesql .= " union SELECT accountid FROM u_login_".date('Ymd',strtotime("{$where['date']} -$i days")).$msql;
    	}
    	$beforesql .= ")a";
    	$result = $this->db->query($beforesql);
    	$data['before'] = 0;
    	if($result){
    		$data['before'] = count($result->result_array());
    	}
    	$sql = "select distinct accountid from (SELECT accountid FROM u_login_{$where['date']} ";
    	if($where['serverids']){
    		$msql = " WHERE serverid IN(".implode(',', $where['serverids']).")";
    	}
    	$sql .= $msql;
    	for($i=1;$i<=2;$i++){
    		$sql .= " union SELECT accountid FROM u_login_".date('Ymd',strtotime("{$where['date']} +$i days")).$msql;
    	}
    	$sql .= ")b where accountid in ($beforesql)";
    	$result = $this->db->query($sql);
    	$data['after'] = 0;
    	if($result){
    		$data['after'] = count($result->result_array());
    	}
    	return $data;
    }
    /**
     * 参与度统计
     * @param unknown $where
     * @param string $field
     * @param string $group
     * @return multitype:
     */
    public function sumJoin($where=array(),$field='',$group='',$order = "mysort,param")
    {
    	if (!$field)$field='*';
    	$ym = date('Ym',strtotime($where['begindate']));
    	$sql = "SELECT $field FROM sum_join_$ym WHERE logdate between {$where['begindate']} and {$where['enddate']}";
    	if($where['typeids']){
    		$sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['param']){
    		$sql .= " AND param ={$where['param']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	$sql .= " order by $order";
    	$result = $this->db->query($sql);
    	if($result){
    		return $result->result_array();
    	}
    	return array();
    }
	/**
	 * vip登录情况
	 * @param unknown $where
	 * @param string $field
	 * @param string $group
	 * @return multitype:
	 */
	public function viplogin($where=array())
	{
		$date0 = $where['date'];
		$date1 = date('Ymd',strtotime("$date0 +1 days"));
		$date3 = date('Ymd',strtotime("$date0 +2 days"));
		$date7 = date('Ymd',strtotime("$date0 +6 days"));
		$data['day0'] = $data['day1'] = $data['day3'] =$data['day7']= array();
		$wsql = '';
		if($where['serverids']){
			$wsql .= " AND a.serverid IN(".implode(',', $where['serverids']).")";
		}
		if($where['channels']){
			$wsql .= " AND a.channel IN(".implode(',', $where['channels']).")";
		}
		$wsql .= " group by a.viplev";
		$sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a WHERE 1=1 ".$wsql;//当天登录数
		$result = $this->db->query($sql);
		if($result){
			$data['day0'] = $result->result_array();
		}
		$sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a inner join u_login_{$date1} b on a.accountid=b.accountid  WHERE 1=1 ".$wsql;//次日登录数
		$result = $this->db->query($sql);
		if($result){
			$data['day1'] = $result->result_array();
		}
		$sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a inner join u_login_{$date3} b on a.accountid=b.accountid  WHERE 1=1 ".$wsql;//3日登录数
		$result = $this->db->query($sql);
		if($result){
			$data['day3'] = $result->result_array();
		}
		$sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a inner join u_login_{$date7} b on a.accountid=b.accountid  WHERE 1=1 ".$wsql;//7日登录数
		$result = $this->db->query($sql);
		if($result){
			$data['day7'] = $result->result_array();
		}
		
		return $data;
	}
	/*指定时间段内的server id 列表
	 * @param string $where 
	 */
	
	public function serverList($where){
		if ($where ['beginserver'] && $where ['endserver']) {
			$server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );
			 
			if ($server_list) {
				foreach ( $server_list->result_array () as $k => $v ) {
						
					$server_list_new .= $v ['serverid'] . ',';
				}
			return 	$server_list_new = rtrim ( $server_list_new, ',' );
			}
		} else {
			
			return false;
		}
	}
    
}