<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 兑换行为统计
* ==============================================
* @date: 2016-3-28
* @author: luoxue
* @version:
*/
class DisplayExchangeItem extends Display{
	private $where = '';
	private $group = '';
	private $limit = '';
	private $table = 'exchange_item';
	
	public function show($serverid, $limit, $offset){
		
		$this->limit = " LIMIT $limit, $offset";
	
		$beginTime = strtotime($this->bt);
		$endTime = strtotime($this->et)+24*60*60;
		
		$this->where = "WHERE exchange_time>='$beginTime' AND exchange_time<'$endTime'";
		
		if(!empty($serverid))
			$this->where .= is_array($serverid) ? " AND server_id IN(" . implode(',', $serverid).')': " AND server_id='".intval($serverid)."'";
		$this->group = "GROUP BY id DESC";
		
		$sql_cnt = "SELECT COUNT(*) FROM $this->table  $this->where";
		
		$stmt = $this->_db->prepare($sql_cnt);
		$stmt->execute();
		$total = $stmt->fetchAll(PDO::FETCH_COLUMN);
		
		$sql = "SELECT * FROM $this->table $this->where $this->group $this->limit";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		
		return array(
				'total' => array_shift($total),
				'list'  => $list,
		);
	}
}