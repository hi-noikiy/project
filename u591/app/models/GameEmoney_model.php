<?php

/**
 *
 * 统计剩余钻石
 */
class GameEmoney_model extends CI_Model
{
    protected $db_sdk = null;
    public function __construct()
    {
        parent::__construct();
        $this->db_sdk = $this->load->database('sdk', TRUE);
    }
    /**
     * 查询钻石排名
     */
    public function rankEmoney($where=array() , $field='*' , $group='' ,$order='',$limit='')
    {
    	$sql   = <<<SQL
        select $field from game_rank_emoney where logdate={$where['logdate']}
SQL;
    	if($where['serverid']){
    		$sql .= " AND serverid ={$where['serverid']}";
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
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    
    }
    /**
     * 查询金币排名
     */
    public function rankMoney($where=array() , $field='*' , $group='' ,$order='',$limit='')
    {
    	$sql   = <<<SQL
        select $field from game_rank_money where logdate={$where['logdate']}
SQL;
    	if($where['serverid']){
    		$sql .= " AND serverid ={$where['serverid']}";
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
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    
    }
   
    /**
     * 区服道具统计
     *
     */
    public function serverItem($where=array() , $field='*' , $group='')
    {
    	$gameserver = include APPPATH .'/config/game_server_list.php'; //道具字典
    	foreach ($gameserver as $v){
    		if($where['serverid']>=$v[0]&&$where['serverid']<=$v[1]){
    			$gamedb = $this->load->database($v[2], TRUE);
    			$p = $where['serverid']%100;
    			$table = 'u_item'.str_pad($p,3,0,STR_PAD_LEFT);
    			break;
    		}
    	}
    	$sql   = <<<SQL
        select $field from $table where 1=1
SQL;
    	if($where['itemid']){
    		$sql .= " and itemtype_id={$where['itemid']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	$query = $gamedb->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    
    }
    /**
     * 个人剩余钻石数量
     *
     */
    public function userEmoney($where=array() , $field='*' , $group='')
    {
    	$gameserver = include APPPATH .'/config/game_server_list.php'; //道具字典
    	foreach ($gameserver as $v){
    		if($where['serverid']>=$v[0]&&$where['serverid']<=$v[1]){
    			$gamedb = $this->load->database($v[2], TRUE);
    			$p = $where['serverid']%100;
    			$table = 'u_playershare'.str_pad($p,3,0,STR_PAD_LEFT);
    			break;
    		}
    	}
    	$sql   = <<<SQL
        select $field from $table where account_id={$where['accountid']}
SQL;
    	$query = $gamedb->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    
    }
    /**
     * 充值总金额
     *
     */
    public function payMoney($where=array() , $field='*' , $group='')
    {
    	$gameserver = include APPPATH .'/config/game_server_list.php'; //道具字典
    	foreach ($gameserver as $v){
    		if($where['serverid']>=$v[0]&&$where['serverid']<=$v[1]){
    			$gamedb = $this->load->database($v[2], TRUE);
    			$p = $where['serverid']%100;
    			$table = 'u_gift_recharge'.str_pad($p,3,0,STR_PAD_LEFT);
    			break;
    		}
    	}
    	$sql   = <<<SQL
        select $field from $table where account_id={$where['accountid']}
SQL;
    	$query = $gamedb->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    
    }
    /**
 	 * 服务器剩余钻石数量
 	 * 
	 */
    public function serverEmoney($where=array() , $field='*' , $group='')
    {
    	if(!$where['type']){
    		$table = 'u_server_emoney';
    	}else{
    		$table = 'u_server_emoney_active';
    	}
        $sql   = <<<SQL
        select $field from $table where logdate between {$where['begindate']} and {$where['enddate']}
SQL;
        if($where['serverids']){
        	$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
        }

        if($group){
        	$sql .= " group by $group";
        }
        $query = $this->db_sdk->query($sql);
        if ($query) {
            return $query->result_array();
        }
        return array();

    }
   
  

    
}
