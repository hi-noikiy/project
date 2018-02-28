<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 2016/11/12
 * Time: 15:19
 *
 * 统计游戏服务器发送过来的数据,汇总等
 */
class GameServerData extends CI_Model
{
    protected $db_sdk = null;
    protected $db = null;
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);
        //$this->db_sdk = $this->load->database('sdk', TRUE);
    }
    
	/**
	 * 商店操作
	 */
    public function shop($where=array(),$field='*',$group='',$order=''){
    	$sql = "select $field from sum_shop where logdate between {$where['begindate']} and {$where['enddate']}";
    	$data = array();
    	$query = $this->db->query($sql);
    	if($query){
    		$data = $query->result_array();
    	}
    	return $data;
    }
    /**
     * 商店操作
     */
    public function shoprank($where=array(),$field='*',$group='',$order=''){
    	$sql = "select $field from sum_shop_rank where logdate between {$where['begindate']} and {$where['enddate']}";
    	if($where['itemid']){
    		$sql .= " and itemid in ({$where['itemid']})"; 
    	}
    	if($where['tabletype']){
    		$sql .= " and table_type = {$where['tabletype']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	$data = array();
    	$query = $this->db->query($sql);
    	if($query){
    		$data = $query->result_array();
    	}
    	return $data;
    }
}
