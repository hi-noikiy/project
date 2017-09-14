<?php

namespace Api\Model;
use Think\Model;

class SeatModel extends Model {
	
	
	/**
	 * 查看座位
	 */
	function findSectionId($map){
		$seat=M('cinemaHallSeat')->where($map)->find();
		if(empty($seat)||empty($seat['sectionId'])){
			$data['findSectionId']='0';
			$data['findSectionName']='普通区';
		}else{
			$data['findSectionId']=$seat['sectionId'];
			$data['findSectionName']=$seat['sectionName'];
		}
		return $data;
	}
	/**
	 * 座位信息
	 */
	function seatInfos($seats) {
		$mcol=0;
		$mincol=10;
		$mrow=0;
		$minrow=10;
		foreach ($seats as $value) {
			if(empty($value['sectionId'])){
				$value['sectionId']='0';
				$value['sectionName']='普通区';
			}
			$seatss[$value['sectionId']][]=$value;
		}
		$y=0;
		foreach ($seatss as $v){
			foreach ($v as $value){
				if(empty($rows[$value['GraphRow']+$y])&&$rows[$value['GraphRow']+$y]!='0'){
					$rows[$value['GraphRow']+$y]=$value['SeatRow'];
				}
				$seat[$value['GraphRow']+$y][$value['GraphCol']]=$value;
				$seat[$value['GraphRow']+$y][$value['GraphCol']]['GraphRow']=$value['GraphRow']+$y;
				if($mcol<$value['GraphCol']){
					$mcol=$value['GraphCol'];
				}
				if($mrow<$value['GraphRow']+$y){
					$mrow=$value['GraphRow']+$y;
				}
				if($minrow>$value['GraphRow']){
					$minrow=$value['GraphRow'];
				}
				if($mincol>$value['GraphCol']){
					$mincol=$value['GraphCol'];
				}
			}
			$y=$mrow;
		}
		for($i=$minrow;$i<=$mrow;$i++){
			for($j=$mincol;$j<=$mcol;$j++){
				if(!$seat[$i][$j]){
					//$seat[$i][$j]=1;
					$seat[$i][$j]['SeatState']=2;
					$seat[$i][$j]['GraphRow']=$i;
					$seat[$i][$j]['GraphCol']=$j;
				}
			}
			ksort($seat[$i]);
		}
		for($i=$minrow;$i<=$mrow;$i++){
			if(empty($rows[$i])&&$rows[$i]!='0'){
				$rows[$i]='';
			}
		}
		ksort($rows);
		ksort($seat);

// 		(
// 		[SeatState] => 0
// 		[SeatNo] => 31
// 		[SeatRow] => 1
// 		[SeatCol] => 08
// 		[GraphRow] => 2
// 		[GraphCol] => 1
// 		[groupCode] =>
// 		[sectionId] => 0000000000000001
// 		[sectionName] => 普通区
// 		)
		$flag = true;
		$tempKey = 1;
		foreach ($rows as $key => $value) {
			if($flag){
				for ($i = 1; $i<$key; $i++){
					$rows[$i] = -1;
					$tempKey ++;
				}
				$flag = false;
			}
			for ($i = $key; $i < $tempKey; $i++) { 
				$rows[$i] = 0;
				$tempKey++;
			}
		}
		ksort($rows);
		$flag = true;

		foreach ($seat as $k=>$v){
			if($flag){
				for ($i = 1; $i<$k; $i++){
					for ($j = 0; $j<$mcol; $j++){
						$seat[$i][$j] = array(
								'SeatState' => 2,
								'SeatNo' => '',
								'SeatRow' => $i,
								'SeatCol' => $j,
								'GraphRow' => $i,
								'GraphCol' => $j,
						);
					}
				}
				$flag = false;
			}
			
			$seat[$k] = array_values($v);
		}
		ksort($seat);
		$seat = array_values($seat);
		$rows = array_values($rows);
		$seatinfo['row']=$rows;
		$seatinfo['seat']=$seat;
		return $seatinfo;
	}
}