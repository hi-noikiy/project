<?php
namespace Interfaces\Controller;
// use Think\Controller\HproseController;
use Think\Controller;

/*
extends HproseController
*/
class ServiceController extends InterfacesExtends {
    public function index()
    {
        die('禁止访问');
    }

    /**
    * 获取会员ID
    * @param  
    * @return result
    *        {
    *            "status": "0",
    *            "data": md5(tokenId)
    *        }xmmgf123
    * @author 
    */
    public function getToken()
    {   
        if(empty($this->param['appAccount']) || empty($this->param['appPasswd'])){
            $this->error('参数错误！', '11001');
        }

        if(!empty($this->param['tokenId'])){
            $appAccountInfo = S('APPINFOUserInfotokenId_' . $this->param['tokenId']);
            S('APPINFOUserInfotokenId_' . $this->param['tokenId'], NULL);
        }

        if (empty($appAccountInfo)) {
            $appAccountMap['appAccount'] = $this->param['appAccount'];
            $appAccountMap['appPasswd'] = $this->param['appPasswd'];
            $cinemaGroupInfo = D('Service')->getAppAccount('id as cinemaGroupId, groupName, cinemaList, defaultLevel, smsType, smsAccount, smsPassword, smsSign, registrationProtocol, voucherRule, proportion, getProportion, channelConfig, payWay, isDebug, maxName', $appAccountMap);

            // print_r($cinemaGroupInfo);
            if (empty($cinemaGroupInfo)) {
               $this->error('APP帐号、密码不正确！');
            }

            $appAccountInfo['cinemaGroupId'] = $cinemaGroupInfo['cinemaGroupId'];
            $appAccountInfo['proportion'] = $appInfo['proportion'];
            $appAccountInfo['cinemaGroupInfo'] = $cinemaGroupInfo;
            wlog('获取token详细信息' . json_encode($appAccountInfo), 'tokenId');
        }       

    	$tokenId = md5(time() + microtime() . rand(100000000,999999999) . $this->cacheName);
        $resultData['tokenId'] = $tokenId;
       // $resultData['defaultLevel'] = $cinemaGroupInfo['defaultLevel'];
        S('APPINFOUserInfotokenId_' . $tokenId, $appAccountInfo, 604800);
        session(array('id'=>$tokenId,'expire'=>7200));
        session('tokenId', $tokenId);
        $this->success('', $resultData, 7200, 'tokenId_' . $tokenId);
    }


    
    /**
    * 获取影院列表
    * @param  
    * @return result
    *        {
    *            "status": "0",
    *            "data": md5(tokenId)
    *        }xmmgf123
    * @author 
    */

    public function getCinemaList()
    {

        $map['cinemaCode'] = array('IN', $this->appInfo['cinemaGroupInfo']['cinemaList']);
        $cinemaList = D('Cinema')->getCinemaList('cinemaCode, cinemaName, shortName, longitude, latitude, phone, prov, city, address', $map);
        $this->success('', $cinemaList, 3600);
    }


    /**
     * 3.2获取热映影片
     */
    function getHotMove(){
        if(empty($this->param['cinemaCode'])){
            $this->error('参数错误！', '11001');
        }
        $filmsMap['cinemaCode'] = $this->param['cinemaCode'];
        $films=D('plan')->getFilms($filmsMap);
        $this->success('', $films, 3600);
    }


        /**
     * 即将上映
     */
    public function getSoonMove(){


        $filmsMap['publishDate'] = array('egt',time());
        $filmsMap['filmNo'] = array('eq','');

        $films = D('film')->getList('id as filmId, filmName, version as copyType, duration, publishDate, director, cast, area, type, score, lang as copyLanguage, introduction', $filmsMap);

        foreach ($films as $k=>$v){
            if(in_array($v['id'], $myfilms)){
                $films[$k]['hasLook']=1;
            }
            $films[$k]['publishDate']=date('Y-m-d',$v['publishDate']);
            if(!empty($v['image'])){
                $films[$k]['image']=C('IMG_URL') . 'Uploads/'.$v['image'];
            }else{
                $films[$k]['image']=C('FILM_IMG_URL') ;
            }
        }
        $this->success('', $films, 3600);
    }


     /**
     * 影片详情
     */
    public function filmDetail(){
        $filmid=$this->param['filmId'];
        $filmNo=$this->param['filmNo'];
        if(empty($filmNo)&&empty($filmid)){
            $this->error('参数错误！', '11001');
        }
        if(!empty($filmid)){
            $arr['id']=$filmid;
        }
        if(!empty($filmNo)){
            $arr['filmNo']=$filmNo;
        }
        $film=D('film')->getFilm($arr);
        if(empty($film)){
        	$this->error();
        }else{
        	unset($film['id']);
        	unset($film['updateTime']);
        	unset($film['updateUser']);
        	unset($film['minPrice']);
        	$this->success('', $film, 3600);
        }
       
    }


    /**
     * 获取排期时间
     */
    public function getTimes(){
        $filmNo = $this->param['filmNo'];
        $cinemaCode = $this->param['cinemaCode'];
        $type = $this->param['type'];
        $times=D('Plan')->gettime($cinemaCode,$filmNo,$type);//时间列表
        $this->success('', $times, 900);//获取成功！
    }
    /**
     * 查看排期
     */
    public function getPlans() {
        $cinemaCode=$this->param['cinemaCode'];
        $time=$this->param['time'];
        $filmNo=$this->param['filmNo'];
        if(empty($time)||empty($cinemaCode)){
            $this->error('参数错误！', '11001');
        }

        $films=D('plan')->planInfos($time,$cinemaCode, $this->appInfo['cinemaGroupId'],$filmNo, $this->param['type']);

        if (!empty($filmNo)) {
            $filmsList = $films[0];
        }else{
            $filmsList = $films;
        }

        $this->success('', $filmsList, 900);//获取成功！
    }


     /**
     * 座位信息
     */
    public function getSeat(){
        // $cinemaCode=$this->param['cinemaCode'];
        $featureAppNo=$this->param['featureAppNo'];
        
        if(empty($featureAppNo)){
            $this->error('参数错误！', '11001');
        }
        $plan=D('plan')->getplanInfo('cinemaCode, featureAppNo, startTime, filmNo, filmName, hallName, hallNo, featureNo, startTime, totalTime, copyType', $featureAppNo);
        $cinemaCode = $plan['cinemaCode'];


        //$otherplans=D('plan')->getplanList('featureAppNo, startTime, hallName, totalTime, copyType, copyLanguage, standardPrice', $featureAppNo, $this->appInfo['cinemaGroupId']);


        $cinema=D('cinema')->find($plan['cinemaCode']);
        //$hallprice=D('cinema')->findHallPrice(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo']));
        $seatinfos=S('seat'.$plan['cinemaCode'].$featureAppNo);
        if(empty($seatinfos)){
            $seats=D('ZMMove')->getPlanSiteState(array('cinemaCode'=>$plan['cinemaCode'],'featureAppNo'=>$featureAppNo,'link'=>$cinema['link'],'hallNo'=>$plan['hallNo'],'filmNo'=>$plan['otherfilmNo'],'showSeqNo'=>$plan['featureNo'],'planDate'=>$plan['startTime']));
            $seatinfos=D('seat')->seatInfos($seats['PlanSiteState']);
            // print_r($seatinfos);
            S('seat'.$plan['cinemaCode'].$featureAppNo,$seatinfos,10);
        }
        //$data['plan']=$plan;
        //$data['url']=$_SERVER["REQUEST_URI"];
        //$data['otherplans']=$otherplans;
        //$data['hallprice']=$hallprice;
        //$data['seatinfos']=$seatinfos;
        $this->success('', $seatinfos, 30);//获取成功！
    }
    /**
     * 锁定座位
     */
    public function lockSeat(){
    	$featureAppNo=I('featureAppNo');
    	$mobile=I('mobile');
    	$datas=I('datas');
    	if(empty($featureAppNo)||empty($mobile)||empty($datas)){
    		$this->error('参数错误！', '11001');
    	}
    	$plan=M('cinemaPlan')->field('cinemaCode,featureAppNo,cinemaName,hallNo,hallName,filmNo,filmName,startTime,copyType')->find($featureAppNo);
    	if(strstr($plan['hallName'],'VIP')&&$plan['cinemaCode']=='35013901'){
    		$price=C('OTHER_PRICE_VIP_35013901');
    	}else{
    		$price=C('OTHER_PRICE_'.$plan['copyType'].'_'.$plan['cinemaCode']);
    	}
    	if(empty($price)){
    		$this->error('价格未编辑');
    	}
    	$datas=explode(',',$datas);
    	foreach ($datas as $k=>$value){
    		$seats[$k]['seatNo']=$value;
    		$seats[$k]['ticketPrice']=$price;
    	}
    	$map=array(
    			'cinemaCode'=>$plan['cinemaCode'],
    			'featureAppNo'=>$featureAppNo,
    			'seatInfos'=>$seats,
    			'mobile'=>$mobile,
    	);
    	$lock=D('ZMMove')->checkSeatState($map);
    	if($lock['ResultCode']=='0'){
    		$orderarr=D('order')->addObj($lock,$plan,$seats,$mobile,$this->appInfo['cinemaGroupId']);
    		if(!empty($orderarr)){
    			$this->success('锁座成功',$orderarr);
    		}else{
    			$this->error('锁座成功，添加订单数据失败');
    		}
    	}else{
    		$this->error($lock['Message']);
    	}
    }
    
	/**
	 * 下单
	 */
	public function gopay(){
		$orderid=I('orderCode');
		if(empty($orderid)){
			$this->error('参数错误！', '11001');
		}
		$order=M('orderFilmOther')->where(array('orderCode'=>$orderid))->find();
		if(empty($order)){
			$this->error('订单不存在');
		}
		$mypay=S('payother'.$orderid);
		if(empty($mypay)){
			S('payother'.$orderid,1,60);
		}else{
			$this->error('您正在确定订单，请不要重复操作');
		}
		
		$a=D('order')->setOrderArray($order,$this->orderlog);
		if($a['ResultCode']=='0'){
			$orderarr['orderCode']=$orderid;
			$orderarr['verifyCode']=$a['VerifyCode'];
			M('orderFilmOther')->save($orderarr);
		}
		/*$b=D('order')->getOrderStatus($order,$this->orderlog);
		if($b['ResultCode']=='0'&&$b['OrderStatus']=='0'){
			wlog('[订单成功]'.arrayeval($b), $this->orderlog);
			$orderarr['orderCode']=$order['orderCode'];
			$orderarr['printNo']=$a['PrintNo'];
			$orderarr['status']=3;//订单完成
			$orderarr['orderTime']=time();
			$orderarr['verifyCode']=$a['VerifyCode'];
			M('orderFilmOther')->save($orderarr);
			$this->success('订单成功',array('orderCode'=>$order['orderCode'],'verifyCode'=>$a['VerifyCode']));
		}else{
			$this->error('确定订单失败');
		}*/
		$this->success('下单成功！');
	}
	
	/**
	 * 查询订单状态
	 */
	function getOrderStatus(){
		$orderid=I('orderCode');
		if(empty($orderid)){
			$this->error('参数错误！', '11001');
		}
		$order=M('orderFilmOther')->field('orderCode,cinemaCode,serialNum')->where(array('orderCode'=>$orderid))->find();
		if(empty($order)){
			$this->error('订单不存在');
		}
		$b=D('order')->getOrderStatus($order,$this->orderlog);
        $backArray['OrderStatus'] = $b['OrderStatus'];
        $backArray['orderCode'] = $b['OrderNo'];
        $backArray['VerifyCode'] = $b['VerifyCode'];
        if ($b['ResultCode'] == 0 && $b['OrderStatus']=='0') {
            $this->success('', $backArray);
        }else{
            unset($backArray['VerifyCode']);
            if($backArray['OrderStatus']=='2'){
            	$text='已退票';
            }else{
            	$text='订单还未成功';
            }
            $this->error($text, 1, $backArray);
        }
		
	}
	
	/**
	 * 退票
	 */
	function backTicket(){
		//$this->error('功能未开放');
		$orderid=I('orderCode');
		$order=M('orderFilmOther')->find($orderid);
		if($order['startTime']<time()){
			$this->error('已经超过放映时间');
		}
		$result=D('ZMMove')->backTicket($order);
		if($result['ResultCode']=='0'){
			$orderarr['orderCode']=$orderid;
			$orderarr['status']=11;
			M('orderFilmOther')->save($orderarr);
			$this->success('退票成功',$result);
		}else{
			$this->error('退票失败','1',$result);
		}
		
	}
}