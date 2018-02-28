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
  
}