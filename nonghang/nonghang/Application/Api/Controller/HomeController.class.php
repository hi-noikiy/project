<?php
namespace Api\Controller;
use Think\Controller;

class HomeController extends ServiceController {
 
	/**
	 * 3.1首页BANNER
	 */
	function getBanner(){

		// $map['appid']=$this->appInfo['appId'];//$this->appInfo['appId'];
		$map['type'] = (int)$this->param['type'];  //头部banner
		if(empty($map['type'])){
			$map['type']=0;
		}
		$banners=D('banner')->getAppBanners($map);
		$this->success('', $banners, 3600);
	}

	/**
	 * 获取影院信息
	 */
	function getCinemaInfo(){
		$map['cinemaCode']=$this->param['cinemaCode'];
		if(empty($map['cinemaCode'])){
			$this->error('参数错误！', '11001');
		}
		$cinema=D('cinema')->getCinemaInfo('cinemaName,cinemaStyle,serviceCharge',$map);
		$this->success('', $cinema, 3600);
	}

    /**
     * 3.2获取热映影片
     */
    function getHotMove(){
    	if(empty($this->param['cinemaCode'])){
    		$this->error('参数错误！', '11001');
    	}
    	$page= $this->param['page'];
    	if(empty($page)){
    		$page=1;
    	}


    	$start=($page-1)*$this->pageNum;
        $films=D('plan')->getFilms('cinemaCode="'.$this->param['cinemaCode'].'"',$start,$this->pageNum);
        
        $this->success('', $films, 1);
    }    
    /**
     * 即将上映
     */
    public function getSoonMove(){
    	$page= $this->param['page'];
    	if(empty($page)){
    		$page=1;
    	}
    	$start=($page-1)*$this->pageNum;
    	$tarr['publishDate']=array('egt',time());
    	$tarr['filmNo']=array('eq','');
    	$films=D('film')->getList($tarr,$start,$this->pageNum);
    	$user=$this->appInfo['userInfo'];
    	$myfilms=array();
    	if(!empty($user)&&!empty($user['onfilm'])){
    		$myfilms=explode(',', $user['onfilm']);
    	}
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
    	$data['films']=$films;
    	$this->success('', $data, 3600);
    }
    
    
    /**
     * 影院卖品
     */
    function cinemaGoods(){
    	
    	$cinemaCode=$this->param['cinemaCode'];
    	$page= $this->param['page'];
    	if(empty($page)){
    		$page=1;
    	}
    	$start=($page-1)*$this->pageNum;
    	$map['cinemaGroupId']=$this->appInfo['cinemaGroupId'];
    	$map['cinemaCode']=$cinemaCode;
    	$goods=D('goods')->getCinemaGoods($map,$start,$this->pageNum);
    	if(empty($goods)){
    		$this->success('');//
    	}
    	$this->success('',$goods, 3600);
    }
    
    /**
     * 周边卖品
     */
    function roundGoods(){
    	$cinemaCode=$this->param['cinemaCode'];
    	$page= $this->param['page'];
		if(empty($page)){
			$page=1;
		}
		$start=($page-1)*$this->pageNum;
    	$goods=D('goods')->getRoundGoods($cinemaCode,$start,$this->pageNum);
    	if(empty($goods)){
    		$this->success('');//
    	}
    	$this->success('',$goods, 3600);
    }
    
    /**
     * 周边卖品详情
     */
    function getRound(){
    	$goodsId=$this->param['goodsId'];
    	$goods=D('goodsRound')->find($goodsId);
    	$goods['goodsImg']=C('IMG_URL').'Uploads/'.$goods['goodsImg'];
    	$goods['detailImg']=substr(C('IMG_URL'), 0,-1).U('api/home/rounddetail',array('goodsId'=>$goodsId));
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
    	$this->success('',$goods, 0);
    }
    
    /**
     * 周边详情图片
     */
    function rounddetail(){
    	
    	$goodsId=I('goodsId');
    	$goods=D('goodsRound')->find($goodsId);
    	$this->assign('detail',$goods['detailImg']);
    	$this->display();
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
    	$this->success('', $film, 3600);
    }
    
    /**
     * 影片评论
     */
    function filmViews(){
    	$map['filmId']=$this->param['filmId'];
    	if(empty($map['filmId'])){
    		$this->error('参数错误！', '11001');
    	}
    	$page= $this->param['page'];
    	if(empty($page)){
    		$page=1;
    	}
    	$start=($page-1)*$this->pageNum;
    	$map['status']=0;
    	$map['pid']=0;
		$map['cinemaGroupId']=$this->appInfo['cinemaGroupId'];
    	$views=D('film')->getViews($map,$start,$this->pageNum);
    	$user=$this->appInfo['userInfo'];
    	if(!empty($user)){
    		$myviews=explode(',', $user['onview']);  //点赞过的
    		foreach ($views as $k=>$v){
    			if(in_array($v['id'], $myviews)){
    				$views[$k]['hasClick']='1';
    			}
    		}
    	}
    	$this->success('', $views, 3600);
    }
    
    /**
     * 评论回复
     */
    function backViews(){
    	$map['pid']=$this->param['pid']; //评论id
    	if(empty($map['pid'])){
    		$this->error('参数错误！', '11001');
    	}
    	$map['status']=0;
    	$map['cinemaGroupId']=$this->appInfo['cinemaGroupId'];
    	$views['cur']=D('filmView')->find($map['pid']);
    	$user=$this->appInfo['userInfo'];
    	if(!empty($user)){
    		$myviews=explode(',', $user['onview']);  //点赞过的
    		if(in_array($views['cur']['id'], $myviews)){
    			$views['cur']['hasClick']='1';
    		}
    	}
    	$mem=D('member')->find($views['cur']['uid']);
    	$views['cur']['headImage']=C('IMG_URL').'Uploads/'.$mem['headImage'];
    	$views['cur']['otherName']=$mem['otherName'];
    	$views['list']=D('film')->getViews($map);
    	$this->success('', $views);
    }
    
    
    /**
     * 获取排期时间
     */
    function getTimes(){
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
    	$user=$this->getBindUserInfo($this->appInfo['userInfo']);
    	if(empty($user)){

    		$mystr = $cinemaGroupInfo['defaultLevel'];
    	}else{
    		$mystr=$user['memberGroupId'];
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
    public function seat(){
    	// $cinemaCode=$this->param['cinemaCode'];
    	$featureAppNo=$this->param['featureAppNo'];
    	
    	if(empty($featureAppNo)){
    		$this->error('参数错误！', '11001');
    	}
        $plan=D('plan')->getplanInfo('cinemaCode, featureAppNo, startTime, filmNo, filmName, hallName, priceConfig, hallNo, otherfilmNo, featureNo, startTime, totalTime, copyType', $featureAppNo);
        $cinemaCode = $plan['cinemaCode'];

    	$user=$this->getBindUserInfo($this->appInfo['userInfo']);
    	if(empty($user)){
    		$mystr=D('memberType')->findtype($cinemaCode);
    	}else{
    		$mystr=$user['memberGroupId'];
    		if(!empty($user['businessCode'])){
    			$tflag=D('cinema')->isInCinemas($this->appInfo,$user['businessCode']);
    			if($tflag=='1'){
    				$this->error('该会员卡无法购买此影院影票');
    			}
    		}
    	}
        $priceConfig = json_decode($plan['priceConfig'],true);
        $plan['priceConfig'] = $priceConfig[$this->appInfo['cinemaGroupId']];


    	$otherplans=D('plan')->getplanList('priceConfig, featureAppNo, startTime, hallName, otherfilmNo, totalTime, copyType, copyLanguage, standardPrice', $featureAppNo, $this->appInfo['cinemaGroupId']);


    	$cinema=D('cinema')->find($plan['cinemaCode']);
    	$hallprice=D('cinema')->findHallPrice(array('cinemaCode'=>$plan['cinemaCode'],'hallNo'=>$plan['hallNo']));
    	$seatinfos=S('seat'.$plan['cinemaCode'].$featureAppNo);
    	if(empty($seatinfos)){
    		$seats=D('ZMMove')->getPlanSiteState(array('cinemaCode'=>$plan['cinemaCode'],'featureAppNo'=>$featureAppNo,'link'=>$cinema['link'],'hallNo'=>$plan['hallNo'],'filmNo'=>$plan['otherfilmNo'],'showSeqNo'=>$plan['featureNo'],'planDate'=>$plan['startTime']));
    		// print_r($seats);
            $seatinfos=D('seat')->seatInfos($seats['PlanSiteState']);
    		// print_r($seatinfos);
            S('seat'.$plan['cinemaCode'].$featureAppNo,$seatinfos,10);
    	}
    	$data['plan']=$plan;
    	//$data['url']=$_SERVER["REQUEST_URI"];
    	$data['otherplans']=$otherplans;
    	$data['hallprice']=$hallprice;
    	$data['seatinfos']=$seatinfos;
    	$this->success('', $data, 30);//获取成功！
    }
    
    
    /**
     * 获取二维码
     */
    public function getQRcode() {
    	$size = 10;
    	$orderid = $this->param['orderid'];
    	$code = $this->param['code'];
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