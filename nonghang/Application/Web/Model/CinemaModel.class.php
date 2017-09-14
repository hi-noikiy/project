<?php

namespace Web\Model;
use Think\Model;

class CinemaModel extends Model {
	
	/**
	 * 获取影院信息
	 * @param array();
	 * @return array();
	 * @author 宇
	 */
	public function getCinemaInfo($field, $map){
		$cinemaInfo = M('Cinema')->field($field)->where($map)->find();
//		echo M('Cinema')->getlastsql();
		// echo $cinemaList;
		return $cinemaInfo;
	}

	/**
	 * 根据标识获取分组信息
	 * @param array();
	 * @return array();
	 * @author 宇
	 */
	public function getCinemaGroupByDomain($domain){
		$map['domain'] = $domain;
		$map['channelConfig'] = array('LIKE', '%web%');
		$cinemaGroup= M('CinemaGroup')->field('id as cinemaGroupId,groupName, tempName, defaultLevel, cinemaList, smsType, smsAccount, smsPassword, smsSign, registrationProtocol, voucherRule, proportion, getProportion, token, payWay, isDebug')->where($map)->find();
		return $cinemaGroup;
	}

	/**
	 * 根据分组获取影院列表
	 * @param array();
	 * @return array();
	 * @author 宇
	 */
	public function getCinemaInfoBycinemaCode($field, $cinemaCode){
		return $this->getCinemaInfo($field, array('cinemaCode' => $cinemaCode));
	}
	function getFirstCinema($map=''){
		$cinemaList=D('cinema')->getCinemaList($map);
		return $cinemaList[0]['cinemaCode'];
	}
	function getCinemasStr($map=''){
		$cinemaList=D('cinema')->getCinemaList();
		$str='';
		foreach ($cinemaList as $v){
			$str.=','.$v['cinemaCode'];
		}
		return substr($str, 1);
	}
	/**
    * 获取影院列表
    * @param array();
    * @return array();
    * @author 宇
    */
   public function getCinemaList( $map=array(),$cinemaCode=''){
		$cinemaList = M('Cinema')->field($field)->where($map)->select();
		if(!empty($cinemaCode)){
			 $i=1;
			 foreach ($cinemaList as $value){
			 	if($value['cinemaCode']==$cinemaCode){
			 		$cinemaList[0]=$value;
			 	}else{
			 		$cinemaList[$i]=$value;
			 		$i++;
			 	}
			 }
			 ksort($cinemaList);
		}
		return $cinemaList;
   }
	/**
	 * 获取座位补贴价
	 */
	function getseatprice($map,$sectionId=''){
		$price=0;
		$domain =$_SERVER['HTTP_HOST'];
		$wwwInfo = getWwwInfo($domain);
		if(!empty($sectionId)){
			$prices=M('CinemaHall')->where($map)->find();
			$pricess=json_decode($prices['price'],true);
			$pricess=$pricess[$wwwInfo['cinemaGroupId']];
			if(!empty($pricess[$sectionId])){
				$price=$pricess[$sectionId];
			}
		}
		return $price;
	}
	function findObj($map){
		return M('Cinema')->where($map)->find();
	}
	function findHallPrice($map){
		$hall=M('cinemaHall')->where($map)->find();
		return json_decode($hall['price'],true);
	}
	

	function getList($map=array()){
		 $cinema= M('Cinema')->where($map)->find();
		 $cinemaList= M('Cinema')->where('cinemaGroupId='.$cinema['cinemaGroupId'])->select();		 
		 $i=1;
		 foreach ($cinemaList as $value){
		 	if($value['cinemaCode']==$cinema['cinemaCode']){
		 		$cinemaList[0]=$value;
		 	}else{
		 		$cinemaList[$i]=$value;
		 		$i++;
		 	}
		 }
		 ksort($cinemaList);
		 return $cinemaList;
	}
	 
	function getAllCinema($map=array()){
		return M('Cinema')->where($map)->select();
	}
    function citys($op='',$filmNo=''){
    	$user=session('ftuser');
    	$domain =$_SERVER['HTTP_HOST'];
	 	$weiXinInfo = getWwwInfo($domain);
    	$arr['cinemaCode'] = array('IN', $weiXinInfo['cinemaList']);

    	if(!empty($user['cardNum'])){
    		$cinema= M('Cinema')->find($user['businessCode']);
    		// $arr['cinemaGroupId']=$cinema['cinemaGroupId'];
			$citys= M('Cinema')->field('distinct(city) as city')->where($arr)->select();
    	}else{
    		$pflag=session('pflag');
    		if(empty($pflag)){
    			if(!empty($filmNo)){
    				$map['startTime']=array('egt',time());
    				$map['filmNo']=$filmNo;
    				$cinemas=M('cinema_plan')->field('distinct(cinemaCode) as cinemaCode')->where($map)->select();
    				$i=0;
    				foreach ($cinemas as $k=>$v){
    					$city=M('Cinema')->field('city')->find($v['cinemaCode']);
    					$arrs[]=$v['cinemaCode'];
    					if(empty($cc[$city['city']])){
    						$citys[$i]['city']=$city['city'];
    						$cc[$city['city']]=1;
    						$i++;
    					}
    				}
    				$p=implode(',', $arrs);
    				$arr['cinemaCode']=array('in',$p);
    				unset($cc);
    			}else{
    				$citys=M('Cinema')->field('distinct(city) as city')->where($arr)->select();
    			}
    		}else{
    			$cinema= M('Cinema')->find($pflag);
    			// $arr['cinemaGroupId']=$cinema['cinemaGroupId'];
    			$citys= M('Cinema')->field('distinct(city) as city')->where($arr)->select();
    		}
    	}
    	foreach ($citys as $key=>$val){
    		$arr['city']=$val['city'];
    		$citys[$key]['cinemas']=M('Cinema')->where($arr)->select();
    		foreach ($citys[$key]['cinemas'] as $k=>$v){
    			if($op=='plan'){
    				$citys[$key]['cinemas'][$k]['url']=U('index/plan',array('cinemaCode'=>$v['cinemaCode']));//影片排期
                    $citys[$key]['cinemas'][$k]['type'] = 'load';
    			}elseif($op=='recharge'){
                    session('url', U('user/recharge'));
                    $citys[$key]['cinemas'][$k]['url']=U('public/login',array('cinemaCode'=>$v['cinemaCode']));//影院首页
                }elseif($op=='cinemaplan'){
    				$citys[$key]['cinemas'][$k]['url']=U('index/cinemaplan',array('cinemaCode'=>$v['cinemaCode'],'filmNo'=>$filmNo));//影院排期
                    $citys[$key]['cinemas'][$k]['type'] = 'load';
    			}elseif($op=='login'){
                    session('url', U('user/user'));
    				$citys[$key]['cinemas'][$k]['url']=U('public/login',array('cinemaCode'=>$v['cinemaCode']));//影院首页
    			}elseif($op=='round'){
                   $citys[$key]['cinemas'][$k]['url']=U('index/roundlist',array('cinemaCode'=>$v['cinemaCode']));
                   $citys[$key]['cinemas'][$k]['type'] = 'load';
    			}elseif($op=='goods'){
                   $citys[$key]['cinemas'][$k]['url']=U('index/goodslist',array('cinemaCode'=>$v['cinemaCode']));
                   $citys[$key]['cinemas'][$k]['type'] = 'load';
    			}elseif($op=='film'){
                   $citys[$key]['cinemas'][$k]['url']=U('index/film',array('cinemaCode'=>$v['cinemaCode']));
                   $citys[$key]['cinemas'][$k]['type'] = 'load';
    			}else{
    				$citys[$key]['cinemas'][$k]['url']=U('index/index',array('cinemaCode'=>$v['cinemaCode']));//
    			}
    		}
    	}
    	return $citys;
    }
    
     public function cinema_getlist($dataarray=array(),$flag="1") {
        $wherearray=array();
        if(isset($dataarray['id'])) {    
        	$wherearray['id']=array('eq',$dataarray['id']);            
        }
    	if(isset($dataarray['cinemaCode'])) {    
        	$wherearray['cinemaCode']=array('eq',$dataarray['cinemaCode']);            
        }
       $database = M('cinema');
     	//条件处理
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
//     echo $database->getlastsql();
        if($info) {
            return $info;//返回用户id
        }else {
            return false;
        }
    }
    
    
    
    
    
    
    
    
}