<?php

namespace Api\Model;
use Think\Model;

class PlanModel extends Model {
	/**
	 * 正在热映影片信息
	 * @param string $sql
	 * @return unknown
	 */
	function getFilms($sql='1',$start=0,$limit=9999999){
		$films=M('cinemaPlan')->field('filmNo,filmName,totalTime, max(copyType) as copyType')->where($sql.' and startTime>='.time())->group('filmNo')->limit($start,$limit)->select();
		$appInfo = getAppInfo();
		$maxName = $appInfo['cinemaGroupInfo']['maxName'] ? $appInfo['cinemaGroupInfo']['maxName'] : 'MAX';
		foreach ($films as $k=>$v){

			$film=M('film')->where(array('filmNo'=>$v['filmNo']))->find();
			$films[$k]['simpleword']=$film['simpleword'];
			$films[$k]['score']=$film['score'];
			$films[$k]['id']=$film['id'];

			if ($v['copyType'] != '2D') {
				$films[$k]['bottonColor'] = 'green';
				if (strstr($v['copyType'], 'MAX')) {
					$films[$k]['bottonColor'] = 'blue';
				}
			}


			$films[$k]['copyType'] = str_replace('MAX', $maxName . ' ', $films[$k]['copyType']);

			if(!empty($film['image'])){
				$films[$k]['image']=C('IMG_URL') . 'Uploads/'.$film['image'];
			}else{
				$films[$k]['image']=C('FILM_IMG_URL') ;
			}
			
			
			$cc=M('cinemaPlan')->field('cinemaCode,count(*) as c')->where($sql.' and startTime>='.time().' and FROM_UNIXTIME(startTime,"%Y%m%d")='.date('Ymd').' and filmNo="'.$v['filmNo'].'"')->group('cinemaCode')->select();
			$a=0;
			foreach ($cc as $val){
				$a+=$val['c'];
			}
			$films[$k]['cp']=$a;
			$arrayCount[] = $a;
		}

		array_multisort($arrayCount, SORT_DESC, $films);

		return $films ? $films : array();
	}
	
	/**
	 * 排片信息
	 */
	function planInfos($time,$cinemaCode,$cinemaGroupId,$filmNo='0', $type = ''){
		$films=S('films'.$time.$cinemaCode.$filmNo.$type);

		if(empty($films)){

			$sql='cinemaCode='.$cinemaCode." and FROM_UNIXTIME(startTime,'%Y%m%d')=".$time." and startTime>=".(time()+10*60);
			if (strtoupper($type) == 'MAX') {
				$map['copyType'] = array('LIKE', 'MAX%');
			}elseif (strtoupper($type) == 'DISCOUNT') {
				$map['panType'] = $type;
			}elseif (strtoupper($type) == 'RUSH') {
				$map['panType'] = $type;
			}
			if(!empty($filmNo)){
				$film=M('film')->where(array('filmNo'=>$filmNo))->find();
				if(empty($film)){
					$films[0]['image']=C('FILM_IMG_URL') ;
					$films[0]['score']=0;
				}else{
					$films[0]['image']=C('IMG_URL') . 'Uploads/'.$film['image'];
					if(empty($film['image'])){
						$films[0]['image']=C('FILM_IMG_URL') ;
					}
					$films[0]['score']=$film['score'];
				}
				$films[0]['filmId'] =$film['id'];
				$films[0]['planInfo'] =M('cinema_plan')->field('filmName, filmNo, copyType, copyLanguage, standardPrice, priceConfig, startTime, featureAppNo, panType, totalTime, otherfilmNo, hallName')->where($sql.' and filmNo="'.$filmNo.'"')->order('startTime')->select();
				$films[0]['filmName']=$films[0]['planInfo'][0]['filmName'];
				$films[0]['filmNo']=$films[0]['planInfo'][0]['filmNo'];
				$films[0]['copyType']=$films[0]['planInfo'][0]['copyType'];
				$films[0]['totalTime']=$films[0]['planInfo'][0]['totalTime'];
				foreach ($films[0]['planInfo'] as $k=>$v) {
					$priceConfig=json_decode($v['priceConfig'],true);
					if(!empty($priceConfig)){
						$films[0]['planInfo'][$k]['priceConfig']=$priceConfig[$cinemaGroupId];
					}

					$films[0]['planInfo'][$k]['startTime']=date('H:i',$v['startTime']);
					$films[0]['planInfo'][$k]['endTime']=date('H:i',$v['startTime']+$v['totalTime']*60);
					// $films[0]['planInfo'][$k]['url']=U('seat',array('featureAppNo'=>$v['featureAppNo']));
				}
			}else{
				$films=M('cinema_plan')->field('filmNo,filmName,totalTime,copyType')->where($sql)->group('filmNo')->select();
				foreach ($films as $key => $value) {
					$film=M('film')->where('filmNo="'.$value['filmNo'].'"')->find();
					if(empty($film)){
						$films[$key]['image']=C('FILM_IMG_URL') ;
						$films[$key]['score']=0;
					}else{
						$films[$key]['image']=C('IMG_URL') . 'Uploads/'.$film['image'];
						if(empty($film['image'])){
							$films[$key]['image']=C('FILM_IMG_URL') ;
						}
						$films[$key]['score']=$film['score'];
					}
					$films[$key]['filmId'] =$film['id'];
					$films[$key]['planInfo'] =M('cinema_plan')->field('filmName, filmNo, copyType, copyLanguage, standardPrice, priceConfig, startTime, featureAppNo, panType,totalTime, otherfilmNo, hallName')->where($sql.' and filmNo="'.$value['filmNo'].'"')->order('startTime')->select();
					$films[$key]['planInfoCount'] = count($films[$key]['planInfo']);
					$newSort[] = $films[$key]['planInfoCount'];
					foreach ($films[$key]['planInfo'] as $k=>$v) {
						$priceConfig=json_decode($v['priceConfig'],true);
						if(!empty($priceConfig)){
							$films[$key]['planInfo'][$k]['priceConfig']=$priceConfig[$cinemaGroupId];
						}

						$films[$key]['planInfo'][$k]['startTime'] = date('H:i',$v['startTime']);
						$films[$key]['planInfo'][$k]['endTime'] = date('H:i',$v['startTime']+$v['totalTime']*60);
						// $films[$key]['planInfo'][$k]['url']=U('seat',array('featureAppNo'=>$v['featureAppNo']));
					}
				}
			}
			array_multisort($newSort, SORT_DESC, $films);
			S('films'.$time.$cinemaCode.$filmNo,$films,60);
		}
		return $films;
	}
	
	/**
	 * 排期时间列表
	 */
	function gettime($cinemaCode='',$filmNo=''){
		$map['cinemaCode']=$cinemaCode;
		if(!empty($filmNo)){
			$map['filmNo']=$filmNo;
		}
		$map['startTime']=array('egt',time()+10*60);

		if (strtoupper($type) == 'MAX') {
			$map['copyType'] = array('LIKE', 'MAX%');
		}elseif (strtoupper($type) == 'DISCOUNT') {
			$map['panType'] = $type;
		}elseif (strtoupper($type) == 'RUSH') {
			$map['panType'] = $type;
		}

		$planTime= M('cinema_plan')->field("FROM_UNIXTIME(startTime,'%Y%m%d') as time,startTime")->where($map)->group("FROM_UNIXTIME(startTime,'%Y%m%d')")->order('startTime')->select();
		$weeks=array('周日','周一','周二','周三','周四','周五','周六');
		foreach ($planTime as $k=>$v){
			$planTime[$k]['dtime']=date('n月j日',strtotime($v['time']));
			switch ((strtotime($v['time'])-strtotime(date('Ymd',time())))/(24*60*60)){
				case 0:$instr[$k]='今天';break;
				case 1:$instr[$k]='明天';break;
				case 2:$instr[$k]='后天';break;
				default:$instr[$k]=$weeks[date('w',strtotime($v['time']))];
			}
			$planTime[$k]['instr']=$instr[$k];
		}
		 
		return $planTime;
	}
	
	/**
	 * 获取当前排期信息
	 * @param unknown $featureAppNo
	 * @param unknown $mystr
	 * @return Ambigous <unknown, \Think\mixed>
	 */
	function getplan($featureAppNo,$mystr, $cinemaGroupId){
		$plan=M('cinemaPlan')->where(array('featureAppNo'=>$featureAppNo))->find();
		//$plan['film']=M('film')->where(array('filmNo'=>$plan['filmNo']))->find();
		
		$priceConfig=json_decode($plan['priceConfig'],true);
		$priceConfig = $priceConfig[$cinemaGroupId];

		if(!empty($priceConfig)){
			$plan['memberPrice']=$priceConfig[$mystr];
		}else{
			$plan['memberPrice']=$plan['listingPrice'];
		}
		$plan['m']=date('n-j',$plan['startTime']);
		$plan['d']=date('H:i',$plan['startTime']);
		$weeks=array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
		switch ((strtotime(date('Ymd',$plan['startTime']))-strtotime(date('Ymd',time())))/(24*60*60)){
			case 0:$plan['week']='今天';break;
			case 1:$plan['week']='明天';break;
			case 2:$plan['week']='后天';break;
			default:$plan['week']=$weeks[date('w',$plan['startTime'])];
		}
		return $plan;
	}

	public function getplanInfo($field,$featureAppNo)
	{
		$plan=M('cinemaPlan')->field($field)->where(array('featureAppNo'=>$featureAppNo))->find();

		$plan['m']=date('n-j',$plan['startTime']);
		$plan['d']=date('H:i',$plan['startTime']);
		$weeks=array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
		switch ((strtotime(date('Ymd',$plan['startTime']))-strtotime(date('Ymd',time())))/(24*60*60)){
			case 0:$plan['week']='今天';break;
			case 1:$plan['week']='明天';break;
			case 2:$plan['week']='后天';break;
			default:$plan['week']=$weeks[date('w',$plan['startTime'])];
		}
		$plan['endTime']=date('H:i',$plan['startTime']+$plan['totalTime']*60);
		return $plan;
	}


	/**
	 * 获取当前排期所有排期影片信息
	 * @param unknown $featureAppNo
	 * @param unknown $mystr
	 * @return \Think\mixed
	 */
	function getplans($featureAppNo,$mystr){
		$plan=M('cinemaPlan')->where(array('featureAppNo'=>$featureAppNo))->find();
		$stastr="FROM_UNIXTIME(startTime,'%Y%m%d')=".date('Ymd',$plan['startTime'])." and cinemaCode=".$plan['cinemaCode'].' and filmNo="'.$plan['filmNo'].'" and startTime>='.(time()+10*60);
		$plans=M('cinema_plan')->where($stastr)->order('startTime')->select();
		foreach ($plans as $key=>$val){
			$priceConfig=json_decode($val['priceConfig'],true);
			if(!empty($priceConfig)){
				$plans[$key]['memberPrice']=$priceConfig[$mystr];
			}else{
				$plans[$key]['memberPrice']=$val['listingPrice'];
			}
			unset($priceConfig);
			$plans[$key]['startTime']=date('H:i',$val['startTime']);
			$plans[$key]['start']=date('H:i',$val['startTime']);
			$plans[$key]['endTime']=date('H:i',$val['startTime']+$val['totalTime']*60);
		}
		return $plans;
	}

	public function getplanList($field,$featureAppNo, $cinemaGroupId)
	{

		$plan=M('cinemaPlan')->field('cinemaCode, startTime, filmNo')->where(array('featureAppNo'=>$featureAppNo))->find();
		$stastr="FROM_UNIXTIME(startTime,'%Y%m%d')=".date('Ymd',$plan['startTime'])." and cinemaCode=".$plan['cinemaCode'].' and filmNo="'.$plan['filmNo'].'" and startTime>='.(time()+10*60);
		$plans=M('cinema_plan')->field($field)->where($stastr)->order('startTime')->select();
		foreach ($plans as $key=>$val){
			$priceConfig = json_decode($val['priceConfig'],true);
			$plans[$key]['priceConfig'] = $priceConfig[$cinemaGroupId];
			$plans[$key]['startTime']=date('H:i',$val['startTime']);
			// $plans[$key]['start']=date('H:i',$val['startTime']);
			$plans[$key]['endTime']=date('H:i',$val['startTime']+$val['totalTime']*60);
		}
		return $plans ? $plans : '';
	}
}