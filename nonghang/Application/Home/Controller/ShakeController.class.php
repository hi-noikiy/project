<?php

namespace Home\Controller;
use Think\Controller;
class ShakeController extends ShakeInitController {
	/**
	 * 用户登录
	 * @author
	 */
	public function login(){
		$userId = cookie('uid');

		if(cookie('uid')){
			$this->redirect('index');
			//跳转到摇页面
			die();
		}
		if(IS_POST){
			$placeNo=I('cinemaCode');
			$cardId=I('cardId');
			$passWord=I('pwd');
			/* 登录用户 */
			if(!empty($placeNo)){
				$backJson = file_get_contents("http://www.zrfilm.com/index.php?route=system/api/login&placeNo=$placeNo&cardId=$cardId&passWord=$passWord");
			}else{
				$backJson = file_get_contents("http://www.zrfilm.com/index.php?route=system/api/login&mobile=$cardId&telPassWord=$passWord");
			}
			$suser=json_decode($backJson, true);
			if($suser['status']){ //登录用户
				cookie('uid',$suser['data']['id']);
				$this->success('登录成功',array('url'=>U('index')));
			} else {
				$this->error('登录失败');
			}
		} else {
			$cinemaList = json_decode(file_get_contents('http://www.zrfilm.com/index.php?route=system/api/getCinema'), true);
			$this->assign('data',$cinemaList);
			$this->display();
		}
	}
	/**
	 * 摇奖
	 */
	function index(){
		/***开始判断次数****/
		$this->time=3;
		$map['uid']=cookie('uid');

		$cinemaCode = session('shakeCinemaCode');
		$nowDate = date('Ymd');
		
		$countPrizeMap['updateTime'] = $nowDate;
		$countNprizes=D('prize')->getList('id', $countPrizeMap);

		$map['_string']='FROM_UNIXTIME(time,"%Y%m%d")='.date('Ymd');
		$clog=D('prize')->getLog('count(id) as ci',$map);
		// echo $clog['ci'];
		if($clog['ci'] >= $this->time){
			$this->display('nonumprize');
			die();
		}
		$this->hastime= $this->time-$clog['ci'] - 1;
		/***结束判断次数****/
		
		/***开始获取概率****/
		$nowDate = date('Ymd');
		$prizeMap['cinemaCode'] = $cinemaCode;
		$nprizes=D('prize')->getList('*', $prizeMap);
		foreach ($nprizes as $k=>$v){

			if ($v['updateTime'] != $nowDate) {

				if ($v['allNum'] >= $v['allRemainNum']) {
					if ($v['allNum'] - $v['allRemainNum'] >= 0) {
						$v['remainNum'] = $v['dateNum'];
					}else{
						$v['remainNum'] = $v['allNum'] - $v['allRemainNum'];
					}
					
				}else{
					$v['remainNum'] = 0;
				}

				$v['updateTime'] = $nowDate;
            	M('prizeZr')->save($v);
			}

			$prizes[$v['id']]=$v;
		}
    	foreach ($prizes as $k=>$v){
    		if($v['remainNum'] <= 0 && $v['odds'] > 0 && $v['dateNum'] != -1){
    			$prizes[$k]['odds']=0;
    			$tprizes=D('prize')->getList('id',array('dateNum'=>array('eq',-1),'odds'=>array('neq',0), 'cinemaCode' => $cinemaCode),0,1,'priority desc');
    			$prizes[$tprizes[0]['id']]['odds']+=$v['odds'];
    			unset($tprizes);
    			unset($prizes[$k]);
    		}
    	}
    	/***结束获取概率****/
    	
    	/**开始抽奖****/
    	$random=rand(0,10000);

    	$sodds=0;
    	$logarr=array(
    			'time'=>time(),
    			'uid'=>$map['uid'],
    	);


    	foreach ($prizes as $k=>$v){
    		if($random >= $sodds && $random < $sodds + $v['odds']){

    			if($v['type']=='0'){
    				//发券代码
    				vendor('Hprose.HproseHttpClient');
        			$client = new \HproseHttpClient(C('VOUCHER_URL'));
        			$voucherInfo = $client->getVoucherNum($v['voucherType'], 1, $v['startTime'], $v['endTime']);


        			$voucherNum = $voucherInfo['data'][0];
        			$result = $client->appCheckVoucher($voucherNum, 0);
		            $voucherType = 'convertVolume';
		            $voucherTypeId = 0;
		            if(!isset($result['status']) || $result['status'] != 0){
		                $result = $client->appCheckVoucher($voucherNum, 1);
		                $voucherType = 'orderVolume';
		            	$voucherTypeId = 1;
		                 if(!isset($result['status']) || $result['status'] != 0){
		                    $result = $client->appCheckVoucher($voucherNum, 2);
		                    $voucherType = 'saleVolume';
		            		$voucherTypeId = 2;
		                 }
		            }


        			$addData['voucherType'] = $voucherType;
			        $addData['accountId'] = $map['uid'];
			        $addData['voucherNum'] = $result['data']['voucher_number'];
			        $addData['voucherName'] = $result['data']['type_name'];
			        $addData['voucherValue'] = $result['data']['type_value'];
			        $addData['validData'] = date('Y-m-d', $result['data']['end_time']);
			        $addData['createdDatetime'] = date('Y-m-d H:i:s');
			        $addData['isUse'] = 0;
			        $addData['usePackNo'] = '';
			        $addData['typeId'] = $result['data']['type_id'];

			        // wlog('开始增加卖品券----------' . json_encode($addData), 'testLog');
			        $addResult = D('Prize')->addAccountCardPackage($addData);


    			}

    			M('prizeZr')->where(array('id'=>$v['id']))->setInc('remainNum',-1); //扣除物品数量
    			M('prizeZr')->where(array('id'=>$v['id']))->setInc('allRemainNum',1); //扣除物品数量
    			$logarr['prizeId']=$v['id'];
    			$logarr['prizeName']=$v['name'];
    			if ($voucherNum) {
    				$logarr['value'] = $voucherNum;
    			}
    			$logarr['note']=$v['note'];
    			$logId=D('prize')->addLog($logarr);
    			$logarr['voucherTypeId'] = $voucherTypeId;
    			// print_r($addData);
    			$this->drawresult($v,$logarr, $logId, $addData);
    			die();
    			//$this->success('获得奖品：'.$v['name'],array('id'=>$v['id']));
    		}
    		$sodds+=$v['odds'];
    	}
    	$logarr['prizeStatus']=1;
    	D('prize')->addLog($logarr);
    	$this->display('noprize');
	}
	
	
	public function drawresult($prize,$logarr, $logId, $addData){

		$this->assign('prize',$prize);
		// print_r($addData);
		if($prize['type']=='0'){  //票券逻辑
			// $typeClass=M('voucherType')->field('typeClass,typeValue')->find($prize['voucherType']);
			$this->assign('logarr',$logarr);

			if ($logarr['voucherTypeId'] == 0) {
				$addData['voucherName'] = '兑换券';
			}

			if ($logarr['voucherTypeId'] == 1) {
				$addData['voucherName'] = '立减券';
			}

			if ($logarr['voucherTypeId'] == 2) {
				$addData['voucherName'] = '卖品券';
			}

			$this->assign('addData',$addData);
			$display='drawresult'.$logarr['voucherTypeId'];
		}else{  //物品逻辑
			$display='experiencevoucher';
			$this->assign('logId',$logId);
		}
		$this->display($display);
	}

	public function evinfo (){
		if(IS_AJAX){
			$logarr['id']=I('logId');
			$mobile=I('mobile');
			if(!preg_match('/^1\d{10}$/', $mobile)){
				$this->error('手机格式不正确');
			}

			$logInfo = D('prize')->getLog('', array('id' => $logarr['id']));
			if ($logInfo['mobile']) {
				$this->success('您的短信已发送成功，请查收您的信息！');
			}

			$prizeInfo = D('prize')->getPrize('', array('id' => $logInfo['prizeId']));
			// print_r($prizeInfo);
			//开始发送短信
			
			// echo '111';
			$smsConfig['smsType'] = 'ihyi';
			$smsConfig['smsAccount'] = 'cf_zrgjys';
			$smsConfig['smsPassword'] = 'APgWjP';

			$sms = new \Think\SmsModel($smsConfig);
        	$smsResult = $sms->sendSms($mobile, $prizeInfo['smsTemp']);
   
			//成功
			$logarr['mobile']=$mobile;
			if(D('prize')->saveLog($logarr)){
				$this->success('发送成功');
			}
		}else{
			$this->time=3;
			$map['uid']=cookie('uid');
			$map['_string']='FROM_UNIXTIME(time,"%Y%m%d")='.date('Ymd');
			$clog=D('prize')->getLog('count(id) as ci',$map);
			if($clog['ci']>=$this->time){
				// $this->display('nonumprize');
			}
			$this->hastime=$this->time-$clog['ci'];
			$prize=D('prize')->getPrize('',array('id'=>I('id')));
			$this->assign('prize',$prize);
			$this->display();
		}
		
        
	}

}