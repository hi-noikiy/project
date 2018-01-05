<?php
/*
 * autor: Mr.xu
 *
 */
class PayController extends Controller{
	private $menus;
    public function init(){
        parent::init();
        $this->layout = false;
        $this->title = '充值中心';
        $this->keyword = '充值中心';
        $this->desc = '充值中心';
        $this->menu = array(
        		1=>array('money'=>'6','desc'=>'60钻石','gift'=>''),
        		2=>array('money'=>'30','desc'=>'300钻石','gift'=>''),
        		3=>array('money'=>'128','desc'=>'1280钻石','gift'=>''),
        		4=>array('money'=>'328','desc'=>'3280钻石','gift'=>''),
        		5=>array('money'=>'648','desc'=>'6480钻石','gift'=>''),
        		6=>array('money'=>'25','desc'=>'月卡','gift'=>'领取120钻石/天'),
        		//7=>array('money'=>'98','desc'=>'狩猎月卡','gift'=>'领取5张狩猎券/天'),
        		//8=>array('money'=>'0.01','desc'=>'测试1分钱','gift'=>''),
        		//9=>array('money'=>'1','desc'=>'测试1元钱','gift'=>''),
        );

    }
    /**
     * 礼包直购
     */
    public function recharge(){
    	$userinfo = Yii::app()->session['userinfo'];
    	$serverinfo = Yii::app()->session['serverinfo'];
    	if(!$userinfo || !$serverinfo || !$serverinfo[8][$userinfo['server_id']]){
    		exit('参数错误');
    	}
    	if(isset($_GET['pay']) && $_GET['pay'] == 'ali'){
    		$tinfo = Yii::app()->session['tinfo'];
    		if(!$tinfo){
    			exit('没有这个档次的充值金额');
    		}
    		$serverId = $userinfo['server_id'];
    		$accountId = $userinfo['account_id'];
    		$out_trade_no = $this->getOrderId('8_'.$serverId.'_'.$accountId.'_');
    		//订单名称，必填
    		//付款金额，必填
    		$total_amount = intval($tinfo['money']);
    		//$total_amount = 0.01;
    		//超时时间
    		//$url = "http://pokeweb.u591776.com:84/interface/alipay/alipayapi.php";
    		$url = "http://pokeweb.u591776.com:84/interface/aliwappay/alipayapi.php";
    		$data = array(
    				'WIDout_trade_no' =>$out_trade_no,
    				'WIDsubject'    =>'官网wap充值',
    				'WIDtotal_fee'  =>$total_amount,
    				'WIDbody'       =>'',
    		);
    		$rs =$this->https_post($url, $data);
    		echo $rs;
    		return ;
    	}
    
    	$t = $_GET['t'];
    	$tinfo = $this->menu[$t];
    	if(!$tinfo){
    		exit('没有这个档次的充值金额');
    	}
    	Yii::app()->session['tinfo'] = $tinfo;
    	$this->render('info', array(
    			'title'=>'充值中心','server_name'=>$serverinfo[8][$userinfo['server_id']],'player_name'=>$userinfo['player_name'],'tinfo'=>$tinfo
    			// 'gameServer' => $this->getServer(),
    	));
    }
    public function actionIndex(){

    	if(!isset($_GET['server_id']) || !isset($_GET['player_id']))
    		exit('参数错误');
    	$result = $this->checkPlayer($_GET['server_id'],$_GET['player_id']);
    	$result = json_decode($result,true);
    	if($result['status'] == 1){
    		exit($result['msg']);
    	}
    	$player_name = $result['data']['name'];
    	$server_id = $_GET['server_id'];
    	$serverinfo = Yii::app()->session['serverinfo'];
    	if(!$serverinfo){
    		$serverinfo = GameServer::model()->getInfo();
    		Yii::app()->session['serverinfo'] =$serverinfo ;
    	}
    	if(!isset($serverinfo[8][$server_id])){
    		exit('未授权区服');
    	}
        $this->render('index', array(
            'title'=>'充值中心','server_name'=>$serverinfo[8][$server_id],'player_name'=>$player_name,'server_id'=>$server_id,'menu'=>$this->menu
           // 'gameServer' => $this->getServer(),
        ));
    }
    
   
    public function actionInfo(){
    	$userinfo = Yii::app()->session['userinfo'];
    	$serverinfo = Yii::app()->session['serverinfo'];
    	if(!$userinfo || !$serverinfo || !$serverinfo[8][$userinfo['server_id']]){
    		exit('参数错误');
    	}
    	if(isset($_GET['pay']) && $_GET['pay'] == 'ali'){
    		$tinfo = Yii::app()->session['tinfo'];
    		if(!$tinfo){
    			exit('没有这个档次的充值金额');
    		}
    		$serverId = $userinfo['server_id'];
    		$accountId = $userinfo['account_id'];
    		$out_trade_no = $this->getOrderId('8_'.$serverId.'_'.$accountId.'_');
    		//订单名称，必填
    		//付款金额，必填
    		$total_amount = intval($tinfo['money']);
    		//$total_amount = 0.01;
    		//超时时间
    		//$url = "http://pokeweb.u591776.com:84/interface/alipay/alipayapi.php";
    		$url = "http://pokeweb.u591776.com:84/interface/aliwappay/alipayapi.php";
    		$data = array(
    				'WIDout_trade_no' =>$out_trade_no,
    				'WIDsubject'    =>'官网wap充值',
    				'WIDtotal_fee'  =>$total_amount,
    				'WIDbody'       =>'',
    		);
    		$rs =$this->https_post($url, $data);
    		echo $rs;
    		return ;
    	}

    	 $t = $_GET['t'];
    	 $tinfo = $this->menu[$t];
    	 if(!$tinfo){
    	 	exit('没有这个档次的充值金额');
    	 }
    	 Yii::app()->session['tinfo'] = $tinfo;
    	 $this->render('info', array(
    	 		'title'=>'充值中心','server_name'=>$serverinfo[8][$userinfo['server_id']],'player_name'=>$userinfo['player_name'],'tinfo'=>$tinfo
    	 		// 'gameServer' => $this->getServer(),
    	 ));
    }



    public function checkPlayer($server_id,$player_id){
    	if(!isset($server_id) || !isset($player_id))
    		exit(array('status'=>1,'msg'=>'参数错误'));
        $url = "http://gunweb.u591.com:83/interface/website/checkUser.php";
        $array = array();
        $array['game_id'] = $this->gameId;
        $array['server_id'] = $server_id;
        $array['player_name'] = $player_id;
        $mySign = $this->httpBuidQuery($array, $this->appKey);
        $array['sign'] = $mySign;
        $result = $this->https_post($url, $array);
        $myresult = json_decode($result,true);
        if(isset($myresult['status'] ) && $myresult['status'] == 0){
        	Yii::app()->session['userinfo']=array('account_id'=>$myresult['data']['accountId'],'player_name'=>$myresult['data']['name'],'server_id'=>$server_id);
        }
        return $result;
        
    }

    private function getOrderId($pre = ''){
        return $pre.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

}