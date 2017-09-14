<?php
/**
 * 首页
 */

namespace Home\Controller;
use Think\Controller;
class IndexController extends InitController {
	

    /**
     * 影院首页
     */
    public function index(){
        $pflag=$this->pflag;
        if(!empty($pflag)){
            session('pflag',$pflag);
            $cinemaCode=$pflag;
        }else{
            $cinemaCode=I('cinemaCode');
        }

        if($cinemaCode){
            session('cinemaCode',$cinemaCode);
            $op=I('op');
            if(!empty($op)){
                Header('Location:'.U('plan'));
                die();
            }
        }elseif(session('cinemaCode')){
            $cinemaCode = session('cinemaCode');
        }else{
            $cinemaCode=D('cinema')->getFirstCinema();
            session('cinemaCode',$cinemaCode);
        }
        $cinema=D('cinema')->find($cinemaCode);
        $banners=D('banner')->getList(array('type'=>0,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
        // print_r($banners);
        $tarr['publishDate']=array('egt',time());
        $tarr['filmNo']=array('eq','');
        $data['prein']=D('film')->countObj($tarr);
        $data['curin']=D('plan')->getcurin(array('cinemaCode'=>$cinemaCode));
        $ad=D('banner')->getObj(array('type'=>1,'cinemaGroupId'=>$this->weiXinInfo['cinemaGroupId']));
        $this->assign('ad',$ad);
        $this->assign('data',$data);
        $this->assign('cinema',$cinema);
        $this->assign('weiXinInfo',$this->weiXinInfo);
        $this->assign('banners',$banners);
        $this->display();
    }
	
	
	/**
	 * 影片是否编辑
	 */
	function hasfilm(){
		$arr['filmNo']=I('filmNo');
		$film=D('film')->findObj($arr);
		if(empty($film)){
			echo '0';
		}else{
			echo '1';
		}
	}
	/**
	 * 影院列表
	 */
    public function cinemalist(){
    	$op=I('op');

        if(empty($op)){
            $op=session('op');
            //session('op', null);
        }
    	$filmNo=I('filmNo');
    	$citys=D('cinema')->citys($op,$filmNo);
    	foreach ($citys as $key=>$city){
    		foreach ($city['cinemas'] as $k=>$cinema){
    			$citys[$key]['cinemas'][$k]['distance']=number_format(getDistance($y,$x,$cinema['longitude'],$cinema['latitude'])/1000,2);
    		}
    	}
		$this->assign('citys',$citys);
    	$this->display();
    }
    public function activity(){
    	$cinemaCode=I('cinemaCode');
    	$where['type']=2;
    	if(!empty($cinemaCode)){
    		$where['cinemaCode']=$cinemaCode;
    	}
    	$acts=D('banner')->getList($where);
    	$this->assign('acts',$acts);
    	$this->display();
    }
	
	
    /**
     * 影片排期
     */
    public function cinemaplan(){

        $cinemaCode=session('cinemaCode');
    	$filmNo=$map['filmNo']=I('filmNo');
    	if(empty($cinemaCode)){
    		header('location:'.U('index/cinemalist',array('op'=>'cinemaplan','filmNo'=>$filmNo,'cinemaCode'=>$cinemaCode)));
    		die();
    	}
    	$map['cinemaCode']=$cinemaCode;
    	$films=D('plan')->getcinemaFilms($cinemaCode,$filmNo);
    	$user=session('ftuser');
    	if(empty($user)){
    		$mystr=D('memberType')->findtype($cinemaCode);
    	}else{
    		$mystr=$user['memberGroupId'];
    	}

    	$planTime=D('Plan')->gettime($map);//时间列表
    	$plans=D('plan')->findplans($planTime[0]['time'],$cinemaCode,$map['filmNo'],$mystr);
    	$this->assign('time',$planTime);
    	$this->assign('films',$films);
    	$this->assign('plans',$plans);


    	$this->display();
    }
    /**
     * 影片排期ajax
     */
    public function cinemaplanajax(){
    	$filmNo=I('filmNo');
    	$time=I('startTime');
    	$cinemaCode=session('cinemaCode');
    	$user=session('ftuser');
    	if(empty($user)){
    		$mystr=D('memberType')->findtype($cinemaCode);
    	}else{
    		$mystr=$user['memberGroupId'];
    	}
    	$plans=D('plan')->findplans($time,$cinemaCode,$filmNo,$mystr);
    	echo json_encode($plans);
    }
    /**
     * 影院排期ajax
     */
    public function cinemaajax(){
    	$map['filmNo']=I('filmNo');
    	$map['cinemaCode']=$cinemaCode=session('cinemaCode');
    	$user=session('ftuser');
    	if(empty($user)){
    		$mystr=D('memberType')->findtype($cinemaCode);
    	}else{
    		$mystr=$user['memberGroupId'];
    	}
    	$planTime=D('Plan')->gettime($map);//时间列表
    	$plans=D('plan')->findplans($planTime[0]['time'],$cinemaCode,$map['filmNo'],$mystr);
    	$data['planTime']=$planTime;
    	$data['plans']=$plans;
    	echo json_encode($data);
    }
    /**
     * 影片详情
     */
    public function details(){
		$filmid=I('filmid');
		$filmNo=I('filmNo');
    	if(!empty($filmid)){
    		$arr['id']=$filmid;
    	}
    	if(!empty($filmNo)){
    		$arr['filmNo']=$filmNo;
    	}
    	$film=D('film')->findObj($arr);

    	$film['imgs']=explode(';', substr($film['imgs'], 0,-1));
    	$this->assign('film',$film);
    	$page= I('page');
    	if(empty($page)){
    		$page=1;
    	}
    	$start=($page-1)*$this->pageNum;
    	$map['filmId']=$film['id'];
    	$map['status']=0;
    	$map['pid']=0;
    	$map['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
    	$views=D('film')->getViews($map,$start,$this->pageNum);
    	$user=session('ftuser');
    	if(!empty($user)){
    		$myviews=explode(',', $user['onview']);  //点赞过的
    		foreach ($views as $k=>$v){
    			if(in_array($v['id'], $myviews)){
    				$views[$k]['hasClick']='1';
    			}else{
    				$views[$k]['hasClick']='0';
    			}
    		}
    	}
    	$this->assign('user',$user);
        $this->assign('filmNo',$filmNo);
        $this->assign('views',$views);
    	$this->display();
    }
	/**
	 * 正在热映
	 */
    public function film(){
    	$cinemaCode=I('cinemaCode');
    	if(empty($cinemaCode)){
    		$cinemaCode=session('cinemaCode');
    	}else{
    		session('cinemaCode',$cinemaCode);
    	}
    	$cinema=D('cinema')->find($cinemaCode);
    	$this->assign('cinema',$cinema);
    	$films=D('plan')->getFilms('cinemaCode="'.$cinemaCode.'"');
    	$this->assign('films',$films);
    	$this->display();
    }
    public function filmplan(){
    	$this->display();
    }
    public function movielist(){
    	$this->display();
    }
    /**
     * 排期
     */
    public function plan(){
    	$cinemaCode=I('cinemaCode');
    	if(!empty($cinemaCode)){
    		session('cinemaCode',$cinemaCode);
    	}else{
    		$cinemaCode = session('cinemaCode');
    	}
    	$tarr['cinemaCode']=$cinemaCode;
    	$op=I('op');

    	If(!empty($op) && empty($cinemaCode)){
    		Header('Location:'.U('cinemalist',array('op'=>$op)));
    		die();
    	}
    	$user=session('ftuser');
    	if(empty($user)){
    		$mystr=$this->weiXinInfo['defaultLevel'];
    	}else{
    		$mystr=$user['memberGroupId'];
    	}
        $couponsMap['buyingEndTime'] = array('egt',time());
        $couponsList = D('Coupons')->couponsList('couponId, couponName, couponSum, newPrice', $couponsMap, '', 'buyingStartTime asc');

        foreach ($couponsList as $key => $value) {
            $resultData = D('Coupons')->getSurplusSum($value['couponId']);
            if ($resultData['couponSum'] <= 0) {
                unset($couponsList[$key]);
            }
            $couponsList[$key]['couponSum'] = $resultData['couponSum'];
        }
        
    	$planTime=D('Plan')->gettime(array('cinemaCode'=>$cinemaCode));//时间列表
    	$cinema=D('cinema')->findObj($tarr);
        $this->assign('couponsList',$couponsList);
        $this->assign('time',$planTime);
    	$this->assign('cinema',$cinema);

    	$this->display();
    }
    /**
     * 排期ajax
     */
    public function planajax(){
    	$cinemaCode=I('request.cinemaCode');
    	$time=I('startTime');
    	$user=session('ftuser');
     	if(empty($user)){
    		$mystr=$this->weiXinInfo['defaultLevel'];
    	}else{
    		$mystr=$user['memberGroupId'];
    	}
		$films=D('plan')->planInfos($time,$cinemaCode,$mystr);
    	echo json_encode($films);
    }
    /**
     * 座位图
     */
    public function seat(){
    	//delDirAndFile('Runtime');
        $weiXinInfo = getWeiXinInfo();
    	$user=session('ftuser');
    	$user=$this->getBindUserInfo($user);
    	$featureAppNo=I('featureAppNo');

        $plan=D('plan')->getplanInfo('cinemaCode, cinemaName,featureAppNo, listingPrice,startTime, filmNo, filmName, hallName, priceConfig, hallNo, otherfilmNo, featureNo, startTime, totalTime, copyType', $featureAppNo);
        $cinemaCode = $plan['cinemaCode'];

    	
    	if(empty($user)){
    		$mystr=D('memberType')->findtype($cinemaCode);
    	}else{
    		$this->assign('user',$user);
    		$mystr=$user['memberGroupId'];
    		if(!empty($user['businessCode'])){
    			$tflag=D('cinema')->isInCinemas($cinemaCode,$user['businessCode']);
    			$this->assign('tflag',$tflag);
    		}
    	}
        $priceConfig = json_decode($plan['priceConfig'],true);
        $priceConfig = $priceConfig[$weiXinInfo['cinemaGroupId']];

    	if(!empty($priceConfig)&&$priceConfig[$mystr]){
    		$plan['memberPrice']=$priceConfig[$mystr];
    	}else{
    		$plan['memberPrice']=$plan['listingPrice'];
    	}

    	$otherplans=D('plan')->getplans($featureAppNo,$mystr);


    	$cinema=D('cinema')->find($plan['cinemaCode']);
    	$hallprice=D('cinema')->findHallPrice(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo']));
    	// $seatinfos=S('seat'.$plan['cinemaCode'].$featureAppNo);
    	if(empty($seatinfos)){
    		$seats=D('ZMMove')->getPlanSiteState(array('cinemaCode'=>$plan['cinemaCode'],'featureAppNo'=>$featureAppNo,'link'=>$cinema['link'],'hallNo'=>$plan['hallNo'],'filmNo'=>$plan['otherfilmNo'],'showSeqNo'=>$plan['featureNo'],'planDate'=>$plan['startTime']));
            $seatinfos=D('seat')->seatInfos($seats['PlanSiteState']);
    		S('seat'.$plan['cinemaCode'].$featureAppNo,$seatinfos,30);
    	}
    	$this->assign('plan',$plan);
    	$this->assign('url',$_SERVER["REQUEST_URI"]);
    	$this->assign('otherplans',$otherplans);
    	$this->assign('hallprice',$hallprice);
    	$this->assign('seatinfos',$seatinfos);
    	$this->display();
    }
    /**
     * 即将上映
     */
    public function soon(){
    	$tarr['publishDate']=array('egt',time());
    	$tarr['filmNo']=array('eq','');
    	$films=D('film')->getList($tarr);
    	foreach ($films as $k=>$v){
    		$t[$k]=explode('.', number_format($v['score']/10,1));
    		$films[$k]['f']=$t[$k][0];
    		$films[$k]['s']=$t[$k][1];
    		$films[$k]['publishDate']=date('n月j日',$v['publishDate']);
    	}
    	$this->assign('films',$films);
    	$this->display();
    }

    
    /**
     * 卖品列表
     */
    public function goodslist(){
    	$cinemaGroupId=$this->weiXinInfo['cinemaGroupId'];
    	$cinemaCode=I('cinemaCode');
		if(empty($cinemaCode)){
    		$cinemaCode=session('cinemaCode');
    	}else{
    		session('cinemaCode',$cinemaCode);
    	}
    	$cinema=D('cinema')->find($cinemaCode);
    	$map['cinemaGroupId']=$this->weiXinInfo['cinemaGroupId'];
    	$map['cinemaCode']=$cinemaCode;
    	$this->assign('cinema',$cinema);
    	$goods=D('goods')->getCinemaGoods($map);
    	$this->assign('goods',$goods);
    	$this->display();
    }
    
    /**
     * 周边列表
     */
    public function roundlist(){
    	$cinemaCode=I('cinemaCode');
    	if(empty($cinemaCode)){
    		$cinemaCode=session('cinemaCode');
    	}else{
    		session('cinemaCode',$cinemaCode);
    	}
    	$cinema=D('cinema')->find($cinemaCode);
    	$this->assign('cinema',$cinema);
    	$goods=D('goods')->getRoundGoods($cinemaCode);
    	$this->assign('goods',$goods);
    	$this->display();
    }
    
    /**
     * 周边卖品详情
     */
    function roundinfo(){
    	$goodsId=I('goodsId');
    	session('roundid',$goodsId);
    	$goods=D('goodsRound')->find($goodsId);
    	$goods['goodsImg']=C('IMG_URL').'Uploads/'.$goods['goodsImg'];
    	$detailss=explode(';', $goods['detail']);
    	$price=0;
    	foreach ($detailss as $v){
    		$goods['details'][]=$details=explode(',', $v);
    		$price+=$details[1]*$details[2];
    	}
    	$goods['showPrice']=$price;
    	unset($goods['detail']);
    	$seller=D('goodsSeller')->find($goods['sellerNo']);
    	unset($seller['account']);
    	unset($seller['passwd']);
    	unset($goods['visible']);
    	unset($goods['priority']);
    	unset($goods['sellerNo']);
    	$goods['seller']=$seller;
    
    	$goods['explain'] = '&lt;html&gt;&lt;body&gt;' . $goods['explain'] . '&lt;/body&gt;&lt;/html&gt;';
    	$goods['tip'] = '&lt;html&gt;&lt;body&gt;' . $goods['tip'] . '&lt;/body&gt;&lt;/html&gt;';
    	$this->assign('goods',$goods);
    	$this->display();
    }
    
    /**
     * 周边详情图片
     */
    function rounddetail(){
    	 
    	$goodsId=I('goodsid');
    	$goods=D('goodsRound')->find($goodsId);
    	$this->assign('detail',$goods['detailImg']);
    	$this->display();
    }
    
    
    /**
     * 获取二维码
     */
    public function getQRcode() {
    	$size = 10;
    	$orderid = I('orderid');
    	$code = I('code');
    	$data = substr(C('IMG_URL'), 0,-1). U('index', array('orderid'=>$orderid,'code' => $code ));
    	// die($data);
    	// 纠错级别：L、M、Q、H
    	$level = 'L';
    	// 点的大小：1到10,用于手机端4就可以了
    	$size = $size;
    	// 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
    	// $path = "Upload/";
    	// // 生成的文件名
    	// $fileName = $path.$size.'.png';
    
    	vendor("phpqrcode.phpqrcode");
    	$QRcode = new \QRcode();
    	$QRcode->png($data, false, $level, $size);
    	die();
    }
    
}