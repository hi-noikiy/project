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
}