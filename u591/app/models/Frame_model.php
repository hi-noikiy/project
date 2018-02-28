<?php


class Frame_model extends CI_Model
{
	public function __construct()
	{
		//$this->load->database();
	    $this->db = $this->load->database('sdk', TRUE);
	}
	/*
	 * 社区
	 */
	public function community($where,$field='',$group='date',$order = "date")
	{
	
    // $sql = "SELECT $field from game_community where status = 5 and  type = 4";  
	 
	    
 if(  $where['classify']==1){
	     $sql = "SELECT $field from game_egg where 1=1";
	     }
	     else 
	    
	   {
	        $sql = "SELECT $field from game_community where status = 5 and  type = 4";  
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
      if($where ['logdate']){
          $sql .= " AND logdate={$where ['logdate']} ";
      }
  

      $query = $this->db->query($sql);
      if ($query) return $query->result_array();
      return array();
  
  }

    public function lugiaDetail($where, $field = '', $group = 'date', $order = "date")
    {
        $sql = "SELECT item_id,sum(item_num) as item_num  FROM u_behavior_{$where['date']} a,item_trading_{$where['date']} b WHERE act_id=136 and a.id=b.behavior_id and b.type=0 GROUP BY item_id";
        
        $query = $this->db->query($sql);
        if ($query)
            return $query->result_array();
        return array();
    }

    public function sixteen($where, $field = '', $group = 'date', $order = "date")
    {
    $sql = "SELECT u.accountid,u.param1,u.serverid,l.username,u.vip_level,u.user_level FROM u_behavior_{$where['date']} u inner join  u_login_{$where['date']} l where act_id=108 and u.param>0 and u.accountid=l.accountid and u.serverid={$where['serverid']}";
  
        
        $query = $this->db->query($sql);
        if ($query)
            return $query->result_array();
        return array();
    }
  
    
    public function rotomClassdistribution($where, $field = '', $group = 'date', $order = "date")
    {
        if($where['class']==100){
            $sql = "SELECT serverid,COUNT(DISTINCT accountid) cnt from intimacy_{$where['date_table']} where logdate={$where['date']} and   Rotom_class is not null group by serverid";
             
        } else {
            
            $sql = "SELECT serverid,COUNT(DISTINCT accountid) cnt  from intimacy_{$where['date_table']} where logdate={$where['date']} and  Rotom_class={$where['class']} and  Rotom_class is not null group by serverid";
             
        }
      
 
     $query = $this->db->query($sql);
        if ($query)
       return         $result= $query->result_array();
        
         
           
    }
    
    public function intensifyByClass($where, $field, $group)
    {
    	if (! $field) {
    		$field = '*';
    	}
    
    
    	
    	if($where['class']!=100){
    		$sql_3 = "SELECT vip_level,avg(Rotom_intensify) avg from intimacy_{$where['date_table']} where Rotom_class={$where['class']}";
    	}  else {
    		
    		$sql_3 = "SELECT vip_level,avg(Rotom_intensify) avg from intimacy_{$where['date_table']} where Rotom_class is not null";
    	}
    	
    
    	
    	
    	
    
    	if( $where['date'] ){
    		$sql_3 .= " AND logdate={$where ['date']}";
    	}
    	
    		$sql_3 .= " group by vip_level";
    	
    	
    	if ($order) {
    		$sql_3 .= " order by $order";
    	}
    
    		
    
    	$this->db_sdk = $this->load->database ( 'sdk', TRUE );
    
    		
    	$query_3 = $this->db_sdk->query ( $sql_3 );
    	
  //  echo	$this->db_sdk->last_query();
    	if ($query_3) {
    		$result= $query_3->result_array ();
    	}
    
    		
    		
    	if($result) return  $result;
    	return array();
    }
    
    
    public function rotomVipdistribution($where, $field = '', $group = 'date', $order = "date")
    {
        if($where['class']==100){
            $sql = "SELECT serverid,COUNT(DISTINCT accountid) cnt, vip_level from intimacy_{$where['date_table']} where logdate={$where['date']} and  Rotom_class is not null group by vip_level";
             
        } else {
            $sql = "SELECT serverid,COUNT(DISTINCT accountid) cnt, vip_level from intimacy_{$where['date_table']} where logdate={$where['date']} and Rotom_class={$where['class']} and  Rotom_class is not null group by vip_level";
             
        
        }
    
    
        $query = $this->db->query($sql);
        if ($query)
            return $query->result_array();
            return array();
    }
    
    public function imageDetail($where, $field = '', $group = 'date', $order = "date")
    {
    
        $sql="select * from u_bugreport where id={$where['id']}";
    
    
        $query = $this->db->query($sql);
        if ($query)
            return $query->result_array();
            return array();
    }
    
    public function getMac($where, $field = '', $group = 'date', $order = "date")
    {
    	if (! $field) {
    		$field = '*';
    	}
    
    	$sql = "SELECT mac from  u_apple_paylog  WHERE id='".$where['mac']."'";
    
    
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
    
    
    public function blackCard($where, $field = '', $group = 'date', $order = "date")
    {
		if (! $field) {
			$field = '*';
		}
	
		$sql = "SELECT mac,accountid,userid,serverid,username from  u_apple_login_{$where['date_table']}  WHERE mac='".$where['mac']."'";
	
	
		
			$sql .= " group by accountid";
		
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
    
	public function skillList($where, $field = '', $group = 'date', $order = "date")
	{
		if (! $field) {
			$field = '*';
		}
	
		$sql = "SELECT eudemon,count(skills1) s1,count(skills2) s2,count(skills3) s3,count(skills4) s4,skills1,skills2,skills3,skills4,count(*) total from  game_user_eudemon_{$where['date_table']}  WHERE eudemon='".$where['eudemon']."'";
	
	
	
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
	
	
  
}