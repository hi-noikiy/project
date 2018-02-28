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
    public function __construct()
    {
        parent::__construct();
        $this->db_sdk = $this->load->database('sdk', TRUE);
    }
    /**
     * 技能专精数据分享
     *
     * @author 王涛 20170531
     */
    public function DataAnalysis($table='',$where=array(),$field='*',$group='',$order='',$limit='')
    {
    	if(!$field){
    		$field = '*';
    	}
    	
    	$sql = "select $field from $table where 1=1";
    	
    	if ($where ['beginserver'] && $where ['endserver']) {
    		$server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );
    		$server_list_new = '1';
    		if ($server_list) {
    			foreach ( $server_list->result_array () as $k => $v ) {
    					
    				$server_list_new .= $v ['serverid'] . ',';
    			}
    			$server_list_new = rtrim ( $server_list_new, ',' );
    		}      	
    	}
    	
    	if ($server_list_new) {
    		$sql .= " AND serverid IN($server_list_new)";
    	}
    	if($where['beginviplev']){
    		$sql .= " and viplev >= {$where['beginviplev']}";
    	}
    	if($where['endviplev']){
    		$sql .= " and viplev <= {$where['endviplev']}";
    	}
    	if($where['begindate']){
    		$sql .= " and logdate >= {$where['begindate']}";
    	}
    	if($where['enddate']){
    		$sql .= " and logdate <= {$where['enddate']}";
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
     * 椰蛋树活动
     *
     * @author 王涛 20170515
     */
    public function egg($where=array(),$field='*',$group='',$order='',$limit='')
    {
    	if(!$field){
    		$field = '*';
    	}
    	$sql = "select $field from game_egg where 1=1";
    	if($where['begindate']){
    		$sql .= " and logdate >= {$where['begindate']}";
    	}
    	if($where['enddate']){
    		$sql .= " and logdate <= {$where['enddate']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
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
    	//echo $sql;die;
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 社团
     *
     * @author 王涛 20170512
     */
    public function community($where=array(),$field='*',$group='',$order='',$limit='')
    {
    	if(!$field){
    		$field = '*';
    	}
    	$sql = "select $field from game_community where 1=1";
    	if($where['begindate']){
    		$sql .= " and logdate >= {$where['begindate']}";
    	}
    	if($where['enddate']){
    		$sql .= " and logdate <= {$where['enddate']}";
    	}
    	if($where['type']){
    		$sql .= " and type = {$where['type']}";
    	}
    	if($where['status']){
    		$sql .= " and status in ({$where['status']})";
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['beginh']){
    		$sql .= " AND from_unixtime(operate_time,'%H%i') between {$where['beginh']} and {$where['endh']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($where['c']){
    		$sql .= " having c in ({$where['c']})";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	if($limit){
    		$sql .= " limit $limit";
    	}
    	//echo $sql;die;
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    
    /**
     * 社团添加新功能
     *
     * @author zzl 20170725
     */
    public function communityMore($where=array(),$field='*',$group='',$order='',$limit='')
    {
    	if(!$field){
    		$field = '*';
    	}
    	$sql = "select $field from u_behavior_{$where['begindate']} where act_id=85 and 1=1"; 
  
    	
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
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
    		 $data['group']= $query->result_array();
    	}    	
    	
    	$query_total = $this->db_sdk->query("select count(DISTINCT(accountid)) as total,count(IF((LEFT(param,4))=1010,true,null)) as total_1010,count(IF((LEFT(param,4))=1011,true,null)) as total_1011 from u_behavior_{$where['begindate']} where act_id=85");
    	
    
    	if ($query_total) {
    		$data['total']= $query_total->result_array();
    	}
    	
    	$query_total_1010 =$this->db_sdk->query("SELECT count(DISTINCT(accountid)) as  total_1010 from u_behavior_{$where['begindate']} WHERE LEFT(param,4)=1010 and act_id=85");    	 
    	$total_1010=  $query_total_1010->result_array();
    	$data['total'][0]['total_1010']=$total_1010[0]['total_1010'];
    	 
    	$query_total_1011=$this->db_sdk->query("SELECT count(DISTINCT(accountid)) as  total_1011 from u_behavior_{$where['begindate']} WHERE LEFT(param,4)=1011 and act_id=85");
    	$total_1011=  $query_total_1011->result_array();
    	$data['total'][0]['total_1011']=$total_1011[0]['total_1011'];
    	
    	
    	if($data){
    		return $data;
    	}      	
    	return array();
    }
    
    
    /**
     * 匹配时长
     *
     * @author 王涛 20170511
     */
    public function match($where=array(),$field='*',$group='',$order='',$limit='')
    {
    	if(!$field){
    		$field = '*';
    	}
    	$Ym = date('Ym',strtotime($where['begindate']));
    	$sql = "select $field from game_match_$Ym where 1=1";
    	if($where['begindate']){
    		$sql .= " and logdate >= {$where['begindate']}";
    	}
    	if($where['enddate']){
    		$sql .= " and logdate <= {$where['enddate']}";
    	}
    	if($where['dan'] && $where['danend']){
    		$sql .= " and (dan >= {$where['dan']} and dan <= {$where['danend']})";
    	}
    	if($where['gametype']){
    		$sql .= " and type = {$where['gametype']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['matchtime']){
    		$sql .= " and matchtime = {$where['matchtime']}";
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
     * 掉线统计
     *
     * @author 王涛 20170315
     */
    public function drops($where=array(),$field='*',$group='',$order='',$limit='')
    {
    	if(!$field){
    		$field = '*';
    	}
    	$sql = "select $field from game_drops where 1=1";
    	if($where['begintime']){
    		$sql .= " and create_time >= {$where['begintime']}";
    	}
    	if($where['endtime']){
    		$sql .= " and create_time <= {$where['endtime']}";
    	}
    	if($where['client_version']){
    		$sql .= " and client_version in ({$where['client_version']})";
    	}
    	if($where['btype']){
    		$sql .= " and btype = {$where['btype']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['channels']){
    		$sql .= " AND channel IN(".implode(',', $where['channels']).")";
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
     * 通关流失
     *
     * @author 王涛 20170412
     */
    public function process($where=array(),$field='*',$group='',$order='',$limit='')
    {
    	if(!$field){
    		$field = '*';
    	}
    	$date = $where['date'];
    	//$date = 20170410;
    	$sql = "select $field from game_process_$date where account_id>0 ";
    	if(is_numeric($where['viplev_min'])){
    		$sql .= " and vip_level>={$where['viplev_min']}";
    	}
    	if(is_numeric($where['viplev_max'])){
    		$sql .= " and vip_level<={$where['viplev_max']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['outtime']){
    		$lastlogin = strtotime($date)-$where['outtime']*24*60*60;
    		$sql .= " AND login_time<{$lastlogin}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}

    	$sql .= " having pn>0";
    	if(is_numeric($where['chapter_min'])){
    		$sql .= " and mg>={$where['chapter_min']}";
    	}
    	if(is_numeric($where['chapter_max'])){
    		$sql .= " and mg<={$where['chapter_max']}";
    	}
    	if(is_numeric($where['ps'])){
    		$sql .= " and ps={$where['ps']}";
    	}
    	if($where['pn']){
    		$sql .= " and pn={$where['pn']}";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	if($limit){
    		$sql .= " limit $limit";
    	}
    	//echo $sql;die;
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 每层阵容详细
     *
     * @author 王涛 20170315
     */
    public function squaddetail($where=array(),$field='*',$group='',$order='',$limit='')
    {
    	if(!$field){
    		$field = '*';
    	}
    	$sql = "select $field from game_squad_eudemon where 1=1";
    	if($where['template_id']){
    			$sql .= " and template_id={$where['template_id']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND server_id IN(".implode(',', $where['serverids']).")";
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
     * 段位分布--基本
     *
     * @author 王涛 20170419
     */
    public function dan($where=array(),$field='*',$group='')
    {
    	$sql = "select $field from game_world_user_{$where['date']} where 1=1";
    
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['season']){
    		$sql .= " AND season ={$where['season']}";
    	}
    	if($where['viplev_min']){
    		$sql .= " and vip_level>={$where['viplev_min']}";
    	}
    	if($where['viplev_max']){
    		$sql .= " and vip_level<={$where['viplev_max']}";
    	}
    	if($group ){
    		$sql .= " group by $group";
    	}
    	if($where['ranklev']){
    		$sql .= " having ranklev ={$where['ranklev']}";
    	}
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 段位分布--图鉴
     *
     * @author 王涛 20170419
     */
    public function daneudemon($where=array(),$field='*',$group='')
    {
    	$sql = "select $field from game_world_eudemon_{$where['date']} a inner join game_world_user_{$where['date']} b on a.playerid=b.playerid and a.serverid=b.serverid where 1=1";
    
    	if($where['serverids']){
    		$sql .= " AND b.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['accountid']){
    		$sql .= " AND b.account_id IN(".$where['accountid'].")";
    	}
    	if($where['season']){
    		$sql .= " AND season ={$where['season']}";
    	}
    	if($where['viplev_min']){
    		$sql .= " and vip_level>={$where['viplev_min']}";
    	}
    	if($where['viplev_max']){
    		$sql .= " and vip_level<={$where['viplev_max']}";
    	}
    	if($group ){
    		$sql .= " group by $group";
    	}
    	if($where['ranklev']){
    		$sql .= " having ranklev ={$where['ranklev']}";
    	}
    	//echo $sql;die;
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /*
     * 亲密度平均值   zzl 20170925
     */
    
    public function getIntilv($where=array(),$field='*',$group='')
    {
        $sql = "select $field from game_world_eudemon_{$where['date']} a inner join game_world_user_{$where['date']} b on a.playerid=b.playerid and a.serverid=b.serverid where 1=1";
    
        if($where['serverids']){
            $sql .= " AND b.serverid IN(".implode(',', $where['serverids']).")";
        }
        if($where['accountid']){
            $sql .= " AND b.account_id IN(".$where['accountid'].")";
        }
        if($where['season']){
            $sql .= " AND season ={$where['season']}";
        }
        if($where['viplev_min']){
            $sql .= " and vip_level>={$where['viplev_min']}";
        }
        if($where['viplev_max']){
            $sql .= " and vip_level<={$where['viplev_max']}";
        }
        if($group ){
            $sql .= " group by $group";
        }
        if($where['ranklev']){
            $sql .= " having ranklev ={$where['ranklev']}";
        }
  
        $query = $this->db_sdk->query($sql);
        if ($query) {
            return $query->result_array();
        }
        return array();
    }
    
    
    
    /**
     * 推荐阵容
     *
     * @author 王涛 20170315
     */
    public function squad($where=array(),$field='*')
    {
    	$sql = "select $field from game_squad_eudemon a inner join (select template_id,min(totalpower) totalpower
    	from game_squad_eudemon group by template_id) b on a.template_id=b.template_id and a.totalpower=b.totalpower";
    	if($where['template_id']){
    		$sql .= " and b.template_id={$where['template_id']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND server_id IN(".implode(',', $where['serverids']).")";
    	}
    	 
    	$sql .= " GROUP BY b.template_id,b.totalpower";
    	$sql .= " order by b.template_id";

    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 固定精灵统计
     *
     * @author 王涛 20170308
     */
    public function eudemonCount($where=array(),$field='*',$group='',$order='')
    {
    	$sql = "select $field from u_eudemon where 1=1";
    	if($where['logdate']){
    		$sql .= " and logdate={$where['logdate']}";
    	}
    	if($where['eudemons']){
    		$sql .= " and eudemon in (".implode(',', $where['eudemons']).")";
    	}
    	if($where['serverids']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
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
     * 全球对战--精灵使用率
     *
     * @author 王涛 20170307
     */
    public function eudemonData($where=array(),$field='*',$group='',$order='')
    {
    	$Ym = '20'.substr($where['begintime'], 0,4);
    	//$Ymd = '20'.substr($where['begintime'], 0,6);
    	$sql = "select $field from game_data_$Ym gd inner join game_user_$Ym gu on gd.id=gu.gameid inner join game_user_eudemon_$Ym gue on gu.id=gue.gameuserid where 1=1";
    	if($where['begintime']){
    		$sql .= " and gd.endTime>={$where['begintime']}";
    	}
    	if($where['endtime']){
    		$sql .= " and gd.endTime<={$where['endtime']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND gu.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['eudemons']){
    		$sql .= " AND gue.eudemon IN({$where['eudemons']})";
    	}
    	if($where['accountid']){
    		$sql .= " and gu.accountid = {$where['accountid']}";
    	}
    	if($where['estatus']){
    		$sql .= " and gue.status  IN(".implode(',', $where['estatus']).")";
    	}
    	if($where['dan']){
    		$dandata = explode(',', $where['dan']);
    		if($dandata[0]){
    			$sql .= " and gu.dan >= {$dandata[0]}";
    		}
    		if($dandata[1]){
    			$sql .= " and gu.dan <= {$dandata[1]}";
    		}
    	}
    	if($where['btype']){
    		$sql .= " and gd.btype={$where['btype']}";
    	}
    	if(isset($where['type']) && $where['type'] != -1){
    		$sql .= " and gd.type={$where['type']}";
    	}
    	if($where['viplev_min']){
    		$sql .= " and gu.viplevel>={$where['viplev_min']}";
    	}
    	if($where['viplev_max']){
    		$sql .= " and gu.viplevel<={$where['viplev_max']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	//echo $sql;die;
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 全球对战--精灵详情
     *
     * @author 王涛 20170509
     */
    public function worldDataNew($where=array(),$field='*',$group='',$order='',$limit=120)
    {
    	$Ym = date('Ym',strtotime($where['date']));
    	$sql = "select $field from game_data_{$Ym} c inner join game_user_$Ym a on c.id=a.gameid inner join game_user_eudemon_$Ym b on a.id=b.gameuserid where gameid in(";
    	$sql .= "select gameid from game_data_{$Ym} gd inner join game_user_$Ym gu on gd.id=gu.gameid where btype=1";
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
    	if($group){
    		$sql .= " group by $group";
    	}
    	if($order){
    		$sql .= " order by $order";
    	}
    	$sql .= ")";
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
     * 全球对战--详情
     * 
     * @author 王涛 20170306
     */
    public function worldData($where=array(),$field='*',$group='',$order='',$limit='')
    {
    	$Ym = '20'.substr($where['begintime'], 0,4);
    	$sql = "select $field from game_user_data_$Ym a inner join (";
    	$sql .= "select gameid from game_data_$Ym gd inner join game_user_$Ym gu on gd.id=gu.gameid inner join game_user_eudemon_$Ym gue on gu.id=gue.gameuserid where 1=1";
    	if($where['begintime']){
    		$sql .= " and gd.endTime>={$where['begintime']}";
    	}
    	if($where['endtime']){
    		$sql .= " and gd.endTime<={$where['endtime']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND gu.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['btype']){
    		$sql .= " and gd.btype={$where['btype']}";
    	}
    	if($where['accountid']){
    		$sql .= " and gu.accountid = {$where['accountid']}";
    	}
    	if($where['eudemons']){
    		$sql .= " AND gue.eudemon IN({$where['eudemons']})";
    	}
    	if($where['dan']){
    		$dandata = explode(',', $where['dan']);
    		if($dandata[0]){
    			$sql .= " and gu.dan >= {$dandata[0]}";
    		}
    		if($dandata[1]){
    			$sql .= " and gu.dan <= {$dandata[1]}";
    		}
    	}
    	if(isset($where['type']) && $where['type'] != -1){
    		$sql .= " and gd.type={$where['type']}";
    	}
    	if($where['viplev_min']){
    		$sql .= " and gu.viplevel>={$where['viplev_min']}";
    	}
    	if($where['viplev_max']){
    		$sql .= " and gu.viplevel<={$where['viplev_max']}";
    	}
    	$sql .= " group by gameid";
    	if($order){
    		$sql .= " order by $order";
    	}
    	$sql .= ")b on a.gameid=b.gameid";
    	if($limit){
    		$sql .= " limit $limit";
    	}
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result_array();
    	}
    	return array();
    }
    private function common_lev()
    {
        return <<<SQL
        CASE
            WHEN lev<10 THEN 1
            WHEN lev>10 and lev<=20 then 2
            WHEN lev>20 and lev<=30 then 3
            WHEN lev>30 and lev<=40 then 4
            WHEN lev>40 and lev<=50 then 5
            WHEN lev>50 and lev<=60 then 6
            WHEN lev>60 and lev<=70 then 7
            WHEN lev>70 and lev<=80 then 8
            WHEN lev>80 and lev<=90 then 9
            WHEN lev>90 and lev<=100 then 10
        ELSE 11
        END as `lev`
SQL;
    }
    /**
     * 拼装where
     *
     * @param $date1
     * @param $date2
     * @param $serverid
     * @param $channel
     * @param $viplev
     * @param $viplev_max
     * @return string
     */
    private function common_where($appid, $date1, $date2, $serverid,
                                  $channel,$viplev=0,$viplev_max=0)
    {
        $date1 = str_replace('-','', $date1);
        $date2 = str_replace('-','', $date2);
        $where = " WHERE `appid`={$appid}"; // AND `date`<=$date2
        if($date1!=$date2 && $date1<$date2) {
            $where .= " AND `log_date`>={$date1} AND `log_date`<=$date2";
        }
        else {
            $where .= "  AND `log_date`={$date1}";
        }
        if (is_numeric($serverid) && $serverid>0) $where .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $where .= " AND serverid IN(".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $where .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $where .= " AND channel IN(".implode(',', $channel).")";
        if ($viplev>0) $where .= " AND viplev>=$viplev";
        if ($viplev_max>0) $where .= " AND viplev<=$viplev_max";
        return $where;
    }

    /**
     * 活跃度统计
     *
     * @param $appid
     * @param $date1
     * @param $date2
     * @param $serverid
     * @param $channel
     * @param int $viplev
     * @param int $viplev_max
     * @param int $lev
     * @param int $lev_max
     * @return object
     */
    public function PlayerActive($appid, $date1, $date2, $serverid, $channel,
                                 $viplev=0, $viplev_max=0, $lev=0,$lev_max=0)
    {
        $where = $this->common_where($appid, $date1, $date2, $serverid, $channel);

        if ($lev>0) $where .= " AND lev>=$lev";
        if ($lev_max>0) $where .= " AND lev<=$lev_max";
        $sql = <<<SQL
SELECT log_date,count(accountid) as user_count,
case
    WHEN active<1 THEN 0
    WHEN active>0 and active<=29 then 1
    WHEN active>29 and active<=59 then 2
    WHEN active>59 and active<=89 then 3
    WHEN active>89 and active<=119 then 4
    WHEN active>119 and active<=149 then 5
else 6
end as `active_level`
FROM u_player_active $where GROUP BY log_date,active_level ORDER BY log_date ASC,`active_level` ASC
SQL;
        //echo $sql;
        $query = $this->db_sdk->query($sql);
        if ($query) {
            return $query->result();
        }
        return false;
    }

    /**
     * 通用货币新
     *
     * @param $appid
     * @param $date1
     * @param $serverid
     * @param $channel
     * @param int $viplev
     * @param int $viplev_max
     * @param int $daction
     * @return bool
     * 
     * @author 王涛 20170104
     */
    public function CommonCurrencyNew($appid, $date1, $serverid, $channel,
    		$viplev=0, $viplev_max=0)
    {
    	$where = $this->common_where($appid, $date1, $date1, $serverid, $channel,$viplev, $viplev_max);
    	$sql = <<<SQL
SELECT log_date,count(distinct(accountid)) as user_count,SUM(amount) as total_amount,daction,item_type,
CASE
        WHEN lev<10 THEN 1
        WHEN lev>10 and lev<=20 then 2
        WHEN lev>20 and lev<=30 then 3
        WHEN lev>30 and lev<=40 then 4
        WHEN lev>40 and lev<=50 then 5
        WHEN lev>50 and lev<=60 then 6
        WHEN lev>60 and lev<=70 then 7
        WHEN lev>70 and lev<=80 then 8
        WHEN lev>80 and lev<=90 then 9
    ELSE 10
    END as `newlev` FROM u_common_currency $where GROUP BY newlev,`item_type`,daction
SQL;
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		return $query->result();
    	}
    	return false;
    }
    /**
     * 通用货币
     *
     * @param $appid
     * @param $date1
     * @param $serverid
     * @param $channel
     * @param int $viplev
     * @param int $viplev_max
     * @param int $daction
     * @return bool
     */
    public function CommonCurrency($appid, $date1, $serverid, $channel,
                                   $viplev=0, $viplev_max=0, $daction=1)
    {
        $where = $this->common_where($appid, $date1, $date1, $serverid, $channel,$viplev, $viplev_max);
        $where .= " AND daction=$daction";
        //先汇总消耗和获取
        $sql = <<<SQL
SELECT log_date,count(accountid) as user_count,SUM(amount) as total_amount,daction,
CASE
        WHEN lev<10 THEN 1
        WHEN lev>10 and lev<=20 then 2
        WHEN lev>20 and lev<=30 then 3
        WHEN lev>30 and lev<=40 then 4
        WHEN lev>40 and lev<=50 then 5
        WHEN lev>50 and lev<=60 then 6
        WHEN lev>60 and lev<=70 then 7
        WHEN lev>70 and lev<=80 then 8
        WHEN lev>80 and lev<=90 then 9
        WHEN lev>90 and lev<=100 then 10
    ELSE 11
    END as `lev` FROM u_common_currency $where GROUP BY lev
SQL;
        $sql = <<<SQL
      SELECT log_date,item_type,count(accountid) as user_count,SUM(amount) as total_amount,daction,
       `lev` FROM u_common_currency $where GROUP BY lev,`item_type` ORDER by lev
SQL;
        //echo $sql;
        $query = $this->db_sdk->query($sql);
        if ($query) {
            return $query->result();
        }
        return false;
    }
    /**
 	 * 玩法次数统计
 	 *
 	 * @param type
 	 * @return void
	 */

    public function PlayingMethod($appid, $date1, $serverid, $channel,
                                   $viplev=0, $viplev_max=0)
    {
        $where = $this->common_where($appid, $date1, $serverid, $channel,$viplev, $viplev_max);
        $common_lev = $this->common_lev();
        $sql   = <<<SQL
        select count(accountid) as user_count,method,sum(`playing_times`) as playing_times,
        sum(`playing_time`) as playing_time,$common_lev from u_playing_method
        $where GROUP by lev,method order by lev asc
SQL;
        $query = $this->db_sdk->query($sql);
        if ($query) {
            return $query->result();
        }
        return false;

    }
    /**
 	 * 精灵星级统计
 	 *
 	 * @param type
 	 * @return object|bool
	 */
    public function ElfStarLev($appid, $date1, $date2, $serverid, $channel)
    {
        $where = $this->common_where($appid, $date1, $date2, $serverid, $channel);
        $sql = <<< SQL
        SELECT viplev,count(accountid) as user_count,SUM(lev) as lev,
        sum(elf_1) as elf_1,sum(elf_2) as elf_2,sum(elf_3) as elf_3,
        sum(elf_4) as elf_4,sum(elf_5) as elf_5,sum(elf_6) as elf_6
        from u_elf_starlev $where group by viplev
        order by viplev asc
SQL;
        $query = $this->db_sdk->query($sql);
        if($query) {
            return $query->result();
        }
        return false;
    }

    /**
     * 图鉴等级
     *
     * @param $appid
     * @param $date1
     * @param $date2
     * @param $serverid
     * @param $channel
     * @return bool
     */
    public function PhotoLevel($appid, $date1, $date2, $serverid, $channel)
    {
        $where = $this->common_where($appid, $date1, $date2, $serverid, $channel);
        $sql = <<< SQL
        SELECT viplev,count(accountid) as user_count,SUM(lev) as lev,
        sum(pht_1) as pht_1,sum(pht_2) as pht_2,sum(pht_3) as pht_3,
        sum(pht_4) as pht_4,sum(pht_5) as pht_5,sum(pht_6) as pht_6
        from u_photo_level $where group by viplev
        order by viplev asc
SQL;
        $query = $this->db_sdk->query($sql);
        if($query) {
            return $query->result();
        }
        return false;
    }

    /**
 	 * 关卡进度统计
 	 *
 	 * @param type
 	 * @return bool|object
	 */

    public function LevelProgress($appid, $date1, $date2, $serverid, $channel,$viplev_min, $viplev_max)
    {
        $where = $this->common_where($appid, $date1, $date2, $serverid, $channel,$viplev_min, $viplev_max );
        $common_lev = $this->common_lev();
        $sql = <<<SQL
        select count(accountid) as user_count,sum(fighting) as fighting,
        sum(nomal_copy) as nomal_copy,sum(nomal_elite) as nomal_elite,
        $common_lev
        from u_elf_starlev $where group by lev
        order by lev asc
SQL;
        // echo $sql;
        $query = $this->db_sdk->query($sql);
        if($query) {
            return $query->result();
        }
        return false;
    }

    /**
 	 * 关卡难易度统计
 	 *
 	 * @param type
 	 * @return bool|object
	 */
    public function LevelDifficulty($appid, $date1, $date2, $serverid, $channel,$viplev_min, $viplev_max, $copy_type)
    {
        $where = $this->common_where($appid, $date1, $date2, $serverid, $channel,$viplev_min, $viplev_max);
        $where .= " and copy_type=$copy_type";
        //参与人数
        $sql_total = "select level_id,count(accountid) as user_count from u_level_difficulty $where group by level_id";
        $query = $this->db_sdk->query($sql_total);
        if (!$query) return false;
        $data = [];
        $data['total_user'] = $query->result();
        $query->free_result();
        //3星通关人数和挑战至三星的次数
        $sql_3star = "select level_id,count(accountid) as user_count,sum(max_star_times) as max_star_times from u_level_difficulty $where and max_star=3 group by level_id";
        $query = $this->db_sdk->query($sql_3star);
        if ($query) {
            $data['3star_pass'] = $query->result();
        }
        else {
            $data['3star_pass'] = false;
        }
        $query->free_result();
        //首次3，2，1星通关人数
        $sql_first_star = "select level_id,count(accountid) as user_count,star from u_level_difficulty $where and is_first_pass=1 group by level_id,star";
        $query = $this->db_sdk->query($sql_first_star);
        // var_dump($query);
        // echo $sql_first_star;
        if ($query) {
            $data['star_first_pass'] = $query->result();
        }
        else {
            $data['star_first_pass'] = false;
        }
        $query->free_result();
        //首次挑战失败
        $sql_first_fail = "select count(accountid) as user_count,level_id from u_level_difficulty $where and is_first_pass=0 group by level_id";
        $query = $this->db_sdk->query($sql_first_fail);
        if ($query) {
            $data['star_first_fail'] = $query->result();
        }
        else {
            $data['star_first_fail'] = false;
        }
        $query->free_result();
        //平均失败次数?如何计算
        $sql_avg_fail   = "select level_id,sum(failure_times) as failure_times from u_level_difficulty $where group by level_id";
        $query = $this->db_sdk->query($sql_avg_fail);
        if ($query) {
            $data['avg_fail_times'] = $query->result();
        }
        else {
            $data['avg_fail_times'] = false;
        }
        $query->free_result();
        //非扫荡玩家等级／战力
        $sql_fight_level = "select level_id,count(accountid) as user_count,sum(avg_fighting) as avg_fighting,sum(avg_lev) as avg_level from u_level_difficulty $where group by level_id";
        $query = $this->db_sdk->query($sql_fight_level);
        if ($query) {
            $data['fight_level'] = $query->result();
        }
        else {
            $data['fight_level'] = false;
        }
        $query->free_result();
        return $data;
    }
    
    /*
     * 全球对战-战斗回合数统计  zzl 20170814
     */
    
    public function combatBout($where=array(),$field='*',$group='',$order='')
    {
    	$Ym = '20'.substr($where['begintime'], 0,4);    
    	$sql = "select $field from game_data_$Ym gd inner join game_user_$Ym gu on gd.id=gu.gameid where gu.status=1 and  1=1";
    	if($where['begintime']){
    		$sql .= " and gd.endTime>={$where['begintime']}";
    	}
    	if($where['endtime']){
    		$sql .= " and gd.endTime<={$where['endtime']}";
    	}
    	if($where['serverids']){
    		$sql .= " AND gu.serverid IN(".implode(',', $where['serverids']).")";
    	}
    	if($where['eudemons']){
    		$sql .= " AND gue.eudemon IN({$where['eudemons']})";
    	}
    	if($where['accountid']){
    		$sql .= " and gu.accountid = {$where['accountid']}";
    	}
    	if($where['estatus']){
    		$sql .= " and gue.status  IN(".implode(',', $where['estatus']).")";
    	}
    	
    	if($where['dan_s'] && $where['dan_e']){
    		$sql .= " and gu.dan>={$where['dan_s']} and gu.dan<={$where['dan_e']}";
    	}
    	
    	
    	if($where['dan']){
    		$dandata = explode(',', $where['dan']);
    		if($dandata[0]){
    			$sql .= " and gu.dan >= {$dandata[0]}";
    		}
    		if($dandata[1]){
    			$sql .= " and gu.dan <= {$dandata[1]}";
    		}
    	}
    	if($where['btype']){
    		$sql .= " and gd.btype={$where['btype']}";
    	}
    	if(isset($where['type']) && $where['type'] != -1){
    		$sql .= " and gd.type={$where['type']}";
    	}
    	if($where['viplev_min']){
    		$sql .= " and gu.viplevel>={$where['viplev_min']}";
    	}
    	if($where['viplev_max']){
    		$sql .= " and gu.viplevel<={$where['viplev_max']}";
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
    /*
     * 匹配时间，连表加上vip条件
     */
    public function matchNew($where=array(),$field='*',$group='',$order='',$limit='')
    {
        if(!$field){
            $field = '*';
        }
        $Ym = date('Ym',strtotime($where['begindate']));
   
        $sql = "select m.matchtime,count(*) c from game_match_$Ym m,(SELECT viplev,accountid from u_last_login)l WHERE 1=1";
        
        if($where['begindate']){
            $sql .= " and m.logdate >= {$where['begindate']}";
        }
        if($where['enddate']){
            $sql .= " and m.logdate <= {$where['enddate']}";
        }
        if($where['dan'] && $where['danend']){
            $sql .= " and (m.dan >= {$where['dan']} and m.dan <= {$where['danend']})";
        }
        if($where['gametype']){
            $sql .= " and m.type = {$where['gametype']}";
        }
        if($where['serverids']){
            $sql .= " AND m.serverid IN(".implode(',', $where['serverids']).")";
        }
        if($where['matchtime']){
            $sql .= " and m.matchtime = {$where['matchtime']}";
        }
        if ($where ['viplev_min'] && $where ['viplev_max']) {
            $sql .= " AND (viplev>={$where ['viplev_min']} and viplev<={$where ['viplev_max']} )";
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
}
