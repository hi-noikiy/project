<?php

class SystemFunctionNew_model  extends CI_Model
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
     */
    public function multiplayerMatchTime($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
    {
		if (! $field) {
			$field = '*';
		}
		$sql = "select $field from uesedmagic  where 1=1";
		if ($where ['userid']) {
			$sql .= " AND userid =" . $where ['userid'];
		}
		if ($where ['serverids']) {
			$sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		
		if ($where ['viplev_min'] && $where ['viplev_max']) {
		    $sql .= " AND (viplev>={$where ['viplev_min']} and viplev<={$where ['viplev_max']} )";
		}
		
		if ($where ['channels']) {
		    $sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}
		if( $where['date'] &&  $where['date2'] ){
		    $sql .= " AND (logdate>={$where ['date']} and logdate<={$where ['date2']})";
		}
	
		if ($group) {
			$sql .= " group by $group";
		}
		if ($order) {
		    $sql .= " order by $order";
		}

		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return array();
	}
  
	public function multiplayerBout($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
	{
	    if (! $field) {
	        $field = '*';
	    }
	    $sql = "select $field from uesedmagic  where 1=1";
	    if ($where ['userid']) {
	        $sql .= " AND userid =" . $where ['userid'];
	    }
	    if ($where ['serverids']) {
	        $sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	    }
	
	    if ($where ['vip_level']) {
	        $sql .= " AND (viplev>={$where ['viplev_min']} and viplev<={$where ['viplev_max']} )";
	    }
	
	    if ($where ['channels']) {
	        $sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
	    }
	    if( $where['date'] &&  $where['date2'] ){
	        $sql .= " AND (logdate>={$where ['date']} and logdate<={$where ['date2']})";
	    }
	
	    if ($group) {
	        $sql .= " group by $group";
	    }
	    if ($order) {
	        $sql .= " order by $order";
	    }
	    
	    $sql_2 = "select count(*) as total, turn_num from uesedmagic  where 1=1";
	    if ($where ['userid']) {
	        $sql_2 .= " AND userid =" . $where ['userid'];
	    }
	    if ($where ['serverids']) {
	        $sql_2 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	    }
	    
	    if ($where ['vip_level']) {
	        $sql_2 .= " AND (viplev>={$where ['viplev_min']} and viplev<={$where ['viplev_max']} )";
	    }
	    
	    if ($where ['channels']) {
	        $sql_2 .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
	    }
	    if( $where['date'] &&  $where['date2'] ){
	        $sql_2 .= " AND (logdate>={$where ['date']} and logdate<={$where ['date2']})";
	    }
	    
	    if ($group) {
	        $sql_2 .= " group by bout_flag";
	    }
	    if ($order) {
	        $sql_2 .= " order by $order";
	    }
	    
	    
	    
	    

	    $this->db_sdk = $this->load->database ( 'sdk', TRUE );
	    $query = $this->db_sdk->query ( $sql );
	    if ($query) {
	        $result= $query->result_array ();
	    }
	    
	    $query_2 = $this->db_sdk->query ( $sql_2 );
	    if ($query_2) {
	        $result['more']= $query_2->result_array ();
	    }
	    
	    if($result)
	    return $result;
	    return array();
	}
	
    
	public function multiplayerSkill($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
	{
	    if (! $field) {
	        $field = '*';
	    }

	    $sql = "SELECT u.magic_id,count(*) as total,s.name,s.system,s.target from  uesedmagic u inner JOIN s_magic_type s on u.magic_id=s.id where 1=1";
	
	

	    if( $where['date'] &&  $where['date2'] ){
	        $sql .= " AND (u.logdate>={$where ['date']} and u.logdate<={$where ['date2']})";
	    }
	
	    if ($group) {
	        $sql .= " group by $group";
	    }
	    if ($order) {
	        $sql .= " order by $order";
	    }
	
	    $this->db_sdk = $this->load->database ( 'sdk', TRUE );
	    $query = $this->db_sdk->query ( $sql );
	    if ($query) {
	        return $query->result_array ();
	    }
	    return array();
	}
	
	

	public function intensify($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
	{
	    if (! $field) {
	        $field = '*';
	    }
	
	    $sql = "SELECT $field from intimacy_{$where['date_table']} where Rotom_class is not null";
	
	    if( $where['date'] ){
	        $sql .= " AND logdate={$where ['date']}";
	    }
	
	    if ($group) {
	        $sql .= " group by Rotom_class";
	    }
	    if ($order) {
	        $sql .= " order by $order";
	    }
	    
	    
	    
	    $sql_2 = "SELECT $field from intimacy_{$where['date_table']} where Rotom_class is not null";
	    
	    

	    if( $where['date'] ){
	        $sql_2 .= " AND logdate={$where ['date']}";
	    }
	    
	    
	    if ($group) {
	        $sql_2 .= " group by Rotom_class,accountid";
	    }
	    if ($order) {
	        $sql_2 .= " order by $order";
	    }

	    $sql_3 = "SELECT avg(Rotom_intensify) avg from intimacy_{$where['date_table']} where Rotom_class is not null";
	
	    if( $where['date'] ){
	        $sql_3 .= " AND logdate={$where ['date']}";
	    }
	
	    if ($order) {
	        $sql_3 .= " order by $order";
	    }
	     
	    
	    
	    
	    
	
	    $this->db_sdk = $this->load->database ( 'sdk', TRUE );
	    $query = $this->db_sdk->query ( $sql );
	    if ($query) {
	       $result= $query->result_array ();
	    }
	    
	    $query_2 = $this->db_sdk->query ( $sql_2 );
	    if ($query_2) {
	        $result['more']= $query_2->result_array ();
	    }
	    
	    $query_3 = $this->db_sdk->query ( $sql_3 );
	    if ($query_3) {
	        $result['more2']= $query_3->result_array ();
	    }
	     
	    
	    
	    if($result) return  $result;
	    return array();
	}
	
	
	
	public function blackCard($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
	{
		if (! $field) {
			$field = '*';
		}
	
     	$sql = "SELECT id,COUNT(DISTINCT accountid) total,mac,accountid from  u_apple_paylog  where (created_at>={$where['date']} and created_at<={$where['date2']})";
	
	   	if($where['mac']){
	   		
	   		$sql .=" and mac='".$where['mac']."'";
	   	}
	   	
   	
    	$sql .= " group by  mac HAVING total>5";
   	
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
	
	
		$query = $this->db_sdk->query ( $sql );
		
		if ($query) {
			return $query->result_array ();
		}
		return array();
	}
	

	public function blackCardPayJoin($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
	{
		if (! $field) {
			$field = '*';
		}
	
		$sql = "SELECT COUNT(*) total,mac,accountid from  u_apple_login_{$where['date_table']}  group by mac";
	
	
	
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
	
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return array();
	}
	
	
	

	public function blackCardPayLogin($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
	{
		if (! $field) {
			$field = '*';
		}
	
	//	$sql = "SELECT COUNT(accountid) total,mac,accountid from  u_apple_login_{$where['date_table']}  WHERE mac='".$where['mac']."'";
	
		$sql ="SELECT a.mac,a.accountid,count(*) total  from    u_apple_login_{$where['date_table']} a ,(SELECT mac,COUNT(DISTINCT accountid) c from  u_apple_paylog group by mac HAVING c>5)b where a.mac=b.mac
		GROUP BY  a.mac,a.accountid ORDER BY total";
		
	

		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
	
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return array();
	}
	
	
	public function blackCardPay($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
	{
		if (! $field) {
			$field = '*';
		}
	
		$sql = "SELECT COUNT(DISTINCT accountid) total,mac,accountid from  u_apple_paylog  WHERE mac='".$where['mac']."'";
	

		if ($group) {
			$sql .= " group by $group";
		}
		if ($order) {
			$sql .= " order by $order";
		}
	
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return array();
	}
	public function skillRate($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}
		
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		
		for($i = 1; $i < 11; $i ++) {
			
			$sql = "SELECT sum(bout_num) as bout,magictype{$i} as skillid,sum(used_times{$i}) as total from skill_rate WHERE magictype{$i} is not null  ";
			
			if ($where ['date'] && $where ['date2']) {
				$sql .= " AND (logdate>={$where ['date']} and logdate<={$where ['date2']})";
			}
			
			if ($where ['combattype']) {
				$sql .= " AND type={$where ['combattype']}";
			}
			
			if ($where ['dan_s']) {
				$sql .= " AND (atkRank>={$where ['dan_s']} and atkRank<={$where ['dan_e']})";
			}
			
			$sql .= " group by magictype{$i}";
			
			$query = $this->db_sdk->query ( $sql );
			if ($query) {
				$result [$i] = $query->result_array ();
			}
		}
		
		if ($result) {
			return $result;
		} else {
			
			return array ();
		}
	}
	
	
	public function skillRateTotalBoul($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}
	
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
	
	
				
			$sql = "SELECT sum(bout_num) as total from skill_rate WHERE 1=1 ";
				
			if ($where ['date'] && $where ['date2']) {
				$sql .= " AND (logdate>={$where ['date']} and logdate<={$where ['date2']})";
			}
				
			if ($where ['combattype']) {
				$sql .= " AND type={$where ['combattype']}";
			}
				
			if ($where ['dan_s']) {
				$sql .= " AND (atkRank>={$where ['dan_s']} and atkRank<={$where ['dan_e']})";
			}
				
			$query = $this->db_sdk->query ( $sql );
			if ($query) {
				$result = $query->result_array ();
			}
	
	
		if ($result) {
			return $result;
		} else {
				
			return array ();
		}
	}
	
	
	public function transcript($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
	{
		if (! $field) {
			$field = '*';
		}	
		$sql = "SELECT count(DISTINCT accountid) cnt,vip_level,param,count(if(param1=0,true,null)) total_success,count(*) total,left(param,1) type,sum(right(param,1)) total_storey from u_behavior_{$where['date']} WHERE act_id=115 and left(param,1)={$where['behavior_type']}";

		if ($group) {
			$sql .= "  GROUP BY  vip_level";
		}
		if ($order) {
			$sql .= " order by $order";
		}

		
	    $sql2="SELECT vip_level,accountid,right(param,1) cnt  from u_behavior_{$where['date']} WHERE act_id=115 GROUP BY accountid";
		
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			$result= $query->result_array ();
		}		
		
		$query2 = $this->db_sdk->query ( $sql2 );
		if ($query2) {
			$result['more']= $query2->result_array ();
		}
		
		return $result;
	}
	
	
	public function danGrading($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
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
    		$sql .= " group by vip_level";
    	}
    	if($where['ranklev']){
    		$sql .= " having ranklev ={$where['ranklev']}";
    	}
  // echo $sql;	  	
    	
    	$sql2="select $field,com_ranklev from game_world_user_{$where['date']} where 1=1 group by $group";
    	
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		$result= $query->result_array();
    	}
    	
    	$query2 = $this->db_sdk->query($sql2);
    	if ($query2) {
    		$result['more']= $query2->result_array();
    	}
    	 
    	
    	
    	return $result;
    }
    
    public function danGradingGroup($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
    {
    	$sql = "select $field,level from game_world_user_{$where['date']} group by account_id";
    

    	$sql2="select COUNT(DISTINCT account_id) total from game_world_user_{$where['date']} ";
    	
  	$sql3="SELECT $field,UNIX_TIMESTAMP(NOW())-created_at as day_number from game_world_user_{$where['date']} g INNER JOIN u_register r on g.account_id=r.accountid GROUP BY g.account_id";
    	 
    	$query = $this->db_sdk->query($sql);
    	if ($query) {
    		$result= $query->result_array();
    	}
    	 
     	$query2 = $this->db_sdk->query($sql2);
    	if ($query2) {
    		$result['more']= $query2->result_array();
    	} 
    
    	$query3 = $this->db_sdk->query($sql3);
    	if ($query3) {
    		$result['more3']= $query3->result_array();
    	}    	 
    	return $result;
    }
    
    
    public function danDays($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
    {
    	$where['date_1']= strtotime($where ['date']);
    	
    	$where['date_2']= strtotime($where ['date'])+86400;
    
    	$sql = "SELECT $field,p.active from game_world_user_{$where['date']} g,(SELECT * from u_player_active WHERE log_date>={$where['date']}) p WHERE p.accountid=g.account_id ";
     
    	$query = $this->db_sdk->query($sql);
     
    	if ($query) {
    		$result= $query->result_array();
    	}
       return $result;

    }
    
    public function danSearch($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')
    {
    	$where['date_1']=	$where ['date'].'01';
        $where['date_2']=$where ['date'].'31';
     	$sql = "SELECT p.viplev,p.lev,p.active,COUNT(DISTINCT p.accountid) total,g.dan from game_user_{$where['date_table']} g,(SELECT * from u_player_active WHERE log_date>={$where['date_1']} and log_date<={$where['date_2']}) p WHERE p.accountid=g.accountid ";
    
    
     	if(	$where ['viplev_min'] && 	$where ['viplev_max']){
     		
     		$sql.=" and (p.viplev>={$where ['viplev_min']} and  p.viplev<={$where ['viplev_min']})";
     	}
     	
     	
     if($where ['lev_min'] && 	$where ['lev_max']){
     		
     		$sql.=" and (p.lev>={$where ['lev_min']} and  p.lev<={$where ['lev_max']})";
     	}
     	
  
     	if($where ['days']){
     		 
     		$sql.=" and p.active<={$where ['days']} ";
     	}
     	
     	
     	
     	
     	$sql .= "  GROUP BY g.dan";
   
    	$query = $this->db_sdk->query($sql);
    	
    	$this->db_sdk->last_query();
    	if ($query) {
    		$result= $query->result_array();
    	}
    

    	return $result;
    }
    
    
    public function serverStart($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = ''){
    	
    	$sql="select serverid,serverdate  from server_date where 1=1";
    
        if($where['server_start']){
        	
        	$sql.=" and serverdate>={$where['server_start']}";
        	
        }
        if($where['server_end']){
        	 
        	$sql.=" and serverdate<={$where['server_end']}";
        	 
        }
        
        $query = $this->db_sdk->query($sql);

   
        if ($query) {
        	$result= $query->result_array();
        }
        
        
        return $result;
    }
    
    
    public function wowPet($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = ''){
    	 

    	if($where ['statistics_type']==1){
    		 
    		$sql_1="SELECT sum(star) as total_level,star as name,template_id,exp_group,star,count(*) total,count(if(vip_level=0,true,null)) vip_level0,count(if(vip_level=1,true,null)) vip_level1,
count(if(vip_level=2,true,null)) vip_level2,count(if(vip_level=3,true,null)) vip_level3,count(if(vip_level=4,true,null)) vip_level4,
count(if(vip_level=5,true,null)) vip_level5,count(if(vip_level=6,true,null)) vip_level6,count(if(vip_level=7,true,null)) vip_level7,
count(if(vip_level=8,true,null)) vip_level8,count(if(vip_level=9,true,null)) vip_level9,
count(if(vip_level=10,true,null)) vip_level10,count(if(vip_level=11,true,null)) vip_level11,count(if(vip_level=12,true,null)) vip_level12,sum(if(vip_level=0,vip_level,null)) s0,sum(if(vip_level=1,vip_level,null)) s1,sum(if(vip_level=2,vip_level,null)) s2,sum(if(vip_level=3,vip_level,null)) s3,sum(if(vip_level=4,vip_level,null)) s4,sum(if(vip_level=5,vip_level,null)) s5,sum(if(vip_level=6,vip_level,null)) s6,sum(if(vip_level=7,vip_level,null)) s7,sum(if(vip_level=8,vip_level,null)) s8,sum(if(vip_level=9,vip_level,null)) s9,sum(if(vip_level=10,vip_level,null)) s10,sum(if(vip_level=11,vip_level,null)) s11,sum(if(vip_level=12,vip_level,null)) s12 from wow_pet where logdate>={$where ['date']} 
    		and logdate<={$where ['date2']} ";
    		
    		if($where['eudemon']){
    			$sql_1.=" and template_id={$where['eudemon']} ";
    		}
    		
    	    $sql_1.=" GROUP BY star";
    		
    		
    		$sql_2="SELECT star as name,template_id,exp_group,star,count(*) total,count(if(vip_level=0,true,null)) vip_level0,count(if(vip_level=1,true,null)) vip_level1,
    		count(if(vip_level=2,true,null)) vip_level2,count(if(vip_level=3,true,null)) vip_level3,count(if(vip_level=4,true,null)) vip_level4,
    		count(if(vip_level=5,true,null)) vip_level5,count(if(vip_level=6,true,null)) vip_level6,count(if(vip_level=7,true,null)) vip_level7,
    		count(if(vip_level=8,true,null)) vip_level8,count(if(vip_level=9,true,null)) vip_level9,
    		count(if(vip_level=10,true,null)) vip_level10,count(if(vip_level=11,true,null)) vip_level11,count(if(vip_level=12,true,null)) vip_level12,sum(if(vip_level=0,vip_level,null)) s0,sum(if(vip_level=1,vip_level,null)) s1,sum(if(vip_level=2,vip_level,null)) s2,sum(if(vip_level=3,vip_level,null)) s3,sum(if(vip_level=4,vip_level,null)) s4,sum(if(vip_level=5,vip_level,null)) s5,sum(if(vip_level=6,vip_level,null)) s6,sum(if(vip_level=7,vip_level,null)) s7,sum(if(vip_level=8,vip_level,null)) s8,sum(if(vip_level=9,vip_level,null)) s9,sum(if(vip_level=10,vip_level,null)) s10,sum(if(vip_level=11,vip_level,null)) s11,sum(if(vip_level=12,vip_level,null)) s12 from wow_pet where logdate>={$where ['date']}
    		and logdate<={$where ['date2']} ";
    		
    		
    		if($where['eudemon']){
    			$sql_2.=" and template_id={$where['eudemon']} ";
    		}
    		
    		$sql_2.=" GROUP BY exp_group,star";
    		
    	//echo $sql_1;
    		$query_1 = $this->db_sdk->query($sql_1);    		
    		if ($query_1) {
    			$result['star']= $query_1->result_array();
    		}
    		
    		
    		$query_2 = $this->db_sdk->query($sql_2);
    		if ($query_2) {
    			$result['exp_group']= $query_2->result_array();
    		}
    		
    		
    		
    		
    		return $result;
    	}
 
    	
    	if($where ['statistics_type']==2){
    		 
    		$sql_1="SELECT sum(strengthen_lev) as total_level,strengthen_lev as name,template_id,exp_group,star,count(*) total,count(if(vip_level=0,true,null)) vip_level0,count(if(vip_level=1,true,null)) vip_level1,
    		count(if(vip_level=2,true,null)) vip_level2,count(if(vip_level=3,true,null)) vip_level3,count(if(vip_level=4,true,null)) vip_level4,
    		count(if(vip_level=5,true,null)) vip_level5,count(if(vip_level=6,true,null)) vip_level6,count(if(vip_level=7,true,null)) vip_level7,
    		count(if(vip_level=8,true,null)) vip_level8,count(if(vip_level=9,true,null)) vip_level9,
    		count(if(vip_level=10,true,null)) vip_level10,count(if(vip_level=11,true,null)) vip_level11,count(if(vip_level=12,true,null)) vip_level12,sum(if(vip_level=0,vip_level,null)) s0,sum(if(vip_level=1,vip_level,null)) s1,sum(if(vip_level=2,vip_level,null)) s2,sum(if(vip_level=3,vip_level,null)) s3,sum(if(vip_level=4,vip_level,null)) s4,sum(if(vip_level=5,vip_level,null)) s5,sum(if(vip_level=6,vip_level,null)) s6,sum(if(vip_level=7,vip_level,null)) s7,sum(if(vip_level=8,vip_level,null)) s8,sum(if(vip_level=9,vip_level,null)) s9,sum(if(vip_level=10,vip_level,null)) s10,sum(if(vip_level=11,vip_level,null)) s11,sum(if(vip_level=12,vip_level,null)) s12 from wow_pet where logdate>={$where ['date']}
    		and logdate<={$where ['date2']}";
    	
    		if($where['eudemon']){
    			$sql_1.=" and template_id={$where['eudemon']} ";
    		}
    		
    		$sql_1.=" GROUP BY strengthen_lev";
    		
    	
    	
    		$query_1 = $this->db_sdk->query($sql_1);
    		if ($query_1) {
    			$result['total1']= $query_1->result_array();
    		}
    	
    	
    	
    	
    	
    	
    		return $result;
    	}
    
    	
    	if($where ['statistics_type']==3){
    		 
    		$sql_1="SELECT sum(upclass_lev) as total_level,upclass_lev as name,template_id,exp_group,star,count(*) total,count(if(vip_level=0,true,null)) vip_level0,count(if(vip_level=1,true,null)) vip_level1,
    		count(if(vip_level=2,true,null)) vip_level2,count(if(vip_level=3,true,null)) vip_level3,count(if(vip_level=4,true,null)) vip_level4,
    		count(if(vip_level=5,true,null)) vip_level5,count(if(vip_level=6,true,null)) vip_level6,count(if(vip_level=7,true,null)) vip_level7,
    		count(if(vip_level=8,true,null)) vip_level8,count(if(vip_level=9,true,null)) vip_level9,
    		count(if(vip_level=10,true,null)) vip_level10,count(if(vip_level=11,true,null)) vip_level11,count(if(vip_level=12,true,null)) vip_level12,sum(if(vip_level=0,vip_level,null)) s0,sum(if(vip_level=1,vip_level,null)) s1,sum(if(vip_level=2,vip_level,null)) s2,sum(if(vip_level=3,vip_level,null)) s3,sum(if(vip_level=4,vip_level,null)) s4,sum(if(vip_level=5,vip_level,null)) s5,sum(if(vip_level=6,vip_level,null)) s6,sum(if(vip_level=7,vip_level,null)) s7,sum(if(vip_level=8,vip_level,null)) s8,sum(if(vip_level=9,vip_level,null)) s9,sum(if(vip_level=10,vip_level,null)) s10,sum(if(vip_level=11,vip_level,null)) s11,sum(if(vip_level=12,vip_level,null)) s12	 from wow_pet where logdate>={$where ['date']}
    		and logdate<={$where ['date2']}";
    		 
    		 
    		if($where['eudemon']){
    			$sql_1.=" and template_id={$where['eudemon']} ";
    		}
    		
    		$sql_1.=" GROUP BY upclass_lev";
    		 
    		$query_1 = $this->db_sdk->query($sql_1);
    		if ($query_1) {
    			$result['total1']= $query_1->result_array();
    		}
    		 
    		 
    		 
    		 
    		 
    		 
    		return $result;
    	}
    
   
    }
    
    
    
    
    
    
    
    
    
    
    
    
 public function   magicType(){
   	$sql="SELECT id,type,name from s_magic_type";
   	$query = $this->db_sdk->query($sql);
   	if ($query) {
   	return 	$result= $query->result_array();
   	} else {
   		
   		return array();
   	}
   	
   }
    
}