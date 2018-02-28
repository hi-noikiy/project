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
	public function community($where=array(),$field='',$group='date',$order = "date")
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
  
/*       if($group){
          $sql .= " group by $group";
      }
  
      if($order){
          $sql .= " order by $order";
      }
      if($limit){
          $sql .= " limit $limit";
      } */
 echo $sql;
      $query = $this->db->query($sql);
      if ($query) return $query->result_array();
      return array();
  
  }
	
}