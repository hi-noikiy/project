<?php

namespace Web\Model;
use Think\Model;

class PlanModel extends Model {
	function quickfilms($map){
		$films=M('cinemaPlan')->field('filmNo,filmName')->where($map)->group('filmNo')->select();
		return $films;
	}
	public function getplanInfo($field='*',$featureAppNo){
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
		$plan['startTime']=date('Y/m/d H:i',$plan['startTime']);
		return $plan;
	}
	public function getplanList($field,$featureAppNo){
		$plan=M('cinemaPlan')->field('cinemaCode, startTime, filmNo')->where(array('featureAppNo'=>$featureAppNo))->find();
		$stastr="FROM_UNIXTIME(startTime,'%Y%m%d')=".date('Ymd',$plan['startTime'])." and cinemaCode=".$plan['cinemaCode'].' and filmNo="'.$plan['filmNo'].'" and startTime>='.(time()+10*60);
		$plans=M('cinema_plan')->field($field)->where($stastr)->order('startTime')->select();
		foreach ($plans as $key=>$val){
			$plans[$key]['priceConfig']=json_decode($val['priceConfig'],true);
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
    function getplan($featureAppNo,$mystr){
    	$wwwInfo = session('wwwInfo');
    	$plan=M('cinemaPlan')->where(array('featureAppNo'=>$featureAppNo))->find();
    	$plan['film']=M('film')->where(array('filmNo'=>$plan['filmNo']))->find();
    	$priceConfig=json_decode($plan['priceConfig'],true);
    	$priceConfig=$priceConfig[$wwwInfo['cinemaGroupId']];
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
    /**
     * 获取当前排期所有排期影片信息
     * @param unknown $featureAppNo
     * @param unknown $mystr
     * @return \Think\mixed
     */
    function getplans($featureAppNo,$mystr){
    	$wwwInfo=session('wwwInfo');
    	$plan=M('cinema_plan')->where(array('featureAppNo'=>$featureAppNo))->find();
    	$stastr="FROM_UNIXTIME(startTime,'%Y%m%d')=".date('Ymd',$plan['startTime'])." and cinemaCode=".$plan['cinemaCode'].' and isClose=0 and filmNo="'.$plan['filmNo'].'" and startTime>='.(time()+10*60).' and featureAppNo!="'.$featureAppNo.'"';
    	$plans=M('cinema_plan')->where($stastr)->order('startTime')->select();
    	foreach ($plans as $key=>$val){
    		$priceConfig=json_decode($val['priceConfig'],true);
    		$priceConfig = $priceConfig[$wwwInfo['cinemaGroupId']];
    		if(!empty($priceConfig)&&$priceConfig[$mystr]){
    			$plans[$key]['memberPrice']=$priceConfig[$mystr];
    		}else{
    			$plans[$key]['memberPrice']=$val['listingPrice'];
    		}
    		unset($priceConfig);
    		if(strstr($val['copyType'], 'MAX')){
    			$plans[$key]['copyType']='<b style="color:#32aaee">Z'.$val['copyType'].'</b>';
    			$plans[$key]['start'] = '<b style="color:#32aaee">'.date('H:i',$val['startTime']).'</b>';
    		}else{
    			$plans[$key]['start']=date('H:i',$val['startTime']);
    		}
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
        $cinema = S('GETCINEMABYCODE' . $cinemaCode);
    	if (empty($cinema)) {
    		$cinema = D('cinema')->find($cinemaCode);
    		S('GETCINEMABYCODE' . $cinemaCode, $cinema, 3600*24*7);
    	}
//    	dump($cinema);
    	$wwwInfo=session('wwwInfo');
    	$stastr="FROM_UNIXTIME(startTime,'%Y%m%d')=".$time." and cinemaCode=".$cinemaCode.' and isClose=0 and filmNo="'.$filmNo.'" and startTime>='.(time()+10*60);
    	$plans=M('cinema_plan')->where($stastr)->order('startTime')->select();
    	foreach ($plans as $key=>$val){
            $remain = S('remain'.$wwwInfo['cinemaGroupId'].$val['cinemaCode'].$val['featureAppNo']);
            if ($remain === false) {
                $remain = '统计中';
            }
    		$priceConfig=json_decode($val['priceConfig'],true);
    		$priceConfig = $priceConfig[$wwwInfo['cinemaGroupId']];
    		if(!empty($priceConfig)&&$priceConfig[$mystr]){
    			$plans[$key]['memberPrice']=$priceConfig[$mystr];
    		}else{
    			$plans[$key]['memberPrice']=$val['listingPrice'];
    		}
    		unset($priceConfig);
    		$plans[$key]['end']=date('H:i',$val['startTime']+$val['totalTime']*60);
            $plans[$key]['remain']=$remain;
            if(strstr($val['copyType'], 'MAX')){
            	$plans[$key]['copyType']='<b style="color:#32aaee">Z'.$val['copyType'].'</b>';
            	$plans[$key]['start']='<b style="color:#32aaee">'.date('H:i',$val['startTime']).'</b>';
            }else{
            	$plans[$key]['start']=date('H:i',$val['startTime']);
            }
            if(isset($cinema['stopTime'])){            	
            	if($val['startTime']<(time()+$cinema['stopTime']*60)){
            		$plans[$key]['classesd']='disabled';
            	}          
            }
            
            
            
            
    	}
    	return $plans;
    }
    function countObj($time,$cinemaCode,$filmNo,$mystr){
    	$wwwInfo=session('wwwInfo');
    	$stastr="FROM_UNIXTIME(startTime,'%Y%m%d')=".$time." and cinemaCode=".$cinemaCode.' and filmNo="'.$filmNo.'" and isClose=0 and startTime>='.(time()+10*60);
    	$count=M('cinema_plan')->where($stastr)->count();
    	return $count;
    }
    /**
     * 排期时间列表
     */
    function gettime($map=array()){
    	$map['startTime']=array('egt',time()+10*60);
    	$planTime= M('cinema_plan')->field("FROM_UNIXTIME(startTime,'%Y%m%d') as time,startTime")->where($map)->group("FROM_UNIXTIME(startTime,'%Y%m%d')")->order('startTime')->select();
    	$weeks=array('周日','周一','周二','周三','周四','周五','周六');
    	foreach ($planTime as $k=>$v){
    		switch ((strtotime($v['time'])-strtotime(date('Ymd',time())))/(24*60*60)){
    			case 0:$instr[$k]='今天';break;
    			case 1:$instr[$k]='明天';break;
    			case 2:$instr[$k]='后天';break;
    			default:$instr[$k]=$weeks[date('w',strtotime($v['time']))];
    		}
    		$planTime[$k]['instr']=date('n-j',strtotime($v['time'])).$instr[$k];
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
//    	echo M('cinema_plan')->getlastsql();
    	foreach ($films as $k=>$v){
    		$film=M('film')->where('filmNo="'.$v['filmNo'].'"')->find();
    		$films[$k]['simpleword']=$film['simpleword'];
    		if(empty($film['image'])){
    			$film['image']=C('FILM_IMG_URL') ;
    		}else{
    			$film['image']=C('IMG_URL') . 'Uploads/'.$film['image'];
    		}
    		$films[$k]['image']=$film['image'];
    		$t[$k]=explode('.', number_format($film['score']/10,1));
    		$films[$k]['f']=$t[$k][0];
    		$films[$k]['s']=$t[$k][1];
    		$films[$k]['cast']=$film['cast'];
    		$films[$k]['director']=$film['director'];
    		$films[$k]['duration']=$film['duration'];
    		$films[$k]['presale']=0;
    		$films[$k]['publishDateflag']='选座购票';
    		if($film){
    			if($film['publishDate']>time()) {
    				$films[$k]['publishDateflag']='预售';
    				$films[$k]['presale']=1;
    			}
    		}
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
    	
    	$cinema = S('GETCINEMABYCODE' . $cinemaCode);
    	if (empty($cinema)) {
    		$cinema = D('cinema')->find($cinemaCode);
    		S('GETCINEMABYCODE' . $cinemaCode, $cinema, 3600*24*7);
    	}
    	$films=M('cinema_plan')->field('filmNo,filmName')->where('cinemaCode='.$cinemaCode.' and startTime>='.time())->group('filmNo')->select();
    	$i=1;
    	foreach ($films as $k=>$v){
    		$film=M('film')->where('filmNo="'.$v['filmNo'].'"')->find();
    		if(!empty($film)){
    			
    			if(empty($film['image'])){
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
		$cinema = S('GETCINEMABYCODE' . $cinemaCode);
    	if (empty($cinema)) {
    		$cinema = D('cinema')->find($cinemaCode);
    		S('GETCINEMABYCODE' . $cinemaCode, $cinema, 3600*24*7);
    	}
		$wwwInfo = session('wwwInfo');
		$films=S('films'.$time.$cinemaCode.$filmNo);
		$films=null;
		if(empty($films)){
			$sql='cinemaCode='.$cinemaCode." and FROM_UNIXTIME(startTime,'%Y%m%d')=".$time." and isClose=0  and startTime>=".(time()+10*60);
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
					$priceConfig = $priceConfig[$wwwInfo['cinemaGroupId']];
    				if(!empty($priceConfig)&&$priceConfig[$mystr]){
    					$films[0]['planInfo'][$k]['memberPrice']=$priceConfig[$mystr];
    				}else{
    					$films[0]['planInfo'][$k]['memberPrice']=$v['listingPrice'];
    				}
    				if(strstr($v['copyType'], 'MAX')){
    					$films[0]['planInfo'][$k]['copyType']='<b style="color:#32aaee">Z'.$v['copyType'].'</b>';
    					$films[0]['planInfo'][$k]['startTime']='<b style="color:#32aaee">'.date('H:i',$v['startTime']).'</b>';
    				}else{
    					$films[0]['planInfo'][$k]['startTime']=date('H:i',$v['startTime']);
    				}
    					
    				$films[0]['planInfo'][$k]['endTime']=date('H:i',$v['startTime']+$v['totalTime']*60);
    				if(isset($cinema['stopTime'])){
    					if($v['startTime']<(time()+$cinema['stopTime']*60)){
    						$films[0]['planInfo'][$k]['classesd']='disabled';
    					}
    				}
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
					if(empty($film['director'])){
						$films[$key]['director'] ='未知';
					}else{
						$films[$key]['director'] =$film['director'];
					}
					if(empty($film['cast'])){
						$films[$key]['cast'] ='未知';
					}else{
						$films[$key]['cast'] =$film['cast'];
					}
					$films[$key]['filmId'] =$film['id'];
					$films[$key]['planInfo'] =M('cinema_plan')->field('filmName, filmNo, copyType, copyLanguage, listingPrice, priceConfig, startTime, featureAppNo, panType,totalTime, otherfilmNo, hallName')->where($sql.' and filmNo="'.$value['filmNo'].'"')->order('startTime')->select();
					$films[$key]['planInfoCount'] = count($films[$key]['planInfo']);
					$newSort[] = $films[$key]['planInfoCount'];
					foreach ($films[$key]['planInfo'] as $k=>$v) {
						$priceConfig=json_decode($v['priceConfig'],true);
						$priceConfig = $priceConfig[$wwwInfo['cinemaGroupId']];
						if(!empty($priceConfig)&&$priceConfig[$mystr]){
							$films[$key]['planInfo'][$k]['memberPrice']=$priceConfig[$mystr];
						}else{
							$films[$key]['planInfo'][$k]['memberPrice']=$v['listingPrice'];
						}
						if(strstr($v['copyType'], 'MAX')){
							$films[$key]['planInfo'][$k]['copyType']='<b style="color:#32aaee">Z'.$v['copyType'].'</b>';
							$films[$key]['planInfo'][$k]['startTime'] = '<b style="color:#32aaee">'.date('H:i',$v['startTime']).'</b>';
						}else{
							$films[$key]['planInfo'][$k]['startTime'] = date('H:i',$v['startTime']);
						}
						//$films[$key]['planInfo'][$k]['startTime'] = date('H:i',$v['startTime']);
						$films[$key]['planInfo'][$k]['endTime'] = date('H:i',$v['startTime']+$v['totalTime']*60);
						
						if(isset($cinema['stopTime'])){
	    					if($v['startTime']<(time()+$cinema['stopTime']*60)){
	    						$films[$key]['planInfo'][$k]['classesd']='disabled';
	    					}
	    				}
					}
				}
			}
			array_multisort($newSort, SORT_DESC, $films);
			S('films'.$time.$cinemaCode.$filmNo,$films,60);
		}
		return $films;
	}
  	/**
  	 * 影片排期内容获取封装
  	 * @param array $dataarray 传递一些参数进行查询
  	 * @param int $flag 1正常查询  2带分页查询  3 获取个数  4获取单条内容  5分组排列查找
  	 * @return unknown|boolean
  	 */
  	public function cinema_plan_getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
  		if(isset($dataarray['filmNo'])) {    
        	$wherearray['filmNo']=array('eq',$dataarray['filmNo']);            
        }
  		if(isset($dataarray['eltstartTime'])) {    
        	$wherearray['startTime']=array('elt',$dataarray['eltstartTime']);            
        } 
        if(isset($dataarray['egtstartTime'])) {    
        	$wherearray['startTime']=array('egt',$dataarray['egtstartTime']);            
        }
        if(isset($dataarray['start_startTime'])&&isset($dataarray['end_startTime'])) {
            $starttime=$dataarray['start_startTime'];
            $endtime=$dataarray['end_startTime']+86399;            
            $wherearray['startTime']=array('exp','>='.$starttime.' and startTime <= '.$endtime);
        }elseif(isset($dataarray['start_startTime'])) {
            $starttime=$dataarray['start_startTime'];
            $wherearray['startTime']=array('exp','>='.$starttime);
        }elseif(isset($dataarray['end_startTime'])) {
            $endtime=$dataarray['end_startTime']+86399;
            $wherearray['startTime']=array('exp','<= '.$endtime);
        }
  		if(isset($dataarray['cinemaCode'])) {    
        	$wherearray['cinemaCode']=array('eq',$dataarray['cinemaCode']);            
        }   
       	$database = M('cinema_plan');
     	$sort='';
    	if(isset($dataarray['sort'])) {  
    		$sort=$dataarray['sort'];  	         
        }       
     	$firstRow='0';
    	if(isset($dataarray['firstRow'])) {  
    		$firstRow=$dataarray['firstRow'];  	         
        }
        $listRows=20;
    	if(isset($dataarray['listRows'])) {  
    		$listRows=$dataarray['listRows'];  	         
        }       
		$getField='*';
    	if(isset($dataarray['getField'])) {  
    		$getField=$dataarray['getField'];  	         
        }
     	$groupByField='id';
    	if(isset($dataarray['groupByField'])) {  
    		$groupByField=$dataarray['groupByField'];  	         
        } 
        switch($flag) {
            case '1':
                $info=$database->where($wherearray)->field($getField)->order($sort)->select();
                break;
            case '2'://分页操作
                $info=$database->where($wherearray)->field($getField)->order($sort)->limit($firstRow.','.$listRows)->select();
                break;
            case '3'://获取个数
                $info=$database->where($wherearray)->count();
                break;
            case '4'://获取单条
                $info=$database->where($wherearray)->field($getField)->find();
                break;
            case '5'://分组排列查找
                $info=$database->where($wherearray)->field($getField)->group($groupByField)->select();
                break;
            default:
                $info=$database->where($wherearray)->select();
                break;
        }
//    echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
    
    /**
  	 * 热映影片排序获取
  	 */
  	public function getFilms_sort($sql='1') { 		
  		$list=$this->getFilms($sql); 		 		
  		foreach ($list as $key => $row) {
	        $copyType[$key]  = $row['copyType'];
	        $presale[$key] = $row['presale'];
	        $cc[$key]  = $row['cc'];
    	}
    	array_multisort($presale, SORT_DESC, $copyType, SORT_DESC,$cc,SORT_DESC, $list);		 		 		
  		return $list; 		
  	}
	
	
	
	
}