<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 充值
* ==============================================
* @date: 2016-3-28
* @author: luoxue
* @version:
*/
class PayNew {
	public $_db;
	protected $where;
	
	
	public function __construct(PDO $db, $bt=null, $et=null, $serverids , $channel) {
		$this->_db = $db;
		
		$bt = isset($_GET['bt'])? $_REQUEST["bt"] : date('Y-m-d', strtotime('-7 days'));
		$et = isset($_GET['et'])? $_REQUEST["et"] : date('Y-m-d');	
        $startTime = $bt . ' 00:00:00';
        $endTime   = $et . ' 23:59:59';
        $where = "WHERE Add_Time >= '$startTime' And Add_Time <= '$endTime' AND rpCode in ('1','10')";
      
        if(is_array($serverids) && !empty($serverids))
        	$where .= " AND ServerID IN(" . implode(',', $serverids).')';
     	elseif (is_numeric($serverids)) 
      		$where .= " AND ServerID=$serverids";
     	if(is_numeric($channel) && $channel > 0)
			$where .= " AND CPID='$channel'";
     	
		print_r($fenbaoids);
		
     	$this->where = $where;
	}
	
	public function  payArea(){
		//print_r($this->where);
		$areaArr = array(
				'1~5', '6~20', '21~40', '41~100', '101~200', '201~500', '501~1000', '1001~2000', '2001~5000', 
				'5001~10000', '10001~20000', '20001~50000', '50001~100000', '100001~200000', '200001',
		);
		$areaArr2 = array(
				'1~9', '10~59', '60~99', '100~199', '200~299', '300~399', '400~499', '500~999', '1000~1999', 
				'2000~4999', '5000~9999','10000',
		);
		$areaArr3 = array(
				'1~29', '30~68', '69~99', '100~199', '200~299', '300~399', '400~499', '500~999', '1000~1999', 
				'2000~2999','3000~4999', '5000',
		);
		$sql = "select sum(PayMoney) as total_money from pay_log $this->where group by PayID order by total_money asc";
		//print_r($sql);
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$newArr = $newArr2 =$newArr3 = array();
		
		foreach ($list as $v){
			for ($i = 0; $i < count($areaArr); $i++){
				$nk = $areaArr[$i];
				$v1_v2 = explode('~', $nk);
				
				if(count($v1_v2) == 1){
					if($v['total_money'] >= $v1_v2[0]){
						$newArr[$nk]['sum_money'] += $v['total_money'];
						$newArr[$nk]['sum_count'] += 1;
					}
				}else {
					if($v['total_money'] >= $v1_v2[0] && $v['total_money'] <=$v1_v2[1] ){
						$newArr[$nk]['sum_money'] += $v['total_money'];
						$newArr[$nk]['sum_count'] += 1;
					}
				}
			}
			for ($i = 0; $i < count($areaArr2); $i++){
				$nk = $areaArr2[$i];
				$v1_v2 = explode('~', $nk);
				
				if(count($v1_v2) == 1){
					if($v['total_money'] >= $v1_v2[0]){
						$newArr2[$nk]['sum_money'] += $v['total_money'];
						$newArr2[$nk]['sum_count'] += 1;
					}
				}else {
					if($v['total_money'] >= $v1_v2[0] && $v['total_money'] <=$v1_v2[1] ){
						$newArr2[$nk]['sum_money'] += $v['total_money'];
						$newArr2[$nk]['sum_count'] += 1;
					}
				}
			}
			for ($i = 0; $i < count($areaArr3); $i++){
				$nk = $areaArr3[$i];
				$v1_v2 = explode('~', $nk);
				
				if(count($v1_v2) == 1){
					if($v['total_money'] >= $v1_v2[0]){
						$newArr3[$nk]['sum_money'] += $v['total_money'];
						$newArr3[$nk]['sum_count'] += 1;
					}
				}else {
					if($v['total_money'] >= $v1_v2[0] && $v['total_money'] <=$v1_v2[1] ){
						$newArr3[$nk]['sum_money'] += $v['total_money'];
						$newArr3[$nk]['sum_count'] += 1;
					}
				}
			}
		}
		
		$IosCredit = array(6, 30, 68, 168, 328, 648);
		$iosArr = array();
		$sql = "select PayMoney from pay_log $this->where";
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($list as $v){
			for ($i = 0; $i < count($areaArr); $i++){
				$nk = $IosCredit[$i];
				if($v['PayMoney'] == $nk){
					$iosArr[$nk]['sum_money'] += $v['PayMoney'];
					$iosArr[$nk]['sum_count'] += 1;
				}
			}
		}

		return array(
				'list'  => $newArr,
				'list2' => $newArr2,
				'list3' => $iosArr,
				'list4' => $newArr3,
		);
	}
	
}