<?php

namespace Web\Model;
use Think\Model;

class SeatModel extends Model {
	
	function remain($seats){
		$c=0;
		foreach ($seats as $value){
			if($value['SeatState']=='0'){
				$c++;
			}
		}
		return $c;
	}
	/**
	 * 查看座位
	 */
	function findSectionId($map){
		$seat=M('cinemaHallSeat')->where($map)->find();
		// echo M('cinemaHallSeat')->_sql();
		if(empty($seat)||empty($seat['sectionId'])){
			$data['findSectionId']='01';
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
				$value['sectionId']='01';
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
					$seat[$i][$j]=1;
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
		$seatinfo['row']=$rows;
		$seatinfo['seat']=$seat;
		return $seatinfo;
	}
}