<?php
/*
 * autor: Mr.xu
 *
 */
class PayController extends Controller{
    public function init(){
        parent::init();
        $this->title = '充值中心';
        $this->keyword = '充值中心';
        $this->desc = '充值中心';

    }
    public function actionIndex(){

    	if(!isset($_GET['server_id']) || !isset($_GET['player_name']))
    		exit('参数错误');
    	$player_name = $_GET['player_name'];
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
            'title'=>'充值中心','server_name'=>$serverinfo[8][$server_id],'player_name'=>$player_name,'server_id'=>$server_id
           // 'gameServer' => $this->getServer(),
        ));
    }
    public function actionInfo(){
    
    	if(isset($_POST['pay']) && $_POST['pay'] == 'ali'){
    		$serverId = $_POST['serverId'];
    		$accountId = $_POST['accountId'];
    		$out_trade_no = $this->getOrderId('8_'.$serverId.'_'.$accountId.'_');
    		//订单名称，必填
    		//付款金额，必填
    		$total_amount = intval($_POST['money']);
    		//超时时间
    		$url = "http://hejin.u591.com/interface/alipay/alipayapi.php";
    		$data = array(
    				'WIDout_trade_no' =>$out_trade_no,
    				'WIDsubject'    =>'官网充值',
    				'WIDtotal_fee'  =>$total_amount,
    				'WIDbody'       =>'',
    		);
    		$rs =$this->https_post($url, $data);
    		echo $rs;
    		return ;
    	} else if(isset($_POST['pay']) && $_POST['pay'] == 'wx') {
    		$serverId = $_POST['serverId'];
    		$accountId = $_POST['accountId'];
    		//$playerName = $_POST['playerName'];
    		$orderId = $this->getOrderId('8_'.$serverId.'_'.$accountId.'_');
    		//订单名称，必填
    		$subject = '官网充值';
    		//付款金额，必填
    		$totalAmount = intval($_POST['money']);
    		$productId = 'product_'.$totalAmount;
    		$array = array(
    				'trade_no'=>$orderId,
    				'body'=>$subject,
    				'attach'=>$subject,
    				'product_id'=>$productId,
    				'tag'=>'充值',
    				'total_fee'=>$totalAmount*100,
    				'notify_url'=>'http://gunweb.u591.com:83/interface/wepay/notify.php',
    		);
    		$this->layout='column1';
    		$url = $this->wxPay($array);
    		$this->render('wx', array('url'=>$url,'totalFee'=>number_format($totalAmount,2)));
    		return ;
    	}
    	/* $userinfo = Yii::app()->session['userinfo'];
    	 if(!$userinfo ){
    	 if(!isset($_GET['server_id']) || !isset($_GET['player_name']))
    	 	exit('false');
    	 	$player_name = $_GET['player_name'];
    	 	$server_id = $_GET['server_id'];
    	 	$result = $this->actionCheckPlayer($player_name,$server_id);
    	 	$result = json_decode($result,true);
    	 	if($result['status'] != 0){ //角色验证失败
    	 	exit($result['msg']);
    	 	}
    	 	Yii::app()->session['userinfo']=array('account_id'=>$result['accountId'],'player_name'=>$player_name,'server_id'=>$server_id);
    	 	}*/
    	  
    	 if(!isset($_GET['server_id']) || !isset($_GET['player_name']))
    	 	exit('参数错误');
    	 $player_name = $_GET['player_name'];
    	 $server_id = $_GET['server_id'];
    	 $serverinfo = Yii::app()->session['serverinfo'];
    	 if(!$serverinfo){
    	 	$serverinfo = GameServer::model()->getInfo();
    	 	Yii::app()->session['serverinfo'] =$serverinfo ;
    	 }
    	 if(!isset($serverinfo[8][$server_id])){
    	 	exit('未授权区服');
    	 }
    
    	 $this->render('info', array(
    	 		'title'=>'充值中心','server_name'=>$serverinfo[8][$server_id],'player_name'=>$player_name,'server_id'=>$server_id
    	 		// 'gameServer' => $this->getServer(),
    	 ));
    }


    private function wxPay($data){
        if(empty($data))
            return false;
        $url = 'http://pokemon.u776.com/interface/wepay/native.php';
        $result = $this->https_post($url, $data);
        return $result;
    }

    public function actionAlipay(){
        //支付宝同步通知
        $this->redirect('http://pokemon.u776.com/pay');
    }


    public function actionCheckPlayer(){
    	if(!isset($_POST['server_id']) || !isset($_POST['player_name']))
    		exit(array('status'=>1,'msg'=>'参数错误'));
        $url = "http://gunweb.u591.com:83/interface/website/checkPlayer.php";
        $array = array();
        $array['game_id'] = $this->gameId;
        $array['server_id'] = $_POST['server_id'];
        $array['player_name'] = $_POST['player_name'];
        $mySign = $this->httpBuidQuery($array, $this->appKey);
        $array['sign'] = $mySign;
        $result = $this->https_post($url, $array);
        $myresult = json_decode($result,true);
        if($myresult['status'] == 0){
        	Yii::app()->session['userinfo']=array('account_id'=>$result['accountId'],'player_name'=>$player_name,'server_id'=>$server_id);
        }
        exit($result);
        
    }

    private function getOrderId($pre = ''){
        return $pre.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

}