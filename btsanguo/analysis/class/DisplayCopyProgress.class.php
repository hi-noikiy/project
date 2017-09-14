<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 关卡统计
* u_copy_progress(通关记录表)、ikkitousendata(一骑当千记录表)
* ==============================================
* @date: 2016-3-17
* @author: luoxue
* @version:
*/
class DisplayCopyProgress extends Display{
	
	public function showCopyProgressVip($serverid){
		$beginTime = strtotime($this->bt);
		$endTime = strtotime($this->et)+24*60*60;
		
		$where = "WHERE login_time>='$beginTime' AND login_time<'$endTime'";
		$condition = "WHERE curtime>='$beginTime' AND curtime<'$endTime'";
		
		if (is_numeric($serverid) && $serverid>0){
			$where .= " AND serverid=$serverid";
			$condition .= " AND server_id=$serverid";	
		}
		elseif (is_array($serverid) && count($serverid)) {
			$where .= " AND serverid IN(".implode(',', $serverid).")";
			$condition .= " AND server_id IN(".implode(',', $serverid).")";
		}
		$sql = <<<SQL
		SELECT count(distinct(accountid)) as count, viplevel, AVG(level) as level, AVG(combat) as combat, AVG(copy_normal) as copy_normal, AVG(copy_smart) as copy_smart, AVG(copy_evil) as copy_evil, AVG(throuh_normal) as throuh_normal, AVG(through_smart) as through_smart  
     	FROM u_copy_progress
        $where group by viplevel
        ORDER BY viplevel DESC
SQL;
		$ex = $this->_db->prepare($sql);
		$ex->execute();
		$list = $ex->fetchAll(PDO::FETCH_ASSOC);
		
		$sql2 = <<<SQL
		SELECT  count(distinct(account_id)) as count2, vip_lev, AVG(maxikk) as maxikk from ikkitousendata $condition group by vip_lev order by vip_lev DESC
SQL;
		
		$ex = $this->_db->prepare($sql2);
		$ex->execute();
		$info = $ex->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($list as $k => $v){
			foreach ($info as $key => $val){
				if($v['viplevel'] == $val['vip_lev']){
					$list[$k]['maxikk'] = $val['maxikk'];
					$list[$k]['count2'] = $val['count2'];
					break;
				}
			}
		}
		return array(
				'list'  => $list,
		);
		
	}
	//等级段区分
	public function showCopyProgressLevel($serverid){
		$beginTime = strtotime($this->bt);
		$endTime = strtotime($this->et)+24*60*60;
		
		$where = "WHERE login_time>='$beginTime' AND login_time<'$endTime'";
		$condition = "WHERE curtime>='$beginTime' AND curtime<'$endTime'";
		
		if (is_numeric($serverid) && $serverid > 0){
			$where .= " AND serverid=$serverid";
			$condition .= " AND server_id=$serverid";
		}
		elseif (is_array($serverid) && count($serverid)) {
			$where .= " AND serverid IN(".implode(',', $serverid).")";
			$condition .= " AND server_id IN(".implode(',', $serverid).")";
		}
	
		$sql = "SELECT accountid, level, combat, copy_normal,copy_smart, copy_evil, throuh_normal, through_smart FROM u_copy_progress $where ORDER BY level DESC";
		
		$ex = $this->_db->prepare($sql);
		$ex->execute();
		$list = $ex->fetchAll(PDO::FETCH_ASSOC);
		
		$sql2 = "SELECT  account_id, user_lev, maxikk  from ikkitousendata $condition  order by user_lev DESC";
		$ex = $this->_db->prepare($sql2);
		$ex->execute();
		$info = $ex->fetchAll(PDO::FETCH_ASSOC);
		
		$newArr = array(); // array('0~10'=>array('combat'=>0, 'combatcount'=>0.......))
		$area = array(
				'0~10', '11~20', '21~30', '31~40', '41~50', '51~60', '61~70', '71~80', '81~90', 
				'91~100', '101~110', '111~120', '121~130', '131~140', '141~150', '151~160', '161~170'
		);
		
		foreach ($list as $v){
			for ($i = 0; $i < count($area); $i++){
				$nk = $area[$i];
				$v1_v2 = explode('~', $nk);
				if($v['level'] >= $v1_v2[0] && $v['level'] <=$v1_v2[1] ){
					if(!@in_array($v['accountid'], $newArr[$nk]['account']))
						$newArr[$nk]['account'][] = $v['accountid'];	
					//战力
					$newArr[$nk]['combat'] += $v['combat'];
					$newArr[$nk]['combat_count'] += 1;
					//普本
					$newArr[$nk]['copy_normal'] += $v['copy_normal'];
					$newArr[$nk]['copy_normal_count'] += 1;
					//精本
					$newArr[$nk]['copy_smart'] += $v['copy_smart'];
					$newArr[$nk]['copy_smart_count'] += 1;
					//魔本
					$newArr[$nk]['copy_evil'] += $v['copy_evil'];
					$newArr[$nk]['copy_evil_count'] += 1;
					//普斩
					$newArr[$nk]['throuh_normal'] += $v['throuh_normal'];
					$newArr[$nk]['throuh_normal_count'] += 1;
					//精斩
					$newArr[$nk]['through_smart'] += $v['through_smart'];
					$newArr[$nk]['through_smart_count'] += 1;
				}	
			}
		}
		foreach ($info as $v){
			for ($i = 0; $i < count($area); $i++){
				$nk = $area[$i];
				$v1_v2 = explode('~', $nk);
				if($v['user_lev'] >= $v1_v2[0] && $v['user_lev'] <=$v1_v2[1] ){
					if(!@in_array($v['account_id'], $newArr[$nk]['account2']))
						$newArr[$nk]['account2'][] = $v['account_id'];
					//一骑当千
					$newArr[$nk]['maxikk'] += $v['maxikk'];
					$newArr[$nk]['maxikk_count'] += 1;
				}
			}
		}
		
		return array(
				'list'  => $newArr,
		);
	}
	
}