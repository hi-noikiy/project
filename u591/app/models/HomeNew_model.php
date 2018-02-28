<?php


class HomeNew_model extends CI_Model
{
	public function __construct()
	{

	$this->db=	$this->load->database('sdk', TRUE);
	}

    /**
     * 汇总统计
     *
     * @author auction
     */
    public function auction($table, $where, $field, $group, $order, $limit)
    {
        $sql = "SELECT id,server,serverid,award_type,award_itemtype,award_amount,max_offermoney,`group` FROM auction WHERE logdate={$where['date']}";
        if ($group) {
            $sql .= " group by $group";
        }
        if ($order) {
            $sql .= " order by $order";
        }
        
        $result = $this->db->query($sql);
        
        if ($result) {
            $result_data = $result->result_array();
        }
        
        return $result_data;
    }
	
    
    public function mac($table, $where, $field, $group, $order, $limit)
    {
    	$sql = "SELECT mac,username,serverid,userid,accountid  FROM u_roles WHERE mac='{$where['mac']}'";
    	if ($group) {
    		$sql .= " group by $group";
    	}
    	if ($order) {
    		$sql .= " order by $order";
    	}
    
    	$result = $this->db->query($sql);
    	
    
    
    	if ($result) {
    		$result_data = $result->result_array();
    	}
    
    	return $result_data;
    }
    
    
    
    

    public function payTotal($table, $where, $field, $group, $order, $limit)
    {
    	$sql = "select COUNT(DISTINCT l.accountid) pay_total from  u_paylog p INNER JOIN u_login_{$where['date_table']} l on l.accountid=p.accountid ";
    	if ($group) {
    		$sql .= " group by $group";
    	}
    	if ($order) {
    		$sql .= " order by $order";
    	}    
    	$result = $this->db->query($sql);    	
    
    
    	if ($result) {
    		$result_data = $result->result_array();
    	}
    
    	return $result_data;
    }
    
    
}