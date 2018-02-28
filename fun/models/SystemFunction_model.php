<?php

/**
 * Created by PhpStorm.
 * User: guangpeng
 * Date: 8/15-015
 * Time: 21:05
 */
class SystemFunction_model  extends CI_Model
{
    /**
     * @var $db_sdk CI_DB_driver
     */
    private $db_sdk;
    private $appid;
    public function __construct()
    {
        parent::__construct();
        $this->db_sdk = $this->load->database('sdk', TRUE);
    }
    public function setAppid($appid)
    {
        $this->appid = $appid;
    }
    
    /**
     * 社团入侵
     * @param unknown $where
     * @return boolean
     * @author 王涛 20170515
     */
    public function ActionByInvasion($where = array() , $field = '*' ,$group = '')
    {
    	$date = date("Ymd",$where['begintime']);
    	$utable   = "u_behavior_$date";
    	$sql = "select $field from (select accountid,count(*) cid,floor(user_level/10) as level from $utable where 1=1 ";
    
    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['viplev_min']){
    		$sql .= " AND vip_level >=".$where['viplev_min'];
    	}
    	if($where['viplev_max']){
    		$sql .= " AND vip_level <=".$where['viplev_max'];
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND channel IN(".implode(',', $where['channels']).")";
    	}
    	if($where['typeids']){
    		$sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
    	}
    	$sql .= " group by accountid) a ";
    	if($where['cid']){
    		$sql .= " where cid={$where['cid']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	
    	$query = $this->db_sdk->query($sql);
    	if ($query) return $query->result_array();
    	return array();
    }
    /**
     * 植树节获得奖励数统计
     * @param unknown $where
     * @return boolean
     * @author 王涛 20170316
     */
    public function ActionByTree($where = array() , $field = '*' )
    {
    	$date = date("Ymd",$where['begintime']);
    	$utable   = "u_behavior_$date";
    	$sql = "select $field from (select act_id,count(id) cid,user_level from $utable where 1=1 ";

    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['viplev_min']){
    		$sql .= " AND vip_level >=".$where['viplev_min'];
    	}
    	if($where['viplev_max']){
    		$sql .= " AND vip_level <=".$where['viplev_max'];
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND channel IN(".implode(',', $where['channels']).")";
    	}
    	if($where['typeids']){
    		$sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
    	}
    	$sql .= " group by accountid) a group by level";
    	$query = $this->db_sdk->query($sql);
    	if ($query) return $query->result_array();
    	return array();
    }
    public function PlayerDevelop($serverid, $channel, $viplev_min, $viplev_max=0)
    {
        $sql = "SELECT count(*) as cnt,viplev FROM u_login_new WHERE appid={$this->appid}";
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
        if ($viplev_min>0)         $sql .= " AND viplev>=$viplev_min";
        if ($viplev_max>0)        $sql .= " AND viplev<=$viplev_max";
        $sql .= " GROUP BY viplev ORDER BY viplev ASC";
        $query = $this->db_sdk->query($sql);
        if ($query) return $query->result_array();
        return false;
    }

    public function PlayerDevelopDetail()
    {

    }

    public function money_use($timestamp1, $timestamp2, $serverid, $channel)
    {
        $table   = "type_005_" . $this->appid;
        $sql = "SELECT SUM(currency_num) as money,get_or_use,currency_type FROM $table";
        $sql .= " WHERE appid={$this->appid} AND created_at between $timestamp1 AND $timestamp2 ";
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
        $sql .= " GROUP BY currency_type,get_or_use ORDER BY currency_type ASC";
        $query = $this->db_sdk->query($sql);
        //echo $sql;
        if ($query) return $query->result_array();
        return false;
    }

    public function props_shop($timestamp1, $timestamp2, $serverid, $channel)
    {
        $table   = "type_007_" . $this->appid;
        $sql = "SELECT buy_item_id,buy_item_name,COUNT(*) AS cnt,SUM(buy_item_num) as num,SUM(currency_num) AS money FROM $table";
        $sql .= " WHERE appid={$this->appid} AND created_at between $timestamp1 AND $timestamp2 ";
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
        $sql .= " GROUP BY buy_item_id";
        $query = $this->db_sdk->query($sql);
        //echo $sql;
        if ($query) return $query->result_array();
        return false;
    }
    public function BehaviorProduceSale($timestamp1, $timestamp2, $serverid, $channel, $account_id, $userid)
    {
        $table   = "type_018_" . $this->appid;
        $sql = "SELECT * FROM $table";
        $sql .= " WHERE appid={$this->appid} AND created_at between $timestamp1 AND $timestamp2 ";
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
        if ($account_id) $sql .= " AND accountid=$account_id";
        if ($userid) $sql .= " AND userid=$userid";
        $sql .= " ORDER BY created_at DESC";
        $query = $this->db_sdk->query($sql);
        //echo $sql;
        if ($query) return $query->result_array();
        return false;
    }
    /**
     * 行为产销统计
     * @param unknown $where
     * @return boolean
     * @author 王涛 20161230
     */
    public function ActionProduceSaleByBehavior($where = array() , $field = '*' ,$group = '',$order ='',$limit='')
    { 
    	$date = date("Ymd",$where['begintime']);
    	$utable   = "u_behavior_$date";
    	$sql = "select $field FROM $utable where 1=1";
    	 
    	if ($where ['beginserver'] && $where ['endserver']) {
    		$server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );
    			
    		if ($server_list) {
    			foreach ( $server_list->result_array () as $k => $v ) {
    					
    				$server_list_new .= $v ['serverid'] . ',';
    			}
    			$server_list_new = rtrim ( $server_list_new, ',' );
    		}
    	}
    	
    	if ($server_list) {
    		$sql .= " AND serverid IN($server_list_new)";
    	}
    	
    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['userid']){
    		$sql .= " AND userid =".$where['userid'];
    	}
    	if($where['viplev_min']){
    		$sql .= " AND vip_level >=".$where['viplev_min'];
    	}
    	if($where['viplev_max']){
    		$sql .= " AND vip_level <=".$where['viplev_max'];
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND channel IN(".implode(',', $where['channels']).")";
    	}
    	if($where['typeids']){
    		$sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
    	}
    	if($where['beginh']){
    		$sql .= " AND from_unixtime(client_time,'%H%i') between {$where['beginh']} and {$where['endh']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($where['cid']){
    		$sql .= " having cid IN({$where['cid']})";
    	}
    	if($limit){
    		$sql .= " limit $limit";
    	} 

    	$query = $this->db_sdk->query($sql);    	
    	if ($query) return $query->result_array();
    	return array();
    }
    
    
    
    /**
     * 行为产销统计(多天)
     * @param unknown $where
     * @return boolean
     * @author zzl 20170724 
     */
    public function ActionProduceSaleByBehaviorMore($where = array() , $field = '*' ,$group = '',$order ='',$limit='')  
    {  
    	$date = date("Ymd",$where['begintime']);
    	$utable   = "u_behavior_{$where['logdate']}";
    	$sql = "select $field FROM $utable where 1=1";
    
    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['userid']){
    		$sql .= " AND userid =".$where['userid'];
    	}
    	if($where['viplev_min']){
    		$sql .= " AND vip_level >=".$where['viplev_min'];
    	}
    	if($where['viplev_max']){
    		$sql .= " AND vip_level <=".$where['viplev_max'];
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND channel IN(".implode(',', $where['channels']).")";
    	}
    	if($where['beginh']){
    		$sql .= " AND from_unixtime(client_time,'%H%i') between {$where['beginh']} and {$where['endh']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($where['cid']){
    		$sql .= " having cid IN({$where['cid']})";
    	}
    	if($limit){
    		$sql .= " limit $limit";
    	}
   
    	$query = $this->db_sdk->query($sql);
    	if ($query) return $query->result_array();
    	return array();
    }
    
    /**
     * 行为产销统计
     * @param unknown $where
     * @return boolean
     * @author 王涛 20161230
     */
    public function ActionProduceSaleNew($where = array() , $field = '*' ,$group = '')
    {
    	$date = date("Ymd",$where['begintime']);
    	$itable   = "item_trading_$date";
    	$utable   = "u_behavior_$date";
    	$u_register   = "u_register";
    	//$sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id inner join (select act_id,count(DISTINCT accountid) as caccountid FROM $utable  group by act_id)as n on u.act_id=n.act_id";
    //	$sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where 1=1 ";
    	$sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id inner join $u_register r on u.accountid=r.accountid  where 1=1 ";
    
    	if ($where ['beginserver'] && $where ['endserver']) {
    		$server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );
    		 
    		if ($server_list) {
    			foreach ( $server_list->result_array () as $k => $v ) {
    					
    				$server_list_new .= $v ['serverid'] . ',';
    			}
    			$server_list_new = rtrim ( $server_list_new, ',' );
    		}
    	}
    	 
    	if ($server_list) {
    		$sql .= " AND u.serverid IN($server_list_new)";
    	}  
    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['userid']){
    		$sql .= " AND userid =".$where['userid'];
    	}
    	if($where['serverids']){
    		$sql .= " AND u.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND u.channel IN(".implode(',', $where['channels']).")";
    	}
    	if($where['typeids']){
    		$sql .= " AND u.act_id IN(".implode(',', $where['typeids']).")";
    	}
    	if(isset($where['type'])){
    		$sql .= " AND i.type = {$where['type']}";
    	}
    	if(isset($where['register_start'])){
    	    $sql .= " AND r.reg_data = {$where['register_start']}";
    	}
    	
    	if(isset($where['item_id'])){
    		$sql .= " AND item_id = {$where['item_id']}";
    	}
    	if(isset($where['accountid'])){
    		if($where['accountid']==0){
    			$sql .= " AND accountid!=0";
    		}
    	}
    	 
    	if($group){
    		$sql .= " group by $group";
    	} 
    //	echo $sql;    
    	$query = $this->db_sdk->query($sql);
    	if ($query) return $query->result_array();
    	return false;
    }
    /**
     * 道具产销统计
     * @param unknown $where
     * @return boolean
     * @author 王涛 20161230
     */
    public function BehaviorProduceSaleNew($where = array() , $field = '*' ,$group = '' ,$order = '')
    {
    	$date = date("Ymd",$where['begintime']);
    	$itable   = "item_trading_$date";
    	$utable   = "u_behavior_$date";
    	$sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where item_id not between 1000000000 and 1999999999";

    	if ($where ['beginserver'] && $where ['endserver']) {
    		$server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );
    		 
    		if ($server_list) {
    			foreach ( $server_list->result_array () as $k => $v ) {
    					
    				$server_list_new .= $v ['serverid'] . ',';
    			}
    			$server_list_new = rtrim ( $server_list_new, ',' );
    		}
    	}
    	 
    	if ($server_list_new) {
    		$sql .= " AND u.serverid IN($server_list_new)";
    	}
    	
    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['viplev_min']){
    		$sql .= " AND vip_level >=".$where['viplev_min'];
    	}
    	if($where['viplev_max']){
    		$sql .= " AND vip_level <=".$where['viplev_max'];
    	}
    	if($where['itemid']){
    		$sql .= "  AND item_id in ({$where['itemid']})";
    	}
    	if($where['userid']){
    		$sql .= " AND userid =".$where['userid'];
    	}

    	if($where['serverids']){
    		$sql .= " AND u.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND u.channel IN(".implode(',', $where['channels']).")";
    	}
    	if($where['typeids']){
    		$sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
    	}
    	if(isset($where['type'])){
    		$sql .= " AND i.type = {$where['type']}";
    	}
    	if(isset($where['accountid'])){
    		if($where['accountid']==0){
    			$sql .= " AND accountid!=0";
    		}
    	}
    	
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}  
    	$query = $this->db_sdk->query($sql);
    	if ($query) return $query->result_array();
    	return false;
    }
    
    /**
     * 行为产销统计记录总表
     * @param unknown $where
     * @return boolean
     * @author 王涛 20170203
     */
    public function ActionCount($group='' , $date='')
    {
    	$types = array(
    			'act_id'=>0,
    			'serverid'=>1,
    			'channel'=>2
    	);
    	if(!$group){
    		$group='act_id';
    	}
    	if(!$date){ //前7天的数据
    		$date = date("Ymd",strtotime("-1 days"));
    	}
    	$mysql = "insert into mydb.sum_act_by_type(logdate,type,typeid,consume_money,consume_diamond,consume_tired,get_money,get_diamond,get_tired)  ";
    	$field = "$date as logdate,{$types[$group]} as type,$group as typeid,";
    	$field .= "sum(if(item_id=1&&type=1,item_num,0)) as consume_money,sum(if(item_id=3&&type=1,item_num,0)) as consume_diamond,sum(if(item_id=2&&type=1,item_num,0)) as consume_tired,";
    	$field .= "sum(if(item_id=1&&type=0,item_num,0)) as get_money,sum(if(item_id=3&&type=0,item_num,0)) as get_diamond,sum(if(item_id=2&&type=0,item_num,0)) as get_tired";
    	$itable   = "sdk.item_trading_$date";
    	$utable   = "sdk.u_behavior_$date";
    	$sql = $mysql . "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where item_id not between 1000000000 and 1999999999";
    	//$sql .= " and  u.created_at between " . strtotime($date) . " AND " . (strtotime($date)+86399);
    	$sql .= " group by typeid ON DUPLICATE KEY UPDATE `consume_money`=VALUES(consume_money),`consume_diamond`=VALUES(consume_diamond),`consume_tired`=VALUES(consume_tired),
    			`get_money`=VALUES(get_money),`get_diamond`=VALUES(get_diamond),
`get_tired`=VALUES(get_tired)";
    	$db_sdk = $this->load->database('rootsdk', TRUE);
    	$query = $db_sdk->query($sql);
    	print_r($db_sdk->error());
    	$mysql = "insert into mydb.sum_act_by_type(logdate,type,typeid,account_num)  ";
    	$field = "$date as logdate,{$types[$group]} as type,$group as typeid,count(distinct(accountid)) as account_num";
    	$utable   = "sdk.u_behavior_$date";
    	$sql = $mysql . "SELECT $field FROM $utable ";
    	$sql .= " group by typeid ON DUPLICATE KEY UPDATE `account_num`=VALUES(account_num)";
    	$query = $db_sdk->query($sql);
    	print_r($db_sdk->error());
    	return true;
    }
    
    
    
    /**
     * 运营道具增加区服详细
     * @param unknown $where
     * @return boolean
     * @author zzl 20170704
     */
    public function areaDistribution($where = array() , $field = '*' ,$group = '' ,$order = '')
    {
    	$date = date("Ymd",$where['begintime']);
    	$itable   = "item_trading_$date";
    	$utable   = "u_behavior_$date";
    	$sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where item_id not between 1000000000 and 1999999999";
    
    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['viplev_min']){
    		$sql .= " AND vip_level >=".$where['viplev_min'];
    	}
    	if($where['viplev_max']){
    		$sql .= " AND vip_level <=".$where['viplev_max'];
    	}
    	if($where['itemid']){
    		$sql .= "  AND item_id in ({$where['itemid']})";
    	}
    	if($where['userid']){
    		$sql .= " AND userid =".$where['userid'];
    	}
    
    	if($where['serverids']){
    		$sql .= " AND u.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND u.channel IN(".implode(',', $where['channels']).")";
    	}
  /*   	if($where['typeids']){
    		$sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
    	} */
    	if(isset($where['type'])){
    		$sql .= " AND i.type = {$where['type']}";
    	}
    	if(isset($where['accountid'])){
    		if($where['accountid']==0){
    			$sql .= " AND accountid!=0";
    		}
    	}
    	 
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	$query = $this->db_sdk->query($sql);    	
    
    	if ($query) return $query->result_array();
    	return false;
    }
    
    
    
    
    /**
     * 运营道具增加活动档次详细
     * @param unknown $where
     * @return boolean
     * @author zzl 20170704
     */
    public function levelDistribution($where = array() , $field = '*' ,$group = '' ,$order = '')
    {
    	$date = date("Ymd",$where['begintime']);
    	$itable   = "item_trading_$date";
    	$utable   = "u_behavior_$date";
    	$sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where item_id not between 1000000000 and 1999999999";
    
    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['viplev_min']){
    		$sql .= " AND vip_level >=".$where['viplev_min'];
    	}
    	if($where['viplev_max']){
    		$sql .= " AND vip_level <=".$where['viplev_max'];
    	}
    	if($where['itemid']){
    		$sql .= "  AND item_id in ({$where['itemid']})";
    	}
    	if($where['userid']){
    		$sql .= " AND userid =".$where['userid'];
    	}
    
    	if($where['serverids']){
    		$sql .= " AND u.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND u.channel IN(".implode(',', $where['channels']).")";
    	}
    	if($where['typeids']){
    		$sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
    	}
    	if(isset($where['type'])){
    		$sql .= " AND i.type = {$where['type']}";
    	}
    	if(isset($where['accountid'])){
    		if($where['accountid']==0){
    			$sql .= " AND accountid!=0";
    		}
    	}
    
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	$query = $this->db_sdk->query($sql);  	 
  
    	if ($query) return $query->result_array();
    	return false;
    }
    
    /**
     * 参与度统计
     *
     * @author 王涛 20170330
     */
    public function joinCount($date='')
    {
    //	ini_set('memory_limit', '2048M');
    	/*$sorttype = [1=>1200,6=>3400,7=>400,8=>600,9=>300,10=>1000,11=>1100,12=>2700,13=>5200,14=>5300,15=>5500,16=>5900,18=>5000,19=>3300,20=>1500,
21=>700,22=>800,23=>1400,24=>3200,25=>5400,26=>5600,27=>2400,28=>2500,29=>4100,30=>500,32=>6000,35=>4500,37=>6100,41=>6200,42=>1600,43=>1700,44=>1800,
45=>2100,46=>200,47=>1900,48=>900,49=>2000,50=>2200,52=>2800,53=>2300,54=>5700,55=>3000,56=>100,57=>2600,58=>5800,59=>4200,60=>4300,61=>4400,62=>1300,
63=>3100,66=>4600,67=>4700,68=>4800,69=>4900,71=>5100,72=>2900];*/
    	if(!$date){ //前天的数据
    		$date = date("Ymd",strtotime("-1 days"));
    	}
    	$ym = date('Ym',strtotime($date));
    	$db_sdk = $this->load->database('rootsdk', TRUE);
    	//$mysql = "insert into sum_join_$ym(logdate,act_id,serverid,param,act_count,act_account,vip_level,mysort) values ";
    	$mysql = "insert into sum_join_$ym(logdate,act_id,serverid,param,act_count,act_account,vip_level)  ";
    	$field = " $date as logdate,act_id,serverid,param,COUNT(*) act_count,COUNT(DISTINCT accountid) act_account,vip_level ";
    	$utable   = "u_behavior_$date";
    	$sql =  "SELECT $field FROM  $utable where act_id not in (49,13,14,25,15,26,29,20,43,44,101) GROUP BY act_id,param,serverid,vip_level ORDER BY act_id";
    	$query = $db_sdk->query($mysql.$sql." ON DUPLICATE KEY UPDATE `act_count`=VALUES(act_count),`act_account`=VALUES(act_account),`mysort`=VALUES(mysort)");
    	print_r($db_sdk->error());
    	/*if($query){
    		$data = $query->result_array();
    		foreach ($data as &$v){
    			$sor = $sorttype[$v['act_id']]?$sorttype[$v['act_id']]:0;
    			$mysql .= "({$v['logdate']},{$v['act_id']},{$v['serverid']},{$v['param']},{$v['act_count']},{$v['act_account']},{$sor},{$v['vip_level']}),";
    			unset($v);
    		}	
    	}
    	$db_sdk->reconnect();*/
    	$field = " $date as logdate,act_id,serverid,0 as param,COUNT(*) act_count,COUNT(DISTINCT accountid) act_account,vip_level ";
    	$sql =  "SELECT $field FROM  $utable where act_id in (49,13,14,25,15,26,29,20,43,44,101) GROUP BY act_id,serverid,vip_level ORDER BY act_id";
    	$query = $db_sdk->query($mysql.$sql." ON DUPLICATE KEY UPDATE `act_count`=VALUES(act_count),`act_account`=VALUES(act_account),`mysort`=VALUES(mysort)");
    	print_r($db_sdk->error());
    	/*if($query){
    		$data = $query->result_array();
    		foreach ($data as &$v){
    			$sor = $sorttype[$v['act_id']]?$sorttype[$v['act_id']]:0;
    			$mysql .= "({$v['logdate']},{$v['act_id']},{$v['serverid']},0,{$v['act_count']},{$v['act_account']},{$sorttype[$v['act_id']]},{$v['vip_level']}),";
    			unset($v);
    		}
    	}
    	$db_sdk->reconnect();
    	$mysql = rtrim($mysql,',') ." ON DUPLICATE KEY UPDATE `act_count`=VALUES(act_count),`act_account`=VALUES(act_account),`mysort`=VALUES(mysort)";
    	$query = $db_sdk->query($mysql);
    	print_r($db_sdk->error());*/
    	return true;
    }
    /**
     * 道具产销统计记录总表
     * 
     * @author 王涛 20170204
     */
    public function ItemCount($date='')
    {
    	if(!$date){ //前天的数据
    		$date = date("Ymd",strtotime("-1 days"));
    	}
    	$db_sdk = $this->load->database('rootsdk', TRUE);
    	$mysql = "insert into mydb.sum_item_by_type(logdate,type,typeid,itemid,consume_num,get_num)  ";
    	$field = "$date as logdate,act_id as type,param as typeid,item_id,sum(if(type=1,item_num,0)) as consume_num,sum(if(type=0,item_num,0)) as get_num ";
    	$itable   = "sdk.item_trading_$date";
    	$utable   = "sdk.u_behavior_$date";
    	$sql = $mysql . "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where item_id not between 1000000000 and 1999999999";
    	$sql .= " and  u.created_at between " . strtotime($date) . " AND " . (strtotime($date)+86399) . ' and act_id in (1,41) ';
    	$sql .= " group by item_id,act_id,param";
    	$sql .= " ON DUPLICATE KEY UPDATE logdate=values(logdate),type=values(type),typeid=values(typeid),itemid=values(itemid),consume_num=values(consume_num),get_num=values(get_num)";
    	$query = $db_sdk->query($sql);
    	print_r($db_sdk->error());
    	
    	$mysql = "insert into mydb.sum_item(logdate,type,itemid,consume_num,get_num)  ";
    	$field = "$date as logdate,act_id as type,item_id,sum(if(type=1,item_num,0)) as consume_num,sum(if(type=0,item_num,0)) as get_num ";
    	$sql = $mysql . "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where item_id not between 1000000000 and 1999999999";
    	$sql .= " and  u.created_at between " . strtotime($date) . " AND " . (strtotime($date)+86399) ;
    	$sql .= " group by item_id,act_id";
    	$sql .= " ON DUPLICATE KEY UPDATE logdate=values(logdate),type=values(type),itemid=values(itemid),consume_num=values(consume_num),get_num=values(get_num)";
    	 
    	$query = $db_sdk->query($sql);
    	print_r($db_sdk->error());
    	return true;
    }

    public function FoolBird($timestamp1, $timestamp2, $channel, $typeid_list)
    {
        //编号：process_index
        //结果：process_result字段
        //总数：process_index字段=1的数据条数
        $table   = "u_game_process_".date('Ym',$timestamp1);
        $sql_total = "SELECT count(*) as cnt from {$table}";
        $where = " WHERE appid={$this->appid} AND created_at between $timestamp1 AND $timestamp2 ";
        if (is_numeric($channel) && $channel>0) $where .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $where .= " AND channel IN(".implode(',', $channel).")";
        $sql_total = $sql_total . $where . " AND process_index=1";
        $query_total = $this->db_sdk->query($sql_total);
        //echo $sql_total;
        if (!$query_total) return false;
        if (is_numeric($typeid_list) && $typeid_list>0) $where .= " AND process_index=$typeid_list";
        elseif (is_array($typeid_list) && count($typeid_list)>0) $where .= " AND process_index IN(".implode(',', $typeid_list).")";
        $total = $query_total->result_array();
        $sql_query = <<<SQL
select process_index,process_result,count(*) as cnt from {$table} $where
group by process_index,process_result
ORDER BY process_index asc,process_result asc
SQL;
        //echo $sql_query;
        $query = $this->db_sdk->query($sql_query);
        $result = $query->result_array();
        $output = [];
        foreach ($result as $item) {
            $output[$item['process_index']][$item['process_result']]['cnt'] = $item['cnt'];
            $output[$item['process_index']][$item['process_result']]['per'] = number_format($item['cnt'] / $total[0]['cnt'] * 100,2);
        }
        return ['total'=>$total[0]['cnt'], 'data'=>$output];
    }

    public function BehaviorProductSaleConf()
    {
        return [
            1=> ['title'=>'商店购买', 'params'=>[1=> '道具商店',2=> '联盟商店',3=> '冠军商店', 4=> '全球商店',6=> '神秘商店',7=> '友好商店']],
            2=> ['title'=>'普通关卡', 'params'=>'通关的关卡id'],
            3=> ['title'=>'精英关卡', 'params'=>'通关的关卡id'],
            4=> ['title'=>'试练挑战', 'params'=>'通关的关卡id'],
            5=> ['title'=>'关卡任务', 'params'=>'完成的任务id'],
            6=> ['title'=>'联盟大赛', 'params'=>[1=> '挑战',2=> '排名结算']],
            7=> ['title'=>'祈愿', 'params'=>[1=> '第一次',2=> '第二次',3=> '第三次',4=> '第四次']],
            8=> ['title'=>'好友体力赠送', 'params'=>'今日领取的体力次数'],
            9=> ['title'=>'七日礼包', 'params'=>'领取第几天的礼包'],
            10=>['title'=>'购买金币', 'params'=>'购买次数'],
            11=>['title'=>'购买体力', 'params'=>'记录购买次数'],
            12=>['title'=>'副本评星奖励', 'params'=>'领取的副本片区id'],
            13=>['title'=>'精灵进化', 'params'=>'精灵的id'],
            14=>['title'=>'分配努力值', 'params'=>'精灵的id'],
            15=>['title'=>'图鉴升级', 'params'=>'精灵的类型id'],
            16=>['title'=>'vip特权礼包', 'params'=>'领取第几级的VIP特权礼包'],
            17=>['title'=>'成就奖励', 'params'=>'成就任务id'],
            18=>['title'=>'狩猎场', 'params'=>[1=> '初级狩猎场',2=> '中级狩猎场',3=> '高级狩猎场']],
            19=>['title'=>'全球对战', 'params'=>[1=> '对战',2=> '星级宝箱',3=> '赛季结算']],
            20=>['title'=>'固定交换', 'params'=> [1=> '初级交换',2=> '中级交换',3=> '高级交换']],
            21=>['title'=>'社团捐献', 'params'=>'捐献第几次'],
            22=>['title'=>'扭蛋', 'params'=>[1=> '免费扭蛋',2=> '购买一次',3=> '购买十次']],
            23=>['title'=>'商店刷新', 'params'=>[2=> '联盟商店',3=> '冠军商店',4=> '全球商店',6=> '神秘商店',7=>'友好商店']],
            24=>['title'=>'活跃礼包', 'params'=>[1=> '第一个活跃礼包',2=> '第二个活跃礼包',3=> '第三个活跃礼包']],
            25=>['title'=>'精灵融合', 'params'=>'精灵id'],
            26=>['title'=>'图鉴合成', 'params'=>'',],
            27=>['title'=>'道具出售', 'params'=>'',],
            28=>['title'=>'精灵放生', 'params'=>'',],
            29=>['title'=>'购买精英副本次数', 'params'=>'精英副本关卡id'],
            30=>['title'=>'一日三餐', 'params'=>[1=> '午餐',2=> '晚餐',3=> '夜宵']],
            31=>['title'=>'日常任务', 'params'=>'任务id'],
            32=>['title'=>'兑换码礼包', 'params'=>''],
            33=>['title'=>'封测排名礼包', 'params'=>'名次'],
            34=>['title'=>'封测冲级礼包', 'params'=>'领取礼包对应的等级'],
        ];
    }
    
    //  活跃玩家钻石途径  zzl 20170629
    public function actDistribute($where,$field,$group){
    	$sql = "select  $field  FROM `u_behavior_{$where['date']}` a,item_trading_{$where['date']} b WHERE a.vip_level={$where['vip_level']} and b.item_id=3 AND type={$where['type']} and a.id=b.behavior_id ";
    	    	
	    if($group){
    		$sql .= " group by $group";    	}

    	$query = $this->db_sdk->query($sql);    
        
    	if ($query) return $query->result_array();
    	return array();    
    }
    
    
    /**
     * 行为产销统计(多天)
     * @param unknown $where
     * @return boolean
     * @author zzl  20170721
     */
    public function behaviorProduceSaleMore($where = array() , $field = '*' ,$group = '')
    {
    	$date = date("Ymd",$where['begintime']);
    	$itable   = "item_trading_$date";
    	$utable   = "u_behavior_$date";
    	//$sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id inner join (select act_id,count(DISTINCT accountid) as caccountid FROM $utable  group by act_id)as n on u.act_id=n.act_id";
    	$sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id ";
    	 
    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['userid']){
    		$sql .= " AND userid =".$where['userid'];
    	}
    	if($where['serverids']){
    		$sql .= " AND u.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND u.channel IN(".implode(',', $where['channels']).")";
    	}
    	if($where['typeids']){    	
    		$sql .= " AND typeid = {$where['typeids']}";
    	}
    	if(isset($where['type'])){
    		$sql .= " AND i.type = {$where['type']}";
    	}
    	 
    	if(isset($where['item_id'])){
    		$sql .= " AND item_id = {$where['item_id']}";
    	}
    	if(isset($where['accountid'])){
    		if($where['accountid']==0){
    			$sql .= " AND accountid!=0";
    		}
    	}
    
    	if($group){
    		$sql .= " group by $group";
    	} 
    	$query = $this->db_sdk->query($sql);
    	if ($query) return $query->result_array();
    	return false;
    }
    
    
    /**
     * 行为产销统计
     * @param unknown $where
     * @return boolean
     * @author zzl 20170721
     */
    public function behaviorProduceSaleByBehavior($where = array() , $field = '*' ,$group = '',$order ='',$limit='')
    {
    	$date = date("Ymd",$where['begintime']);
    	$utable   = "u_behavior_$date";
    	$sql = "select $field FROM $utable where 1=1";
    
    	if($where['params']){
    		$sql .= " AND param IN(".implode(',', $where['params']).")";
    	}
    	if($where['userid']){
    		$sql .= " AND userid =".$where['userid'];
    	}
    	if($where['viplev_min']){
    		$sql .= " AND vip_level >=".$where['viplev_min'];
    	}
    	if($where['viplev_max']){
    		$sql .= " AND vip_level <=".$where['viplev_max'];
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND channel IN(".implode(',', $where['channels']).")";
    	}
    	if($where['typeids']){
    		$sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
    	}
    	if($where['beginh']){
    		$sql .= " AND from_unixtime(client_time,'%H%i') between {$where['beginh']} and {$where['endh']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($where['cid']){
    		$sql .= " having cid IN({$where['cid']})";
    	}
    	if($limit){
    		$sql .= " limit $limit";
    	}
    	$query = $this->db_sdk->query($sql);
    	if ($query) return $query->result_array();
    	return array();
    }
   //   获取一天的 vip分布  zzl 2017.8.2
    public function  vipDistribution( $where, $field, $group){    	
  
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
    	if ($where ['beginserver'] && $where ['endserver']) {
    		$server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );
    		 
    		if ($server_list) {
    			foreach ( $server_list->result_array () as $k => $v ) {
    					
    				$server_list_new .= $v ['serverid'] . ',';
    			}
    			$server_list_new = rtrim ( $server_list_new, ',' );
    		}
    	}
    	
    	if ($server_list) {
    		$wsql .= " AND a.serverid IN($server_list_new)";
    	}
    	$sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) accountid_total,a.viplev FROM u_login_{$date0} a WHERE 1=1 ".$wsql;//当天登录数
    
    	$result =$this->db_sdk->query($sql);
    	if($result){
    		$data['day0'] = $result->result_array();
    	}    
   
    	return $data;    	
    	
    }
    
    /*
     *  点击 区服分布  zzl   20170810
     */
    public function  areaClickDistribution( $where, $field, $group){    
   
    	$sql = "SELECT viplev,serverid,count(*) as total FROM activity_click_{$where['date']} group by serverid";//当天登录数
    	
    	$result =$this->db_sdk->query($sql);
    	if($result){
    		$data = $result->result_array();
    	}
    	 
    	return $data;
    }
    
    /*
     * 全球对战-战斗回合数统计  段位分布   zzl 20170815
     */
    public function  danDistribution( $where, $field, $group){
    	

    	$Ym = '20'.substr($where['begintime'], 0,4);    
    	$sql = "select $field from game_data_$Ym gd inner join game_user_$Ym gu on gd.id=gu.gameid  where 1=1";
    	if($where['begintime']){
    		$sql .= " and gd.endTime>={$where['begintime']}";
    	}
    	if($where['endtime']){
    		$sql .= " and gd.endTime<={$where['endtime']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND gu.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	
    	if($where['accountid']){
    		$sql .= " and gu.accountid = {$where['accountid']}";
    	}
    	if($where['dan_s'] && $where['dan_e']){
    		$sql .= " and (gu.dan >= {$where['dan_s']} and  gu.dan <= {$where['dan_e']})";
    	}
    	if($where['continuous']){
    		$sql .= " and gd.continuous = {$where['continuous']}";
    	}
    	if($where['serverids']){
    	    $sql .= " AND gu.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	
    	if(isset($where['type']) && $where['type'] != -1){
    		$sql .= " and gd.type={$where['type']}";
    	}
    	if($where['btype']){
    		$sql .= " and gd.btype={$where['btype']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by dan";
    	}
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    	
    }
    
    
    /*
     * 积分分布  zzl 2017 0908
     */
    public function  bonusDistribution( $where, $field, $group){
    
      if (! $field) {
          $field = '*';
      }
      $date0 = $where ['date']; 
      
      $sql = "select $field from u_behavior_{$where['date']} a inner join item_trading_{$where['date']} b on a.id=b.behavior_id and b.item_id=10034  where 1=1";      
   
      if ($where ['serverids']) {
          $sql .= " AND a.serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
      }
    
      if (  $where ['vip_level']) {
          $sql .= " and  a.vip_level={$where ['vip_level']}";
      }
      if ($group) {
          $sql .= " group by $group";
      }
      if ($order) {
          $sql .= " order by $order";
      }
      if ($limit) {
          $sql .= " limit $limit";
      }

      $this->db_sdk = $this->load->database('sdk', TRUE);
      $query = $this->db_sdk->query ( $sql );
      
      $result = array ();
      if ($query) {
          $result = $query->result_array ();
      }
      
      return $result;
      
      
  }
  
  
  /**
   *  精灵塔  增加条件
   * @author zzl 20170908
   */
  public function ActionByParam($where = array() , $field = '*' ,$group = '',$order ='',$limit='')
  {
  
      $date = date("Ymd",$where['begintime']);
      $utable   = "u_behavior_$date";
      $sql = "select $field FROM $utable where 1=1";
      
      if ($where ['beginserver'] && $where ['endserver']) {
          $server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );
           
          if ($server_list) {
              foreach ( $server_list->result_array () as $k => $v ) {
                  	
                  $server_list_new .= $v ['serverid'] . ',';
              }
              $server_list_new = rtrim ( $server_list_new, ',' );
          }
      }
       
      if ($server_list) {
          $sql .= " AND serverid IN($server_list_new)";
      }
       
      if($where['params']){
          $sql .= " AND param IN(".implode(',', $where['params']).")";
      }
      
      if($where['param_list']){
          $sql .= " AND param in ({$where['param_list']})";
      }
      if($where['userid']){
          $sql .= " AND userid =".$where['userid'];
      }
      if($where['viplev_min']){
          $sql .= " AND vip_level >=".$where['viplev_min'];
      }
      if($where['viplev_max']){
          $sql .= " AND vip_level <=".$where['viplev_max'];
      }
      if($where['serverids']){
          $sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
      }
      if($where['channels']){
          $sql .= " AND channel IN(".implode(',', $where['channels']).")";
      }
      if($where['typeids']){
          $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
      }
      if($where['beginh']){
          $sql .= " AND from_unixtime(client_time,'%H%i') between {$where['beginh']} and {$where['endh']}";
      }
      if($where['typeids']){
          $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
      }
      if($group){
          $sql .= " group by $group";
      }
     
  
/*       if($where['cid']){
          $sql .= " having cid IN({$where['cid']})";
      } */
      if($limit){
          $sql .= " limit $limit";
      }
      $query = $this->db_sdk->query($sql);
      if ($query) return $query->result_array();
      return array();      
  
  }
  
  /**
   *  精灵塔  增加条件 新
   * @author zzl 20170908   param参数变为param1
   */
  public function ActionByParamNew($where = array() , $field = '*' ,$group = '',$order ='',$limit='')
  {
  
      $date = date("Ymd",$where['begintime']);
      $utable   = "u_behavior_$date";
      $sql = "select $field FROM $utable where 1=1";
  
      if ($where ['beginserver'] && $where ['endserver']) {
          $server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );
           
          if ($server_list) {
              foreach ( $server_list->result_array () as $k => $v ) {
                   
                  $server_list_new .= $v ['serverid'] . ',';
              }
              $server_list_new = rtrim ( $server_list_new, ',' );
          }
      }
       
      if ($server_list) {
          $sql .= " AND serverid IN($server_list_new)";
      }    

      if($where['param_list']){
          $sql .= " AND param1 in ({$where['param_list']})";
      }
      if($where['userid']){
          $sql .= " AND userid =".$where['userid'];
      }
      if($where['viplev_min']){
          $sql .= " AND vip_level >=".$where['viplev_min'];
      }
      if($where['viplev_max']){
          $sql .= " AND vip_level <=".$where['viplev_max'];
      }
      if($where['serverids']){
          $sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
      }
      if($where['channels']){
          $sql .= " AND channel IN(".implode(',', $where['channels']).")";
      }
      if($where['typeids']){
          $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
      }
      if($where['beginh']){
          $sql .= " AND from_unixtime(client_time,'%H%i') between {$where['beginh']} and {$where['endh']}";
      }
     
      if($group){
          $sql .= " group by $group";
      }
      if($limit){
          $sql .= " limit $limit";
      } 
    

      $query = $this->db_sdk->query($sql);
      if ($query) return $query->result_array();
      return array();
  
  }
  /*
   * 精灵配招推荐  zzl 20170921
   */
  public function recommend($where, $field = '*' ,$group = '',$order ='',$limit=''){
      


     $Ym = date("Ym",strtotime($where['date']));
     $sql = "SELECT $field from  game_user_{$Ym} as gu INNER JOIN  game_data_{$Ym} as gd on gu.gameid=gd.id INNER JOIN game_user_eudemon_{$Ym} gue  on gue.gameuserid=gu.id"; 
      
     if( empty($where ['eudemon'])){
         return false;
     }

     if($where['btype']){
         $sql .= " AND gd.btype={$where['btype']}";
     }
     if($where['type']){
         $sql .= " AND gd.type={$where['type']}";
     }
     
     
     if($where ['dan_s']){
         $sql .= " AND gu.dan>={$where['dan_s']}";
     }
     if($where ['dan_e']){
         $sql .= " AND gu.dan<={$where['dan_e']}";
     }
     
     if( $where ['eudemon']){
         $sql .= " AND gue.eudemon =".$where['eudemon'];
     }
      if($where['userid']){
          $sql .= " AND userid =".$where['userid'];
      }
      if($where['viplev_min']){
          $sql .= " AND vip_level >=".$where['viplev_min'];
      }
      if($where['viplev_max']){
          $sql .= " AND vip_level <=".$where['viplev_max'];
      }
      if($where['serverids']){
          $sql .= " AND gu.serverid IN(".implode(',', $where['serverids']).")";
      }
      if($where['channels']){
          $sql .= " AND channel IN(".implode(',', $where['channels']).")";
      }
      if($where['typeids']){
          $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
      }
      if($where['beginh']){
          $sql .= " AND from_unixtime(client_time,'%H%i') between {$where['beginh']} and {$where['endh']}";
      }
       
      if($group){
          $sql .= " group by $group";
      }
      if($limit){
          $sql .= " limit $limit";
      }
      $query = $this->db_sdk->query($sql);
      if ($query) return $query->result_array();
      return array();
      
      
  }
  /*
   * 技能专精  zzl 20170921
   */
  public function mastery($where, $field = '*' ,$group = '',$order ='',$limit=''){


      $Ym = date("Ym",strtotime($where['date']));
      $Ymd = date("Ymd",strtotime($where['date']));

    $begintime=strtotime ( $where['begintime'] );
    $endtime=strtotime ( $where['endtime'] );
    $sql=" SELECT {$field} FROM game_synscience_{$Ym} gsy,
(SELECT accountid,dan from game_data_{$Ym} a,game_user_{$Ym} b WHERE a.btype=1 and a.id=b.gameid and a.endTime like '{$where ['begintime']}%'  GROUP BY accountid) s
WHERE gsy.logdate={$Ymd} and gsy.account_id=s.accountid group by dan";

      $query = $this->db_sdk->query($sql);
      if ($query) return $query->result_array();
      return array();
       
  }
  
  /*
   * 社团争霸赛数据提取并处理  zzl  20170927  
   */
  public function hegemony($where, $field = '*' ,$group = '',$order ='',$limit=''){
      
if($where ['group']==1){
    $sql = "SELECT $field from synpkgame_history where 1=1";
    if($where['serverids']){
        $sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    }
    if($where['channels']){
        $sql .= " AND channel IN(".implode(',', $where['channels']).")";
    }
    if($where['typeids']){
        $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
    }
    
    if($where ['pk_th']){
        $sql .= " AND pk_th={$where ['pk_th']}";
    }
     
    if($group){
        $sql .= " group by $group";
    }
    if($limit){
        $sql .= " limit $limit";
    }
}else {
    
   $group="s.game_server";

   $field="s.game_server as serverid,count(*) as total,g.vip_level as vip_level,count(if(g.vip_level=0,true,null)) vip0,count(if(g.vip_level=1,true,null)) vip1,count(if(g.vip_level=2,true,null)) vip2,count(if(g.vip_level=3,true,null)) vip3,count(if(g.vip_level=4,true,null)) vip4,count(if(g.vip_level=5,true,null)) vip5,count(if(g.vip_level=6,true,null)) vip6,count(if(g.vip_level=7,true,null)) vip7,count(if(g.vip_level=8,true,null)) vip8,count(if(g.vip_level=9,true,null)) vip9,count(if(g.vip_level=10,true,null)) vip10,count(if(g.vip_level=11,true,null)) vip11,count(if(g.vip_level=12,true,null)) vip12";
    
   
    $sql = "SELECT $field from u_synpvpgame_userpart s left join game_gift_recharge g on s.player_account_id=g.account_id  where 1=1";
    if($where['serverids']){
        $sql .= " AND s.serverid IN(".implode(',', $where['serverids']).")";
    }
    if($where['channels']){
        $sql .= " AND s.channel IN(".implode(',', $where['channels']).")";
    }
    if($where['typeids']){
        $sql .= " AND s.act_id IN(".implode(',', $where['typeids']).")";
    }
    
    if($where ['pk_th']){
        $sql .= " AND s.pk_th={$where ['pk_th']}";
    }
     
    if($group){
        $sql .= " group by $group";
    }
    if($limit){
        $sql .= " limit $limit";
    }
}
   


   
      $query = $this->db_sdk->query($sql);
      if ($query) return $query->result_array();
      return array();
  
  }
  
  /*社团争霸赛社团  zzl  20170927
   * 
   */
  
  public function hegemonyGroup($where, $field = '*' ,$group = '',$order ='',$limit=''){
  
      if($where ['group']==1){
          $sql = "SELECT $field from synpkgame_history where 1=1";
          if($where['serverids']){
              $sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
          }
          if($where['channels']){
              $sql .= " AND channel IN(".implode(',', $where['channels']).")";
          }
          if($where['typeids']){
              $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
          }
          if($where ['pk_th']){
              $sql .= " AND pk_th={$where ['pk_th']}";
          }
           
          if($group){
              $sql .= " group by $group";
          }
          if($limit){
              $sql .= " limit $limit";
          }
      }else {

          
          
         $sql = "SELECT $field from u_synpvpgame_userpart where 1=1";

          
          if($where['serverids']){
              $sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
          }
          if($where['channels']){
              $sql .= " AND channel IN(".implode(',', $where['channels']).")";
          }
          if($where['typeids']){
              $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
          }
          if($where ['pk_th']){
              $sql .= " AND pk_th={$where ['pk_th']}";
          }
           
          if($group){
              $sql .= " group by $group";
          }
          if($limit){
              $sql .= " limit $limit";
          }
      }
       
   //   $sql = "SELECT $field from synpkgame_history where 1=1";
  
  
     

      $query = $this->db_sdk->query($sql);
      if ($query) return $query->result_array();
      return array();
  
  }
  
  /*1.	远古宝藏统计    zzl  20170930
   *
   */
  
  public function ancient($where, $field = '*' ,$group = '',$order ='',$limit=''){
  
  
      $sql = "SELECT $field from game_egg where 1=1";
  
  
      if($where['serverids']){
          $sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
      }
      if($where['channels']){
          $sql .= " AND channel IN(".implode(',', $where['channels']).")";
      }
      if($where['typeids']){
          $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
      }
      if($where ['begintime'] && $where ['endtime']){
          $sql .= " AND logdate>={$where ['begintime']} and logdate<={$where ['endtime']}";
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
      if ($query) return $query->result_array();
      return array();
  
  }
  
  // 挑战次数
  public function challenge($where, $field = '*' ,$group = '',$order ='',$limit=''){
  
  
     $sql = "SELECT $field from game_community where status = 5 and  type = 4";
    //  $sql = "SELECT $field from game_community where 1=1";
  
  
      if($where['serverids']){
          $sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
      }
      if($where['channels']){
          $sql .= " AND channel IN(".implode(',', $where['channels']).")";
      }
      if($where['typeids']){
          $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
      }
      if($where ['begintime'] && $where ['endtime']){
          $sql .= " AND logdate>={$where ['begintime']} and logdate<={$where ['endtime']}";
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
    //    echo $sql;
      $query = $this->db_sdk->query($sql);
      if ($query) return $query->result_array();
      return array();
  
  }
  
  
  // 参与远古
  public function participation($where, $field = '*' ,$group = '',$order ='',$limit=''){  
  
      $sql = "SELECT $field from game_community where status = 5 and  type = 4";  
  
      if($where['serverids']){
          $sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
      }
      if($where['channels']){
          $sql .= " AND channel IN(".implode(',', $where['channels']).")";
      }
      if($where['typeids']){
          $sql .= " AND act_id IN(".implode(',', $where['typeids']).")";
      }
      if($where ['begintime'] && $where ['endtime']){
          $sql .= " AND logdate>={$where ['begintime']} and logdate<={$where ['endtime']}";
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
  //    echo $sql;
      $query = $this->db_sdk->query($sql);
      if ($query) return $query->result_array();
      return array();
  
  }
  
  
  
  
  public function elite($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
  $sql = "select $field from u_behavior_{$where['date']}  where act_id=142 and 1=1";
      if ($server_list) {
          $sql .= " AND a.serverid IN($server_list_new)";
      }
      if ($where ['serverids']) {
          $sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
      }
      
      if ($where ['channels']) {
          $sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
      }
      
      
      if ($where ['user_level']) {
          $sql .= " AND user_level={$where ['user_level']}";
      }
      if ($group) {
          $sql .= " group by $group";
      }
      if ($order) {
          $sql .= " order by $order";
      }
      if ($limit) {
          $sql .= " limit $limit";
      }
 //echo $sql;
      $query = $this->db_sdk->query ( $sql );
      
  
  
      $result = array ();
      if ($query) {
          $result = $query->result_array ();
      }
  
      return $result;
  }
  

  public function elite_treasure($table = '', $where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '') {
      $sql = "select $field from u_behavior_{$where['date']}  where  1=1";
      if ($server_list) {
          $sql .= " AND a.serverid IN($server_list_new)";
      }
      if ($where ['serverids']) {
          $sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
      }  
      if ($where ['channels']) {
          $sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
      }
  
  
      if ($where ['user_level']) {
          $sql .= " AND user_level={$where ['user_level']}";
      }
      if ($group) {
          $sql .= " group by $group";
      }
      if ($order) {
          $sql .= " order by $order";
      }
      if ($limit) {
          $sql .= " limit $limit";
      }
// echo $sql;
      $query = $this->db_sdk->query ( $sql );
  
      $result = array ();
      if ($query) {
          $result = $query->result_array ();
      }
  
      return $result;
  }

        /*
     * 周任务链后台统计 zzl 20171027
     */
    public function mission($table = '', $where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '')
    {
        $itable = "item_trading_{$where['date']}";
        $utable = "u_behavior_{$where['date']}";
        $u_register = "u_register";
        $sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id  where act_id=151 ";
        
        if ($where['serverids']) {
            $sql .= " AND u.serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql .= " AND u.channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ($where['typeids']) {
            $sql .= " AND u.act_id IN(" . implode(',', $where['typeids']) . ")";
        }
        if ($where['type']) {
            $sql .= " AND i.type = {$where['type']}";
        }
        
        if ($group) {
            $sql .= " group by $group";
        }
      
        $sql2 = "SELECT count(if(param=1,true,null)) p1,count(if(param=2,true,null)) p2,count(if(param=3,true,null)) p3,
      count(if(param=4,true,null)) p4,vip_level from u_behavior_{$where['date']} u  where  act_id=152";
        
		$sql3 = "SELECT count(distinct(accountid)) as v,vip_level from u_behavior_{$where['date']} u  where  act_id=152 or act_id=151";
	  
		$sql4 = " SELECT
					count(IF(tot = 1, TRUE, NULL)) p1,
					count(IF(tot = 2, TRUE, NULL)) p2,
					count(IF(tot = 3, TRUE, NULL)) p3,
					count(IF(tot = 4, TRUE, NULL)) p4,
					count(IF(tot = 5, TRUE, NULL)) p5,
					count(IF(tot = 6, TRUE, NULL)) p6,
					count(IF(tot = 7, TRUE, NULL)) p7,
					count(IF(tot = 8, TRUE, NULL)) p8,
					count(IF(tot = 9, TRUE, NULL)) p9,
						v
				from (
				SELECT
					vip_level as v,
					count(accountid) as tot
				FROM
					u_behavior_{$where['date']} u
				WHERE
					act_id = 152 or act_id=151";



        if ($where['serverids']) {
            $sql2 .= " AND u.serverid IN(" . implode(',', $where['serverids']) . ")";
            $sql3 .= " AND u.serverid IN(" . implode(',', $where['serverids']) . ")";
            $sql4 .= " AND u.serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql2 .= " AND u.channel IN(" . implode(',', $where['channels']) . ")";
            $sql3 .= " AND u.channel IN(" . implode(',', $where['channels']) . ")";
            $sql4 .= " AND u.channel IN(" . implode(',', $where['channels']) . ")";
        }
        $sql2 .= "  group by vip_level";        
		$sql3 .= "  group by vip_level";        
		$sql4 .= "  GROUP BY v,accountid) bb GROUP BY v";        
   
        $query = $this->db_sdk->query($sql);
        if ($query) {
            $result = $query->result_array();
        }
        
        $query2 = $this->db_sdk->query($sql2);
        $query3 = $this->db_sdk->query($sql3);
        $query4 = $this->db_sdk->query($sql4);
        if ($query2) {
            $result['param'] = $query2->result_array();
        }
		
		if ($query3) {
            $result['span'] = $query3->result_array();
        }
		
		if ($query4) {
            $result['vel'] = $query4->result_array();
        }
        
        return $result;
    }
    
	/*
    *  一键狩猎统计   banjin 2017-12-8
    */
	public function hunting( $where = array(), $field = '*', $group = 'vip_level')
	{
		$itable = "item_trading_{$where['date']}";
		$utable = "u_behavior_{$where['date']}";
		$u_register = "u_register";
		$sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id  where act_id=166 ";

		if ($where['serverids']) {
			$sql .= " AND u.serverid IN(" . implode(',', $where['serverids']) . ")";
		}
		if ($where['channels']) {
			$sql .= " AND u.channel IN(" . implode(',', $where['channels']) . ")";
		}
		if ($where['typeids']) {
			$sql .= " AND u.act_id IN(" . implode(',', $where['typeids']) . ")";
		}
		if ($where['type']) {
			$sql .= " AND i.type = {$where['type']}";
		}

		if ($group) {
			$sql .= " group by $group";
		}

		$sql2 = "SELECT count(distinct(accountid)) as v,vip_level from u_behavior_{$where['date']} u  where  act_id=165";

		$sql3 = "SELECT count(distinct(accountid)) as s,vip_level from u_behavior_{$where['date']} u  where  act_id = 166";

		if ($where['serverids']) {
			$sql2 .= " AND u.serverid IN(" . implode(',', $where['serverids']) . ")";
			$sql3 .= " AND u.serverid IN(" . implode(',', $where['serverids']) . ")";
		}
		if ($where['channels']) {
			$sql2 .= " AND u.channel IN(" . implode(',', $where['channels']) . ")";
			$sql3 .= " AND u.channel IN(" . implode(',', $where['channels']) . ")";
		}
		$sql2 .= "  group by vip_level";
		$sql3 .= "  group by vip_level";

		$query = $this->db_sdk->query($sql);
		if ($query) {
			$result = $query->result_array();
		}

		$query2 = $this->db_sdk->query($sql2);
		$query3 = $this->db_sdk->query($sql3);
		if ($query2) {
			$result['param'] = $query2->result_array();
		}

		if ($query3) {
			$result['span'] = $query3->result_array();
		}

		return $result;
	}
	
    /*
     * 精灵塔简单模式 zzl 20171027
     */
    public function  fairyTower($table = '', $where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = ''){


   /*   $sql1 = "SELECT count(IF(user_level<=10 && act_id=143,true,null)) as c1,count(IF(user_level>=11 && user_level<=20 && act_id=143,true,null)) as c2,count(IF(user_level>=21 && user_level<=30 && act_id=143,true,null)) as c3,count(IF(user_level>=31 && user_level<=40 && act_id=143,true,null)) as c4,count(IF(user_level>=41 && user_level<=50 && act_id=143,true,null)) as c5,count(IF(user_level>=51 && user_level<=60 && act_id=143,true,null)) as c6,count(IF(user_level>=61 && user_level<=70 && act_id=143,true,null)) as c7,count(IF(user_level>=71 && user_level<=80 && act_id=143,true,null)) as c8,count(IF(user_level>=81 && user_level<=90 && act_id=143,true,null)) as c9,count(IF(user_level>=91 && act_id=143,true,null)) as c10
        FROM u_behavior_{$where['date']} where 1=1"; */
        $sql1="SELECT  act_id,floor(user_level/10) as level,count(DISTINCT accountid) cnt FROM u_behavior_{$where['date']} where  act_id=143 ";
        
        if ($where['serverids']) {
            $sql1 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql1 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ($where['typeids']) {
            $sql1 .= " AND act_id IN(" . implode(',', $where['typeids']) . ")";
        }
        if ($where['type']) {
            $sql1 .= " AND type = {$where['type']}";
        }
       
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql1 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
        
        $sql1 .= " group by accountid";
        
     //  echo   $sql1;  die;
        
/*        $sql2 ="select act_id,param,CEILING(param/5) as pa,count(IF(user_level<=10 && act_id=144,true,null)) as c1,count(IF(user_level>=11 && user_level<=20 && act_id=144,true,null)) as c2,count(IF(user_level>=21 && user_level<=30 && act_id=144,true,null)) as c3,count(IF(user_level>=31 && user_level<=40 && act_id=144,true,null)) as c4,count(IF(user_level>=41 && user_level<=50 && act_id=144,true,null)) as c5,count(IF(user_level>=51 && user_level<=60 && act_id=144,true,null)) as c6,count(IF(user_level>=61 && user_level<=70 && act_id=144,true,null)) as c7,count(IF(user_level>=71 && user_level<=80 && act_id=144,true,null)) as c8,count(IF(user_level>=81 && user_level<=90 && act_id=144,true,null)) as c9,count(IF(user_level>=91 && act_id=144,true,null)) as c10  FROM u_behavior_{$where['date']}
        where  act_id=144
        ";  */
       $sql2="SELECT  CEILING(param/5) as param,act_id,floor(user_level/10) as user_level,count(DISTINCT accountid) cnt FROM u_behavior_{$where['date']} where  act_id=144";
        
        if ($where['serverids']) {
            $sql2 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql2 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql2 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
      $sql2 .= " group by  CEILING(param/5)";
        
        
        
        $sql2_2="SELECT  CEILING(param/5) as param,act_id,floor(user_level/10) as user_level,count(DISTINCT accountid) cnt FROM u_behavior_{$where['date']} where  act_id=144";
        
        if ($where['serverids']) {
            $sql2_2 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql2_2 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql2_2 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
      $sql2_2 .= " group by  CEILING(param/5),floor(user_level/10)";
 
        
        
     //    $sql2 .= " group by CEILING(param/5)";
         
   //   echo   $sql2;die;
         
   /*       $sql3 ="select act_id,param,SUBSTRING(param1, -1) as pa,count(IF(user_level<=10 && act_id=145,true,null)) as c1,count(IF(user_level>=11 && user_level<=20 && act_id=145,true,null)) as c2,count(IF(user_level>=21 && user_level<=30 && act_id=145,true,null)) as c3,count(IF(user_level>=31 && user_level<=40 && act_id=145,true,null)) as c4,count(IF(user_level>=41 && user_level<=50 && act_id=145,true,null)) as c5,count(IF(user_level>=51 && user_level<=60 && act_id=145,true,null)) as c6,count(IF(user_level>=61 && user_level<=70 && act_id=145,true,null)) as c7,count(IF(user_level>=71 && user_level<=80 && act_id=145,true,null)) as c8,count(IF(user_level>=81 && user_level<=90 && act_id=145,true,null)) as c9,count(IF(user_level>=91 && act_id=145,true,null)) as c10  FROM u_behavior_{$where['date']}
         where  act_id=145
         "; */
/*         $sql3="SELECT  CEILING(param/5) as pa,param,act_id,floor(user_level/10) as user_level,count(DISTINCT accountid) cnt FROM u_behavior_{$where['date']} where  act_id=145";
         if ($where['serverids']) {
             $sql3 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
         }
         if ($where['channels']) {
             $sql3 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
         }
         if ( $where ['viplev_min']  && $where ['viplev_max']) {
             $sql3 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
         }
         $sql3 .= " group by accountid"; */
        
        $sql3="SELECT param,SUBSTRING(param1,-1) as subpa,act_id,floor(user_level/10) as user_level,count(DISTINCT accountid) cnt FROM u_behavior_{$where['date']} where  act_id=145";
        if ($where['serverids']) {
            $sql3 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql3 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql3 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
      $sql3 .= " group by param,SUBSTRING(param1,-1)";
        
        
        $sql3_2="SELECT param,SUBSTRING(param1,-1) as subpa,act_id,floor(user_level/10) as user_level,count(DISTINCT accountid) cnt FROM u_behavior_{$where['date']} where  act_id=145";
        if ($where['serverids']) {
            $sql3_2 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql3_2 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql3_2 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
    $sql3_2 .= " group by param,SUBSTRING(param1,-1),floor(user_level/10)";
        
        
        
      //   $sql3 .= " group by param,SUBSTRING(param1, -1)";
          
 
        $query = $this->db_sdk->query($sql1);
        if ($query) {
            $result['143'] = $query->result_array();
        }
        
        $query2 = $this->db_sdk->query($sql2);
        if ($query2) {
            $result['144'] = $query2->result_array();
        }
        
        $query2_2 = $this->db_sdk->query($sql2_2);
        if ($query2_2) {
            $result['144_2'] = $query2_2->result_array();
        }
        
        $query3 = $this->db_sdk->query($sql3);
        if ($query3) {
            $result['145'] = $query3->result_array();
        }
        
        $query3_2 = $this->db_sdk->query($sql3_2);
        if ($query3_2) {
            $result['145_2'] = $query3_2->result_array();
        }
        
        return $result;
        
    }
  
    /*
     * Lugia
     */
    public function  Lugia($table = '', $where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = ''){
    
   
        $sql1 = " select accountid,viplev from u_login_{$where['date']}
        where lev>=40"; 
    
        if ($where['serverids']) {
            $sql1 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql1 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ($where['typeids']) {
            $sql1 .= " AND act_id IN(" . implode(',', $where['typeids']) . ")";
        }
        if ($where['type']) {
            $sql1 .= " AND type = {$where['type']}";
        }
         
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql1 .= " AND viplev >= {$where['viplev_min']} and viplev <= {$where['viplev_max']}";
        }
         
        $sql1 .= " group by accountid";

        $sql_135_1="select accountid,id,vip_level  from u_behavior_{$where['date']}  where (act_id=135 or act_id=139) ";
    
        if ($where['serverids']) {
            $sql_135_1 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql_135_1 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql_135_1 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
        $sql_135_1 .= " group by accountid";

        
        $sql_138_1="select accountid,id,vip_level  from u_behavior_{$where['date']}  where act_id=138";
        
        if ($where['serverids']) {
            $sql_138_1 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql_138_1 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql_138_1 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
         
        $sql_138_1 .= " group by accountid";
         
         

        $sql_138_2="select accountid,id,vip_level  from u_behavior_{$where['date']}  where act_id=138";
         
        if ($where['serverids']) {
            $sql_138_2 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql_138_2 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql_138_2 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
     
        $sql_138_3="SELECT b.accountid,b.id,b.vip_level,sum(i.item_num) as sum_num FROM u_behavior_{$where['date']} b inner join item_trading_{$where['date']} i on i.behavior_id=b.id where  b.act_id=138 and i.item_id=3 and i.type=1";
         
        if ($where['serverids']) {
            $sql_138_3 .= " AND b.serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql_138_3 .= " AND b.channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql_138_3 .= " AND b.vip_level >= {$where['viplev_min']} and b.vip_level <= {$where['viplev_max']}";
        }
        $sql_138_3 .= " group by b.accountid";
 
   
        $sql_139="SELECT b.accountid,b.id,b.vip_level,sum(i.item_num) as sum_num FROM u_behavior_{$where['date']} b inner join item_trading_{$where['date']} i on i.behavior_id=b.id where  b.act_id=139 and i.item_id=3 and i.type=1";
         
        if ($where['serverids']) {
            $sql_139 .= " AND b.serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql_139 .= " AND b.channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql_139 .= " AND b.vip_level >= {$where['viplev_min']} and b.vip_level <= {$where['viplev_max']}";
        }
        $sql_139 .= " group by b.accountid";
     
        
        $sql_137="SELECT b.accountid,b.id,b.vip_level,sum(i.item_num) as sum_num FROM u_behavior_{$where['date']} b inner join item_trading_{$where['date']} i on i.behavior_id=b.id where  b.act_id=137 and i.item_id=3 and i.type=1";
         
        if ($where['serverids']) {
            $sql_137 .= " AND b.serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql_137 .= " AND b.channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql_137 .= " AND b.vip_level >= {$where['viplev_min']} and b.vip_level <= {$where['viplev_max']}";
        }
        $sql_137 .= " group by b.accountid";
        

        $sql_135_139_one="SELECT param,param1,COUNT(DISTINCT accountid) cnt,vip_level FROM u_behavior_{$where['date']} WHERE act_id=135 or act_id=139";
         
        if ($where['serverids']) {
            $sql_135_139_one .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql_135_139_one .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql_135_139_one .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
        $sql_135_139_one .= " group by  param";
        
        
        $sql_135_139_two="SELECT param,param1,COUNT(DISTINCT accountid) cnt,vip_level FROM u_behavior_{$where['date']} WHERE act_id=135 or act_id=139";
         
        if ($where['serverids']) {
            $sql_135_139_two .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql_135_139_two .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql_135_139_two .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
        $sql_135_139_two .= " group by  param,vip_level";

        
          $sql_135_139_2="SELECT param,param1,COUNT(DISTINCT accountid) as cnt,vip_level FROM  u_behavior_{$where['date']} WHERE act_id=135";
           
          if ($where['serverids']) {
              $sql_135_139_2 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
          }
          if ($where['channels']) {
              $sql_135_139_2 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
          }
          if ( $where ['viplev_min']  && $where ['viplev_max']) {
              $sql_135_139_2 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
          }
             $sql_135_139_2 .= " GROUP BY param,param1";
 
       
             $sql_135_139_2_2="SELECT param,param1,COUNT(DISTINCT accountid) as cnt,vip_level FROM  u_behavior_{$where['date']} WHERE act_id=135";
              
             if ($where['serverids']) {
                 $sql_135_139_2_2 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
             }
             if ($where['channels']) {
                 $sql_135_139_2_2 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
             }
             if ( $where ['viplev_min']  && $where ['viplev_max']) {
                 $sql_135_139_2_2 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
             }
             $sql_135_139_2_2 .= " GROUP BY param,param1,vip_level";
             
        
        
      $sql_136="select accountid,id,vip_level  from u_behavior_{$where['date']}  where act_id=136 ";
     
      if ($where['serverids']) {
            $sql_136 .= " AND serverid IN(" . implode(',', $where['serverids']) . ")";
        }
        if ($where['channels']) {
            $sql_136 .= " AND channel IN(" . implode(',', $where['channels']) . ")";
        }
        if ( $where ['viplev_min']  && $where ['viplev_max']) {
            $sql_136 .= " AND vip_level >= {$where['viplev_min']} and vip_level <= {$where['viplev_max']}";
        }
   

        $query = $this->db_sdk->query($sql1);
        if ($query) {
            $result['2'] = $query->result_array();
        } else {
            $result['2']=array();
        }
    
        $query_135_1 = $this->db_sdk->query($sql_135_1);
        if ($query_135_1) {
            $result['135_1'] = $query_135_1->result_array();
        }
        
       $query_138_1 = $this->db_sdk->query($sql_138_1);
        if ($query_138_1) {
            $result['138_1'] = $query_138_1->result_array();
        }
        
        $query_138_2 = $this->db_sdk->query($sql_138_2);
        if ($query_138_2) {
            $result['138_2'] = $query_138_2->result_array();
        }
        
        $query_138_3 = $this->db_sdk->query($sql_138_3);
        if ($query_138_3) {
            $result['138_3'] = $query_138_3->result_array();
        } else {
            $result['138_3']=array();
        }
        
        $query_139 = $this->db_sdk->query($sql_139);
        if ($query_139) {
            $result['139'] = $query_139->result_array();
        } else {
            $result['139']=array();
        }
        
        $query_137 = $this->db_sdk->query($sql_137);
        if ($query_137) {
            $result['137'] = $query_137->result_array();
        } else {
            $result['137']=array();
        }
        
        $query_135_139_one = $this->db_sdk->query($sql_135_139_one);
        if ($query_135_139_one) {
            $result['135_139_one'] = $query_135_139_one->result_array();
        }  
        
    
        
        $query_135_139_two = $this->db_sdk->query($sql_135_139_two);
        if ($query_135_139_two) {
            $result['135_139_two'] = $query_135_139_two->result_array();
        }
        
   
        
        $query_135_139_2 = $this->db_sdk->query($sql_135_139_2);
        if ($query_135_139_2) {
            $result['135_139_2'] = $query_135_139_2->result_array();
        }
        
        
        $query_135_139_2_2 = $this->db_sdk->query($sql_135_139_2_2);
        if ($query_135_139_2_2) {
            $result['135_139_2_2'] = $query_135_139_2_2->result_array();
        }
        
        
        $query_136 = $this->db_sdk->query($sql_136);
        if ($query_136) {
            $result['136'] = $query_136->result_array();
        } 
  
        return $result;
    
    }
    
    
    public  function champion($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = ''){
        

          $sql = "select count(DISTINCT accountid) as total,serverid from u_behavior_{$where['date']}  where act_id=108 and param>0  and 1=1";
    //    $sql = "select $field from u_behavior_{$where['date']}  where act_id=108 and 1=1";
   
        if ($where ['serverids']) {
            $sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
        }
        
        if ($where ['channels']) {
            $sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
        }
        
        if ($where ['vip_level']) {
            $sql .= " AND vip_level={$where ['vip_level']}";
        }
        if ($where ['user_level']) {
            $sql .= " AND user_level={$where ['user_level']}";
        }
        if ($group) {
            $sql .= " group by $group";
        }
        if ($order) {
            $sql .= " order by $order";
        }
        if ($limit) {
            $sql .= " limit $limit";
        }

        $sql_2 = "select count(DISTINCT accountid) as total,serverid,param1,param from u_behavior_{$where['date']}  where act_id=108 and 1=1";
         
        if ($where ['serverids']) {
            $sql_2 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
        }
        
        if ($where ['channels']) {
            $sql_2 .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
        }
        
        if ($where ['vip_level']) {
            $sql_2 .= " AND vip_level={$where ['vip_level']}";
        }
        if ($where ['user_level']) {
            $sql_2 .= " AND user_level={$where ['user_level']}";
        }
        if ($group) {
            $sql_2 .= " group by serverid,param1";
        }
        if ($order) {
            $sql_2 .= " order by $order";
        }
        if ($limit) {
            $sql_2 .= " limit $limit";
        }
  
        
        $query = $this->db_sdk->query ( $sql );       
        
        $result = array ();
        if ($query) {
            $result = $query->result_array ();
        }
        
        $query2 = $this->db_sdk->query ( $sql_2 );
        
       
        if ($query2) {
            $result['more'] = $query2->result_array ();
        }
        
        
        return $result;
        
        
    } 
    
    
    
    
}