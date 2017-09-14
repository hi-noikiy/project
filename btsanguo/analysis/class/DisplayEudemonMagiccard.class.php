<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 武将奥义玩家个人情况 表(eudemon_magiccard)
 * ==============================================
 * @date: 2016-3-23
 * @author: luoxue
 * @version:
 */
class DisplayEudemonMagiccard extends Display{
	public $table = 'eudemon_magiccard';
	
	
	public function showMagiccar($serverid){
		$beginTime = strtotime($this->bt);
		$endTime = strtotime($this->et)+24*60*60;
		
		$where = "WHERE cur_time>='$beginTime' AND cur_time<'$endTime'";
		
		if (is_numeric($serverid) && $serverid>0)
			$where .= " AND server_id=$serverid";
			
		elseif (is_array($serverid) && count($serverid))
			$where .= " AND server_id IN(".implode(',', $serverid).")";
		
		/**
		 ** magiccardtype1, magiccardlev1,  magiccardqual1,
		 *	 第一张卡牌类型、升级等级、进阶等级.
		 *	  类型百分位=5(澄色)、千分位=1(金属性)、万分位=1(无敌)
		 */
		$sql = "select distinct(account_id) as account_id, level, viplev, magiccardtype1, magiccardlev1,  magiccardqual1,
					magiccardtype2, magiccardlev2,  magiccardqual2, magiccardtype3, magiccardlev3,  magiccardqual3
					from $this->table $where order by viplev desc";
		//print_r($sql);
		$vipLevArr = array();
		
		$cardLevArr = array('1~3', '4~4', '5~5', '6~6', '7~7', '8~8', '9~9','10~10');
		$qualLevArr = array('1~1', '2~2', '3~3', '4~4', '5~5');
		
		$ex = $this->_db->prepare($sql);
        $ex->execute();
        $list = $ex->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($list as $k => $v){
        	$threeTypeArr = array($v['magiccardtype1'], $v['magiccardtype2'], $v['magiccardtype3']);
        	$threeCardArr = array($v['magiccardlev1'], $v['magiccardlev2'], $v['magiccardlev3']);
        	$threeQualArr = array($v['magiccardqual1'], $v['magiccardqual2'], $v['magiccardqual3']);
        	$vipLevArr[$v['viplev']]['count'] += 1;
        	for ($i = 0; $i < count($threeTypeArr); $i++){
        		if($threeTypeArr[$i]){
        			if(mb_substr($threeTypeArr[$i], 7, 1) == 5){
        				$vipLevArr[$v['viplev']]['orange'] += 1;  //橙卡数量
        				if(mb_substr($threeTypeArr[$i], 5, 1) == 1){
        					$vipLevArrr[$v['viplev']]['orange_wd'] += 1; //橙色无敌
        					if(mb_substr($threeTypeArr[$i], 6, 1) == 1)
        						$vipLevArrr[$v['viplev']]['metal_orange_wd'] += 1; //金属性橙色无敌
        				}
        			}
        			
        			for ($j = 0; $j < count($cardLevArr); $j++){
        				$nk = $cardLevArr[$j];
						$v1_v2 = explode('~', $nk);
						if($threeCardArr[$i] >= $v1_v2[0] && $threeCardArr[$i] <=$v1_v2[1] ){
        					$vipLevArr[$v['viplev']][$nk]['card'] += 1;
        					$vipLevArr[$v['viplev']][$nk]['card_lev'] += $threeCardArr[$i];				 
        				}
        			}
        		
        			for ($j = 0; $j < count($qualLevArr); $j++){
        				$nk2 = $qualLevArr[$j];
        				$v1_v2 = explode('~', $nk2);
        				if($threeQualArr[$i] >= $v1_v2[0] && $threeQualArr[$i] <=$v1_v2[1] ){
        					$vipLevArr[$v['viplev']][$nk2]['qual'] += 1;
        					$vipLevArr[$v['viplev']][$nk2]['qual_lev'] += $threeQualArr[$i];
        				}
        			}
        		}
        	}	
        }
		ksort($vipLevArr);
        return array(
        		'list'  => $vipLevArr,
        		'cardLevArr'  => $cardLevArr,
        		'qualLevArr' => $qualLevArr,
        );   
	}
}