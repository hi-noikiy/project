<?php

namespace Home\Model;
use Think\Model;

class PlanModel extends Model {
	
	public function getplanInfo($field,$featureAppNo){
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
	public function getplanList($field,$featureAppNo){
        $weiXinInfo = getWeiXinInfo();
		$plan=M('cinemaPlan')->field('cinemaCode, startTime, filmNo')->where(array('featureAppNo'=>$featureAppNo))->find();
		$stastr="FROM_UNIXTIME(startTime,'%Y%m%d')=".date('Ymd',$plan['startTime'])." and cinemaCode=".$plan['cinemaCode'].' and filmNo="'.$plan['filmNo'].'" and startTime>='.(time()+10*60);
		$plans=M('cinema_plan')->field($field)->where($stastr)->order('startTime')->select();
		foreach ($plans as $key=>$val){
			$plans[$key]['priceConfig']=json_decode($val['priceConfig'],true);
			$plans[$key]['priceConfig'] = $plans[$key]['priceConfig'][$weiXinInfo['cinemaGroupId']];
            $plans[$key]['startTime']=date('H:i',$val['startTime']);
			// $plans[$key]['start']=date('H:i',$val['startTime']);
			$plans[$key]['endTime']=date('H:i',$val['startTime']+$val['totalTime']*60);
		}
		return $plans ? $plans : '';
	}
	function getList($map=array(),$order='startTime',$start=0,$limit=999999999){
		 return M('cinema_plan')->where($map)->order($order)->select();
	}
    function findObj($map=''){
    	return M('cinema_plan')->where($map)->find();
    }
    /**
     * 获取当前排期信息
     * @param unknown $featureAppNo
     * @param unknown $mystr
     * @return Ambigous <unknown, \Think\mixed>
     */
    function getPlan($featureAppNo,$mystr){
        $weiXinInfo = getWeiXinInfo();
    	$plan=M('cinema_plan')->where(array('featureAppNo'=>$featureAppNo))->find();
    	$plan['film']=M('film')->where(array('filmNo'=>$plan['filmNo']))->find();
    	$priceConfig=json_decode($plan['priceConfig'],true);
        $priceConfig = $priceConfig[$weiXinInfo['cinemaGroupId']];

    	if(!empty($priceConfig)&&$priceConfig[$mystr]){
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
    /**
     * 获取当前排期所有排期影片信息
     * @param unknown $featureAppNo
     * @param unknown $mystr
     * @return \Think\mixed
     */
    function getplans($featureAppNo,$mystr){

        $weiXinInfo = getWeiXinInfo();


    	$plan=M('cinema_plan')->where(array('featureAppNo'=>$featureAppNo))->find();
    	$stastr="FROM_UNIXTIME(startTime,'%Y%m%d')=".date('Ymd',$plan['startTime'])." and cinemaCode=".$plan['cinemaCode'].' and filmNo="'.$plan['filmNo'].'" and startTime>='.(time()+10*60).' and startTime!='.$plan['startTime'];
    	$plans=M('cinema_plan')->where($stastr)->order('startTime')->select();
    	foreach ($plans as $key=>$val){
    		$priceConfig=json_decode($val['priceConfig'],true);
            $priceConfig = $priceConfig[$weiXinInfo['cinemaGroupId']];
    		if(!empty($priceConfig)&&$priceConfig[$mystr]){
    			$plans[$key]['memberPrice']=$priceConfig[$mystr];
    		}else{
    			$plans[$key]['memberPrice']=$val['listingPrice'];
    		}
    		unset($priceConfig);
    		$plans[$key]['start']=date('H:i',$val['startTime']);
    		$plans[$key]['end']=date('H:i',$val['startTime']+$val['totalTime']*60);
    	}
    	return $plans;
    }
    /**
     * 根据日期影院影片获取排期信息
     * @param unknown $featureAppNo
     * @param unknown $mystr
     * @return \Think\mixed
     */
    function findplans($time,$cinemaCode,$filmNo,$mystr){
        $weiXinInfo = getWeiXinInfo();
    	$plan=M('cinema_plan')->where(array('featureAppNo'=>$featureAppNo))->find();
    	$stastr="FROM_UNIXTIME(startTime,'%Y%m%d')=".$time." and cinemaCode=".$cinemaCode.' and filmNo="'.$filmNo.'" and startTime>='.(time()+10*60);
    	$plans=M('cinema_plan')->where($stastr)->order('startTime')->select();
    	foreach ($plans as $key=>$val){
    		$priceConfig=json_decode($val['priceConfig'],true);
            $priceConfig = $priceConfig[$weiXinInfo['cinemaGroupId']];
    		if(!empty($priceConfig)&&$priceConfig[$mystr]){
    			$plans[$key]['memberPrice']=$priceConfig[$mystr];
    		}else{
    			$plans[$key]['memberPrice']=$val['listingPrice'];
    		}
    		unset($priceConfig);
    		$plans[$key]['start']=date('H:i',$val['startTime']);
    		$plans[$key]['end']=date('H:i',$val['startTime']+$val['totalTime']*60);
    	}
    	return $plans;
    }
    function countObj($map=''){
    	return M('cinema_plan')->where($map)->count();
    }
    /**
     * 排期时间列表
     */
    function gettime($map=array()){
    	$map['startTime']=array('egt',time()+10*60);
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
     * 正在热映影片数
     * @param string $sql
     * @return \Think\mixed
     */
    function getcurin($map=array()){
    	$cinemaList=D('cinema')->getCinemasStr();
    	$map['_string'] = 'cinemaCode in('.$cinemaList.')';
    	$map['startTime']=array('egt',time());
    	$c=M('cinema_plan')->field('count(distinct(filmNo)) as c')->where($map)->find();
    	if(empty($c)){
    		$c['c']=0;
    	}
    	return $c['c'];
    }
    /**
     * 正在热映影片信息
     * @param string $sql
     * @return unknown
     */
    function getFilms($sql='1'){
    	$films=M('cinema_plan')->field('filmNo,filmName,copyType')->where($sql.' and startTime>='.time())->group('filmNo')->select();
    	foreach ($films as $k=>$v){
    		$film=M('film')->where('filmNo="'.$v['filmNo'].'"')->find();
    		if(empty($film)){
    			$film['image']=C('FILM_IMG_URL');
    		}else{
    			if(!file_exists('./Uploads/'.$film['image'])){
    				$film['image']=C('FILM_IMG_URL') ;
    			}else{
    				$film['image']=C('IMG_URL') . 'Uploads/'.$film['image'];
    			}
    		}
    		$films[$k]['simpleword']=$film['simpleword'];
    		$films[$k]['image']=$film['image'];
    		$t[$k]=explode('.', number_format($film['score']/10,1));
    		$films[$k]['f']=$t[$k][0];
    		$films[$k]['s']=$t[$k][1];
    		$cc=M('cinema_plan')->field('cinemaCode,count(*) as c')->where($sql.' and startTime>='.time().' and FROM_UNIXTIME(startTime,"%Y%m%d")='.date('Ymd').' and filmNo="'.$v['filmNo'].'"')->group('cinemaCode')->select();
    		$a=0;
    		foreach ($cc as $val){
    			$a+=$val['c'];
    		}
    		$films[$k]['cc']=count($cc);
    		$films[$k]['cp']=$a;
    		$arrayCount[]=$a;
    	}
    	array_multisort($arrayCount, SORT_DESC, $films);
    	return $films;
    }
    /**
     * 影院对应的影片排期
     * @param string $sql
     * @return unknown
     */
    function getcinemaFilms($cinemaCode,$filmNo){
    	$films=M('cinema_plan')->field('filmNo,filmName')->where('cinemaCode='.$cinemaCode.' and startTime>='.time())->group('filmNo')->select();
    	$i=1;
    	foreach ($films as $k=>$v){
    		$film=M('film')->where('filmNo="'.$v['filmNo'].'"')->find();
    		if(!empty($film)){
    			
    			if(!file_exists('./Uploads/'.$film['image'])){
    				$film['image']=C('FILM_IMG_URL') ;
    			}else{
    				$film['image']=C('IMG_URL') . 'Uploads/'.$film['image'];
    			}
    			if($v['filmNo']==$filmNo){
    				$filmss[0]=$film;
    			}else{
    				$filmss[$i]=$film;
    				$i++;
    			}
    		}else{
    			$film['image']=C('FILM_IMG_URL') ;
    			$film['filmNo']=$v['filmNo'];
    			$film['filmName']=$v['filmName'];
    			$filmss[$i]=$film;
    			$i++;
    		}
    	}
    	ksort($filmss);
    	return $filmss;
    }
    /**
     * 排片信息
     */
    function planInfos($time,$cinemaCode,$mystr,$filmNo='0'){
        $weiXinInfo = getWeiXinInfo();
        // print_r($weiXinInfo);
    	// $films=S('films'.$time.$cinemaCode.$mystr.$filmNo);
        $maxName = $weiXinInfo['maxName'];

    	if(empty($films)){
    		$sql='cinemaCode='.$cinemaCode." and FROM_UNIXTIME(startTime,'%Y%m%d')=".$time." and startTime>=".(time()+10*60);
    		if(!empty($filmNo)){
    			$film=M('film')->where(array('filmNo'=>$filmNo))->find();
    			if(empty($film)){
    				$film['image']=C('FILM_IMG_URL') ;
    				$film['score']=0;
    			}else{
    				
    				if(!file_exists('./Uploads/'.$film['image'])){
    					$film['image']=C('FILM_IMG_URL') ;
    				}else{
    					$film['image']=C('IMG_URL') . 'Uploads/'.$film['image'];
    				}
    			}
    			$films[0]['film'] =$film;
    			$films[0]['planInfo'] =M('cinema_plan')->where($sql.' and filmNo="'.$value['filmNo'].'"')->order('startTime')->select();

    			foreach ($films[0]['planInfo'] as $k=>$v) {
    				$priceConfig=json_decode($v['priceConfig'],true);

                    $priceConfig = $priceConfig[$weiXinInfo['cinemaGroupId']];

    				if(!empty($priceConfig)&&$priceConfig[$mystr]){
    					$films[0]['planInfo'][$k]['memberPrice']=$priceConfig[$mystr];
    				}else{
    					$films[0]['planInfo'][$k]['memberPrice']=$v['listingPrice'];
    				}
    				$films[0]['planInfo'][$k]['startTime']=date('H:i',$v['startTime']);
    				$films[0]['planInfo'][$k]['url']=U('seat',array('featureAppNo'=>$v['featureAppNo']));
    			}
    		}else{

                // $films[$k]['copyType'] = str_replace('MAX', $maxName . ' ', $films[$k]['copyType']);

    			$films=M('cinema_plan')->field('filmNo,filmName,totalTime')->where($sql)->group('filmNo')->select();

    			foreach ($films as $key => $value) {
    				$film=M('film')->where('filmNo="'.$value['filmNo'].'"')->find();
    				if(empty($film)){
    					$film['image']=C('FILM_IMG_URL') ;
    					$film['score']=0;
    				}else{
    					
    					if(!file_exists('./Uploads/'.$film['image'])){
                            // die($film['image']);
    						$film['image']=C('FILM_IMG_URL') ;
    					}else{
    						$film['image']=C('IMG_URL') . 'Uploads/'.$film['image'];
    					}
    				}
    				$films[$key]['film'] =$film;
    				$films[$key]['planInfo'] =M('cinema_plan')->field('featureAppNo, startTime, filmNo, filmName, hallNo, hallName, replace(copyType,"MAX","'.$maxName.'") as copyType, copyLanguage, totalTime, listingPrice, priceConfig')->where($sql.' and filmNo="'.$value['filmNo'].'"')->order('startTime')->select();
                    $films[$key]['planInfoCount'] = count($films[$key]['planInfo']);
                    $newSort[] = $films[$key]['planInfoCount'];
    				foreach ($films[$key]['planInfo'] as $k=>$v) {
    					$priceConfig=json_decode($v['priceConfig'],true);
                        $priceConfig = $priceConfig[$weiXinInfo['cinemaGroupId']];
    					if(!empty($priceConfig)&&$priceConfig[$mystr]){
    						$films[$key]['planInfo'][$k]['memberPrice']=$priceConfig[$mystr];
    					}else{
    						$films[$key]['planInfo'][$k]['memberPrice']=$v['listingPrice'];
    					}
    					$films[$key]['planInfo'][$k]['startTime']=date('H:i',$v['startTime']);
    					$films[$key]['planInfo'][$k]['url']=U('seat',array('featureAppNo'=>$v['featureAppNo']));
    				}
    			}
    		}


            array_multisort($newSort, SORT_DESC, $films);

    		// for($i=0;$i<count($films);$i++){
    		// 	for($j=$i;$j<count($films);$j++){
    		// 		 if(count($films[$j]['planInfo'])>count($films[$i]['planInfo'])){
    		// 		 	$k=$films[$j]['planInfo'];
    		// 		 	$films[$j]['planInfo']=$films[$i]['planInfo'];
    		// 		 	$films[$i]['planInfo']=$k;
    		// 		 }
    		// 	}
    		// }
    		S('films'.$time.$cinemaCode.$mystr.$filmNo,$films,60);
    	}
    	return $films;
    }
}