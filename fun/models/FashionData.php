<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 2016/11/12
 * Time: 15:19
 *
 * 统计游戏服务器发送过来的数据,汇总等
 */
class FashionData extends CI_Model
{
    protected $db_sdk = null;
    public function __construct()
    {
        parent::__construct();
        $this->db_sdk = $this->load->database('sdk', TRUE);
    }
    /**
     * 赛事统计
     *
     * @author 王涛 20170508
     */
    public function game($where=array(),$field='*',$group='',$order='',$limit=100)
    {
    	if(!$field)$field='*';
    	$sql = "select $field from u_task_user a left join u_first_task b on a.taskid=b.taskid and a.serverid=b.serverid and a.tasktime=b.tasktime and a.accountid=b.accountid and a.tasktype=b.tasktype
    	 left join u_task_vote c on a.taskid=c.taskid and a.serverid=c.target_serverid and a.tasktime=c.tasktime and a.userid=c.target_userid and a.tasktype=c.tasktype where a.isok=1";
    	if($where['begindate']){
    		$sql .= " and a.logdate>={$where['begindate']}";
    	}
    	if($where['enddate']){
    		$sql .= " and a.logdate<={$where['enddate']}";
    	}
    	if($where['beginid']){
    		$sql .= " and a.taskid>={$where['beginid']}";
    	}
    	if($where['endid']){
    		$sql .= " and a.taskid<={$where['endid']}";
    	}
    	if($where['tasktype']){
    		$sql .= " and a.tasktype={$where['tasktype']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	if($limit){
    		$sql .= " limit  $limit";
    	}
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 分享
     *
     * @author 王涛 20170505
     */
    public function share($where=array(),$field='*',$group='',$order='id desc',$limit=100){
    	$sql = "select $field from u_share_log where logdate between {$where['begindate']} and {$where['enddate']} and btype={$where['btype']}";
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	if($limit){
    		$sql .= " limit $limit";
    	}
    	$data = array();
    	$query = $this->db_sdk->query($sql);
    	if($query){
    		$data = $query->result_array();
    	}
    	return $data;
    }
    /**
     * 借衣饰
     *
     * @author 王涛 20170505
     */
    public function borrow($where=array(),$field='*',$group='',$order='id desc',$limit='100'){
    	$sql = "select $field from u_borrow_log where logdate between {$where['begindate']} and {$where['enddate']} and tasktype={$where['tasktype']}";
    	if($where['beginid']){
    		$sql .= " and taskid>={$where['beginid']}";
    	}
    	if($where['endid']){
    		$sql .= " and taskid<={$where['endid']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	if($limit){
    		$sql .= " limit $limit";
    	}
    	$data = array();
    	$query = $this->db_sdk->query($sql);
    	if($query){
    		$data = $query->result_array();
    	}
    	return $data;
    }
    /**
     * 任务统计
     *
     * @author 王涛 20170314
     */
    public function taskCount($where=array(),$field='*',$group='',$order='',$limit=100)
    {
    	if(!$field)$field='*';
    	$sql = "select $field from u_task_user where 1=1";
    	if($where['begindate']){
    		$sql .= " and logdate>={$where['begindate']}";
    	}
    	if($where['enddate']){
    		$sql .= " and logdate<={$where['enddate']}";
    	}
    	if($where['beginid']){
    		$sql .= " and taskid>={$where['beginid']}";
    	}
    	if($where['endid']){
    		$sql .= " and taskid<={$where['endid']}";
    	}
    	if($where['tasktype']){
    		$sql .= " and tasktype={$where['tasktype']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	if($limit){
    		$sql .= " limit  $limit";
    	}
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 投票统计
     *
     * @author 王涛 20170314
     */
    public function voteCount($where=array(),$field='*',$group='',$order='')
    {
    	$sql = "select $field from u_task_vote where 1=1";
    	if($where['begindate']){
    		$sql .= " and logdate>={$where['begindate']}";
    	}
    	if($where['enddate']){
    		$sql .= " and logdate<={$where['enddate']}";
    	}
    	if($where['beginid']){
    		$sql .= " and taskid>={$where['beginid']}";
    	}
    	if($where['endid']){
    		$sql .= " and taskid<={$where['endid']}";
    	}
    	if($where['tasktype']){
    		$sql .= " and tasktype={$where['tasktype']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 单人投票统计
     *
     * @author 王涛 20170314
     */
    public function voteCountOne($where=array(),$field='*',$group='',$order='')
    {
    	$sql = "select logdate,min(cid) mincid,max(cid) maxcid from (select logdate,accountid,count(id) cid from u_task_vote where 1=1 ";
    	if($where['begindate']){
    		$sql .= " and logdate>={$where['begindate']}";
    	}
    	if($where['enddate']){
    		$sql .= " and logdate<={$where['enddate']}";
    	}
    	if($where['beginid']){
    		$sql .= " and taskid>={$where['beginid']}";
    	}
    	if($where['endid']){
    		$sql .= " and taskid<={$where['endid']}";
    	}
    	if($where['tasktype']){
    		$sql .= " and tasktype={$where['tasktype']}";
    	}
    	$sql .= " group by logdate,accountid";
    	$sql .= ")a group by logdate";
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 星级统计
     *
     * @author 王涛 20170314
     */
    public function starCount($where=array(),$field='*',$group='',$order='')
    {
    	$sql = "select $field from u_task_star where 1=1";
    	if($where['begindate']){
    		$sql .= " and logdate>={$where['begindate']}";
    	}
    	if($where['enddate']){
    		$sql .= " and logdate<={$where['enddate']}";
    	}
    	if($where['beginid']){
    		$sql .= " and taskid>={$where['beginid']}";
    	}
    	if($where['endid']){
    		$sql .= " and taskid<={$where['endid']}";
    	}
    	if($where['tasktype']){
    		$sql .= " and tasktype={$where['tasktype']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    
}
