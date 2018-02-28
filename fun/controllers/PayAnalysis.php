<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 统计-付费分析
* ==============================================
* @date: 2016-3-2
* @author: luoxue
* @version:
*/
include 'MY_Controller.php';
ini_set('memory_limit', '1024M');
include APPPATH.'config/game_config.php';

class PayAnalysis extends MY_Controller {
	protected $viewFile = 'PayAnalysis/';
	protected $bt;
	protected $et;
	protected $serverId;
	protected $channelId;
	
	public function __construct() {
		parent::__construct();
		///$this->data['web_channel_list'] = include APPPATH.'/config/game_config.php';
		
		$this->bt = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-6 days'));
		$this->et = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
		$this->serverId = $this->input->get('server_id', true);
		$this->channelId = $this->input->get('channel_id', true);
		
		
		$this->data['bt'] = $this->bt;
		$this->data['et'] = $this->et;
	}
	//===============付费排行====================
	public function PayRank(){
		$this->data['hide_server_list'] = true;
		$this->body = $this->viewFile.'rank';
		$this->layout();
	}
	/**
	 * 付费平均值
	 *
	 * @author 王涛 --20170330
	 */
	public function Payavg(){
		if (parent::isAjax()) {
			$date = $this->input->get('date1');
			$date2 = $this->input->get('date2');
			$reg1 = $this->input->get('reg1');
			$reg2 = $this->input->get('reg2');
			$channel = $this->input->get('channel_id');
			if(!$channel){
				$channel = array_keys($this->data['channel_list']);
			}
			/*if(count($channel)!=1){
				echo json_encode(array('status'=>'fail','info'=>'必须并且只能选择一个渠道'));die;
			}*/
			if(!$reg1 || !$reg2 || !$date|| !$date2){
				echo json_encode(array('status'=>'fail','info'=>'时间区间必须有数值'));die;
			}
			$this->load->model('Register_model');
			//$this->load->model('Paylog_model');
			$where['begintime'] = strtotime($reg1  . ' 00:00:00');
			$where['endtime'] = strtotime($reg2 . ' 23:59:59');
			$where['channels'] = $channel;
			$accounts = $this->Register_model->register_account_new($where,'channel,count(*) t','channel');
			$newaccounts = $newpays = $newavgs = [];
			foreach ($accounts as $v){
				$newaccounts[$v['channel']] = $v['t'];
			}
			$_day_index = array_keys($newaccounts);
			$diff = array_diff( $channel, $_day_index);
			if ($diff) {
				foreach($diff as $_diff_day) {
					$newaccounts[$_diff_day] = 0;
				}
			}
			$where['paybegintime'] = strtotime($date  . ' 00:00:00');
			$where['payendtime'] = strtotime($date2 . ' 23:59:59');
			$pays = $this->Register_model->register_pay_data($where);
			foreach ($pays as $v){
				$newpays[$v['channel']] = $v['s'];
			}
			$_day_index = array_keys($newpays);
			$diff = array_diff( $channel, $_day_index);
			if ($diff) {
				foreach($diff as $_diff_day) {
					$newpays[$_diff_day] = 0;
				}
			}
			ksort($newpays);
			ksort($newaccounts);
			foreach ($newaccounts as $k=>$v){
				$newavgs[$k] = $v==0?0:round($newpays[$k]/$v,2);
			}
			$legend['data'][] = '注册人数';
			$legend['data'][] = '充值总额';
			$legend['data'][] = '平均充值金额';
			$json_data[] = [
					'name' => '注册人数',
					'type' => 'bar',
					'data'  => array_values($newaccounts),
			];
			$json_data[] = [
					'name' => '充值总额',
					'type' => 'bar',
					'data'  => array_values($newpays),
			];
			$json_data[] = [
					'name' => '平均充值金额',
					'type' => 'bar',
					'data'  => array_values($newavgs),
			];
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
					'status'=> 'ok' ,
					'data'=>array(
						'accounts'  => $newaccounts,
						'pays'=>$newpays,
						'avgs'=>$newavgs,
					),
					'series'     =>$json_data,
					'legend'     =>$legend,
					'category'=>array_keys($newpays),
			])
			);
		}
		else {
			$this->data ['bt'] = $this->data ['et'] = '';
			$this->data ['hide_server_list'] = true;
			$this->data ['register_time'] = true;
			$this->body = $this->viewFile.'avg';
			$this->layout();
		}
	}
	/**
	 * 付费等级分布
	 * 
	 * @author 王涛 --20170208
	 */
	public function PayLevel(){
		if (parent::isAjax()) {
			$field = 'is_new,sum(if(lev<=10,1,0)) level_0,sum(if(lev>10&&lev<=20,1,0)) level_1,sum(if(lev>20&&lev<=30,1,0)) level_2,sum(if(lev>30&&lev<=40,1,0)) level_3
					,sum(if(lev>40&&lev<=50,1,0)) level_4,sum(if(lev>50&&lev<=60,1,0)) level_5,sum(if(lev>60&&lev<=70,1,0)) level_6,sum(if(lev>70&&lev<=80,1,0)) level_7
					,sum(if(lev>80&&lev<=90,1,0)) level_8,sum(if(lev>90&&lev<=100,1,0)) level_9';
    		$date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
    		$date2 = $this->input->get('date2') ?$this->input->get('date2', true) : date('Y-m-d');
    		$where['serverids'] = $this->input->get('server_id');
    		$where['channels'] = $this->input->get('channel_id');
    		$where['accountid'] = $this->input->get('accountid');
    		$group = 'is_new';
    		$order = 'is_new desc';
    		$where['begintime'] = strtotime($date  . ' 00:00:00');
    		$where['endtime'] = strtotime($date2 . ' 23:59:59');//只统计当日的数据
    		$this->load->model('Paylog_model');
    		$data = $this->Paylog_model->getPayLevelData($where,$field,$group,$order);
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode([
    				'status'=> 'ok' ,
    				'data'  => $data
    		])
    		);
		}
		else {
			$this->data['account_id_filter'] = true;
			$this->body = $this->viewFile.'level';
			$this->layout();
		}
	}
	
	public function getActionPayLogRank(){
		$bt = strtotime($this->bt.' 00:00:00');
		$et = strtotime($this->et.' 23:59:59');
		$serverId = $this->serverId ;
		$channelId = $this->channelId;
		
		$this->load->model('Paylog_model');
		$this->Paylog_model->init($this->appid, $bt, $et);
		
		$this->getRankData($this->Paylog_model, $serverId, $channelId);
	}
	
	private function getRankData($model, $serverId, $channelId){
		$json_data = $outputData = $legend = $xAxis = [];
		//获取激活数据和获取新增数据
		$bt = strtotime(date('Y-m-d', strtotime($this->bt)));
		$et = strtotime(date('Y-m-d', strtotime($this->et))); 

		$this->load->model('player_analysis_model');
		
		$tableData = array();

		while ($bt){
			if ($et == $bt){
				//传同一天
				$date1 = $date2 = date('Ymd', $bt);
				//$registerData = $this->player_analysis_model->getRegisterData($this->appid, $date1, $date2, $serverId, $channelId);
				$payData = $model->getPayAccountData($this->appid, $date1, $date2, $serverId, $channelId);
				$arr = array();			
				//$round = $registerData['dayTotal']  ? round($payData['dayCount']/$registerData['dayTotal'], 6)*100 : '--';
				$arr['day'] =  $payData['dayCount'].'(--)';
				//$round = $registerData['weekTotal'] ? round($payData['weekCount']/$registerData['weekTotal'], 6)*100 : '--';
				$arr['week'] =  $payData['weekCount'].'(--)';
				//$round = $registerData['monthTotal'] ? round($payData['monthCount']/$registerData['monthTotal'], 6)*100 : '--';
				$arr['month'] =  $payData['monthCount'].'(--)';
			
				$tableData[date('Y-m-d', $bt)] = $arr;
				break;
			}
			//传同一天
			$date1 = $date2 = date('Ymd', $bt);
			$registerData = $this->player_analysis_model->getRegisterData($this->appid, $date1, $date2, $serverId, $channelId);
			$payData = $model->getPayAccountData($this->appid, $date1, $date2, $serverId, $channelId);
			
			$arr = array();	
			$round = $registerData['dayTotal']  ? round($payData['dayCount']/$registerData['dayTotal'], 6)*100 : '--';
			$arr['day'] =  $payData['dayCount'].'('.$round.'%)';
			$round = $registerData['weekTotal'] ? round($payData['weekCount']/$registerData['weekTotal'], 6)*100 : '--';
			$arr['week'] =  $payData['weekCount'].'('.$round.'%)';
			$round = $registerData['monthTotal'] ? round($payData['monthCount']/$registerData['monthTotal'], 6)*100 : '--';
			$arr['month'] =  $payData['monthCount'].'('.$round.'%)';
			$tableData[date('Y-m-d', $bt)] = $arr;

			$bt += 24*60*60;
		
		}

		$this->output->set_content_type('application/json')->set_output(json_encode([
				'status' => 'ok',
				'xAxis' => $xAxis,
				'series' => $json_data,
				'legend' => $legend,
				'data' 	=> $tableData,
				])
			);	
	}
	
	//===============付费行为====================
	public function PayBehavior() {
		
		$this->body = $this->viewFile.'behavior';
		$this->layout();
	}
	
	public function getActionPayLogBehavior() {
		$bt = strtotime($this->bt.' 00:00:00');
		$et = strtotime($this->et.' 23:59:59');

		$serverId = $this->serverId ;
		$channelId = $this->channelId;
		$this->load->model('Paylog_model');
		$this->Paylog_model->init($this->appid, $bt, $et);
		$this->getBehaviorData($this->Paylog_model, $serverId, $channelId);
	}
	
	private function getBehaviorData($model, $serverId, $channelId){
		$json_data = $outputData = $legend = $xAxis = [];
		//获取激活数据和获取新增数据
		$areaPrice  = array('1~6', '7~10', '11~50', '51~100', '101~500', '501~1000', '1001');
		$tableData = array();
        $dayData = $model->getDayData($serverId, $channelId);   
        $dayAcountArr = array();
        foreach ($dayData as $v){   	
        	for ($i = 0; $i < count($areaPrice); $i++){
				$nk = $areaPrice[$i];
				$v1_v2 = explode('~', $nk);
				if(count($v1_v2) == 1){
					if($v['money'] >= $v1_v2[0]){
						//$tableData[$nk]['daymoney'] += $v['money'];
                        if(!isset($dayAcountArr[$v['accountid']]))
							$tableData[$nk]['daycount'] += 1;
					}
				}else {
					if($v['money'] >= $v1_v2[0] && $v['money'] <=$v1_v2[1] ){
						//$tableData[$nk]['daymoney'] += $v['money'];
                        if(!isset($dayAcountArr[$v['accountid']]))
							$tableData[$nk]['daycount'] += 1;
					}
				}
			}
			//填充accountid
            $dayAcountArr[$v['accountid']] = $v['accountid'];
        }
        unset($dayAcountArr);
        $weekData = $model->getWeekData($serverId, $channelId);
        $weekAcountArr = array();
        foreach ($weekData as $v){
        	for ($i = 0; $i < count($areaPrice); $i++){
        		$nk = $areaPrice[$i];
        		$v1_v2 = explode('~', $nk);
        		if(count($v1_v2) == 1){
        			if($v['money'] >= $v1_v2[0]){
        				//$tableData[$nk]['daymoney'] += $v['money'];
        				if(!isset($weekAcountArr[$v['accountid']]))
        					$tableData[$nk]['weekcount'] += 1;
        			}
        		}else {
        			if($v['money'] >= $v1_v2[0] && $v['money'] <=$v1_v2[1] ){
        				//$tableData[$nk]['daymoney'] += $v['money'];
        				if(!isset($weekAcountArr[$v['accountid']]))
        					$tableData[$nk]['weekcount'] += 1;
        			}
        		}
        	}
        	//填充accountid
            $weekAcountArr[$v['accountid']] = $v['accountid'];
        }
        unset($weekAcountArr);
        $monthData = $model->getMonthData($serverId, $channelId);
        $monthAcountArr = array();
        foreach ($monthData as $v){
        	for ($i = 0; $i < count($areaPrice); $i++){
        		$nk = $areaPrice[$i];
        		$v1_v2 = explode('~', $nk);
        		if(count($v1_v2) == 1){
        			if($v['money'] >= $v1_v2[0]){
        				//$tableData[$nk]['daymoney'] += $v['money'];
                        if(!isset($monthAcountArr[$v['accountid']]))
        					$tableData[$nk]['monthcount'] += 1;
        			}
        		}else {
        			if($v['money'] >= $v1_v2[0] && $v['money'] <=$v1_v2[1] ){
        				//$tableData[$nk]['daymoney'] += $v['money'];
                        if(!isset($monthAcountArr[$v['accountid']]))
        					$tableData[$nk]['monthcount'] += 1;
        			}
        		}
        	}
        	//填充accountid
            $monthAcountArr[$v['accountid']] = $v['accountid'];
        }
         unset($monthAcountArr);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode([
				'status' => 'ok',
				'xAxis' => $xAxis,
				'series' => $json_data,
				'legend' => $legend,
				'data' 	=> $tableData,
				])
		);
	}
	//===============付费数据====================
	public function PayData(){
			
		$this->body = $this->viewFile.'data';
		$this->layout();
	}
	public function getActivePaylogData() {	
		$bt = strtotime($this->bt.' 00:00:00');
		$et = strtotime($this->et.' 23:59:59');
		$serverId = $this->serverId ;
	//	$channelId = $this->channelId;
		$channelId =  $this->input->get ( 'channel_id' );
		
	
		
		
		$this->load->model('Paylog_model');
		$this->Paylog_model->init($this->appid, $bt, $et);
		$this->getData($this->Paylog_model, $serverId, $channelId);
	}
	private function getData($model, $serverId, $channelId) {
        $json_data = $outputData = $legend = $xAxis = [];
    
        //获取激活数据和获取新增数据
        $bt = strtotime($this->bt);
        $et = strtotime($this->et);
         /*
          * 下面的数据分析 不适合用于s数据量太大
          */ 
        //$data = $model->get_list($serverId, $channelId);
        $tableData = $model->getData($serverId, $channelId);
        
        $this->load->model('player_analysis_model');
        $date1 = date('Ymd', $bt);
        $date2 = date('Ymd', $et);
        $where['group']='serverid';
        $data2 = $this->player_analysis_model->getActiveData($this->appid, $date1, $date2, $serverId, $channelId);
        
        //活跃数根据serverid变化
        $whereActive['serverids']=$serverId;
        $whereActive['channels']=$channelId;
        $whereActive['sday']=$channelId;
        $whereActive['date1']=$date1;
        $whereActive['date2']=$date2;
        $groupActive='sday';
        $fieldActive = "sday,serverid,SUM(dau) AS dau";
        $data3 = $this->player_analysis_model->getActiveDataSday($table, $whereActive, $field, $groupActive, $order, $limit);  
 
        $outputData = array();
        foreach ($data2 as $v) {
        	$sday = date('Y-m-d', strtotime($v['sday']));
        	$outputData[$sday]['dau'] = $v['dau'];      	

        }        
    
        $this->load->model('Mydb_sum_model');
        $field = 'date,sum(dau) as dau';
        $group='date';
        $where['begindate']= $date1 ;
        $where['enddate']= $date2;
        $where['channels'] = $channelId;
        $output = $this->Mydb_sum_model->summarybychannel($where,$field,$group,'date');
        

        
        foreach ($tableData as $k => $v){
        	$dauSum = $outputData[$v['day']]['dau'];        	
         	$day=date("Ymd",strtotime($v['day']));
        	
       	foreach ($output as $k2=>$v2){   
       	
        		if($day==$v2['date']){
        			$tableData[$k]['dau']=$v2['dau'];
        		}
        	}
        	if($tableData[$k]['countAccountid']==0  || $tableData[$k]['dau']==0 ){
        		$tableData[$k]['payRate']=0;
        	} else {
        	    if($tableData[$k]['countAccountid']!=0 || $tableData[$k]['dau'] !=0){
        	        
        	    $tableData[$k]['payRate']=$tableData[$k]['countAccountid']?round($tableData[$k]['countAccountid']/$tableData[$k]['dau'],4)*100:0;
        	    
        	    } else {
        	        
        	        $tableData[$k]['payRate']=0;
        	    }
        	}
        	$tableData[$k]['arppu'] = round($v['allmoney']/$v['countAccountid'], 2);
        //	$tableData[$k]['arpu'] = ($dauSum > 0) ? round($v['allmoney']/$dauSum, 2) : '--';
        if($v['allmoney']==0 || $tableData[$k]['dau']==0){
        	$tableData[$k]['arpu']=0;
        } else {
        	$tableData[$k]['arpu'] = round($v['allmoney']/$tableData[$k]['dau'],2);
        }
        	
        	$tableData[$k]['text'] =" <a href='javascript:serverDistribute($day,0)'>服务器分布</a> ";
        } 
        
        if($serverId || $channelId){           
            foreach ($tableData as $k=>$v){            
                foreach ($data3 as $v3) {
                    $sday = date('Y-m-d', strtotime($v3['sday']));            
                    if($v['day']==$sday){            
                        if($v3['dau']==0){
                            $tableData[$k]['payRate']=0;
                        }else {
                            $tableData[$k]['payRate']=$tableData[$k]['countAccountid']?round($tableData[$k]['countAccountid']/$v3['dau'],4)*100:0;
                                $tableData[$k]['dau']=$v3['dau'];
                        }
         
                     
                    }
                }
            
            }
            
       } 
       
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
            		'status' => 'ok',
                	'xAxis' => $xAxis,
                	'series' => $json_data,
                	'legend' => $legend,
            		'data' 	=> $tableData,
                ])
            );
	}
	
	

	/*
	 * 活跃玩家充值积分统计  zzl 20170907
	 */	
	public function bonusPoint(){
	    if (parent::isAjax ()) {
	        $date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
	        $date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
	        $date3 = $this->input->get ( 'date3' ) ? $this->input->get ( 'date3', true ) : '';
	        $date4 = $this->input->get ( 'date4' ) ? $this->input->get ( 'date4', true ) : '';
	        $where ['Ym']=date ( 'Ym', strtotime ( $date ) );
	        $where ['date'] = date ( 'Ymd', strtotime ( $date ) );
	        $where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
	        $where ['enddate'] = date ( 'Ymd', strtotime ( $date2 ) );
	        $where ['serverids'] = $this->input->get ( 'server_id' );
	        $where ['channels'] = $this->input->get ( 'channel_id' );
	        $where ['type_id'] = $this->input->get ( 'type_id' );
	    
	        $this->load->model ( 'Data_analysis_model' );
	        $logininfo = $this->Data_analysis_model->viplogin ( $where );
	    
	        $table = '';
	        $group="vip_level";
	        $field="id,vip_level,sum(recharge_mark) as surplus_point";	        

	        $this->load->model ( 'Pay_analysis_model' );
	        $data1 = $this->Pay_analysis_model->bonusPoint($table, $where,$field,$group);  
	        
	        
	      $field="vip_level,sum(if(type=1,item_num,0)) as consume_point,sum(if(type=0,item_num,0)) as get_point";	      
	      $this->load->model ( 'Player_analysis_model' );
	      $data2 = $this->Player_analysis_model->bonusPoint ( $table, $where,$field,$group,$order, $limit);	
	
	        foreach ( $logininfo ['day0'] as $k => &$v ) {
	            $v['get_point']=0;
	            $v ['consume_point']=0;
	            $v ['surplus_point'] = 0;
	            
	            $v['text']=	" <a href='javascript:bonusDistribution({$v['viplev']},1)'>积分消耗分布</a> ";
	          
	            foreach ( $data1 as $v2 ) {
	                if ($v ['viplev'] == $v2 ['vip_level']) {
	                    $v ['active'] = $v ['c'] ? $v ['c'] : 0;	       
	                    $v ['surplus_point'] = $v2 ['surplus_point']? $v2 ['surplus_point'] : 0;
	                   
	                }
	            }
	            
	            foreach ( $data2 as $v3 ) {
	                if ($v ['viplev'] == $v3 ['vip_level']) {	               
	                    $v ['get_point'] =  $v3 ['get_point'] ?  $v3 ['get_point']  : 0;
	                    $v ['consume_point'] =  $v3 ['consume_point']?  $v3 ['consume_point'] : 0;          
	                 
	                     
	                }
	            }
	             
	
	    
	        }
	    
	        if (! empty ( $logininfo ['day0'] ))
	            echo json_encode ( [
	                'status' => 'ok',
	                'data' => $logininfo ['day0']
	            ] );
	            else
	                echo json_encode ( [
	                    'status' => 'fail',
	                    'info' => '未查到数据'
	                ] );
	    } else {
	        $this->data ['type_list'] = $types;
	        $this->data ['hide_end_time'] = true;
	        $this->data ['hide_channel_list'] = true;
	        
	        $this->body = $this->viewFile.'bonusPoint';
	        $this->layout ();
	    }
	    
	}

	/**
	 * 充值档位数据 chenyanbin 2017/11/20
	 */
	public function GearPosition(){
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );

			$begindate =strtotime ( $date ) ;
			$enddate = strtotime ( $date2 );

			$this->load->model ( 'Pay_analysis_model' );
			$data = $this->Pay_analysis_model->getGearPosition($begindate, $enddate);
			if (empty($data)) {
				echo json_encode ( [
					'status' => 'fail',
					'info' => '未查到数据'
				] );
			}

			foreach ( $data as $k => &$v ) {
				$res[$v['money']][] = $v;
			}


			foreach ( $data as $k => &$v ) {
				$ress[$v['date']][] = $v;
			}

//
//			var_dump($list);exit;
			//$res = array_values($res);
			//var_dump($res);exit;

			$l = [
				'res'=> $res,
				'ress' => $ress
			];
			if (! empty ($res))
				echo json_encode ( [
					'status' => 'ok',
					'data' => $l
				] );
			else
				echo json_encode ( [
					'status' => 'fail',
					'info' => '未查到数据'
				] );
		} else {
			$this->data ['type_list'] = $types;
			$this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;
//
			$this->body = $this->viewFile.'gearPosition';
			$this->layout ();
		}

	}

	/**
	 * 首冲数据统计 chenyanbin 2017/11/20
	 */
	public function FirstRecord(){
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );

			$begindate =strtotime ( $date ) ;
			$enddate = strtotime ( $date2 );

			$this->load->model ( 'Pay_analysis_model' );
			$data = $this->Pay_analysis_model->getFirstRecord($begindate, $enddate);

			if (empty($data)) {
				echo json_encode ( [
					'status' => 'fail',
					'info' => '未查到数据'
				] );
			}

			if (! empty ($data)) {
                foreach ($data as $key => $val) {
                    $data [$key] ['deng'] .= "<a class='xi' time='{$val['date']}'>等级分布</a>";
                }
                echo json_encode([
                    'status' => 'ok',
                    'data' => $data
                ]);
            }
			else {
                echo json_encode([
                    'status' => 'fail',
                    'info' => '未查到数据'
                ]);
            }
		} else {
			$this->data ['type_list'] = $types;
			$this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;
//
			$this->body = $this->viewFile.'firstRecords';
			$this->layout ();
		}

	}

	/**
	 * 新增账号付费 chenyanbin 2017/11/21
	 */
	public function PayNewAccounts(){
		if (parent::isAjax ()) {
			$date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
			$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );

			$begin = str_replace("-","",$date);
			$end = str_replace("-","",$date2);

			$begindate =strtotime ( $date.' 00:00:00' ) ;
			$enddate = strtotime ( $date2.' 23:59:59' );
			$this->load->model ( 'Pay_analysis_model' );
			$data = $this->Pay_analysis_model->getPayNew($begin, $end);
			$total = $this->Pay_analysis_model->getPayNewTotal($begindate, $enddate);

			if (empty($data)) {

				echo json_encode ( [
					'status' => 'fail',
					'info' => '未查到数据'
				] );
			}

			if (! empty ($data)) {
				foreach($total as $key => $val) {
					$res[$val['date']] = $val;
				}

				foreach($data as $key => &$val) {
					$val['total'] = $res[$val['date']]['total'];
				}

                echo json_encode([
                    'status' => 'ok',
                    'data' => $data
                ]);
            }
			else {
                echo json_encode([
                    'status' => 'fail',
                    'info' => '未查到数据'
                ]);
            }
		} else {
			$this->data ['type_list'] = $types;
			$this->data ['hide_server_list'] = true;
			$this->data ['hide_channel_list'] = true;
//
			$this->body = $this->viewFile.'payNewAccount';
			$this->layout ();
		}

	}
	/*
	 * 付费分析-任务栏扩容情况统计  zzl 20171211
	 */
	
	public  function dilatation(){
	    
	    if (parent::isAjax ()) {
	    $date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
	    $date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
	    $where['serverids'] = $this->input->get('server_id');
	    $where['typeids'] = $this->input->get('type_id');
	    $where ['channels'] = $this->input->get ( 'channel_id' );	    
	   
	    
	    $where ['date'] =date("Ymd",strtotime($date));
	    $where ['date2'] =date("Ymd",strtotime($date2));
	    
	    $begindate =strtotime ( $date ) ;
	    $enddate = strtotime ( $date2 );
	    $group = "vip_level";
	    $field = "vip_level,accountid,count(DISTINCT accountid) cnt,adventure_max,count(if(adventure_max=4,true,null)) m4,count(if(adventure_max=5,true,null)) m5,count(if(adventure_max=6,true,null)) m6,count(if(adventure_max=7,true,null)) m7,count(if(adventure_max=8,true,null)) m8";
	    $order = "vip_level desc";
	    $this->load->model ( 'Pay_analysis_model' );
	    $data = $this->Pay_analysis_model->dilatation($table, $where, $field, $group, $order, $limit);
	    

	    
	    if (! empty ($data)) {
	 
	        echo json_encode([
	            'status' => 'ok',
	            'data' => $data
	        ]);
	    }
	    else {
	        echo json_encode([
	            'status' => 'fail',
	            'info' => '未查到数据'
	        ]);
	    }
	    
	    } else {
    
            $this->data['page_title'] = "付费分析-任务栏扩容情况统计 ";
           // $this->data ['hide_end_time'] = true;
            $this->body = 'PayAnalysis/dilatation';
            $this->layout();
        }
	    
	}
	
	
	public  function refresh(){
	     
	    if (parent::isAjax ()) {
	        $date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
	        $date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
	        $where['serverids'] = $this->input->get('server_id');
	        $where['typeids'] = $this->input->get('type_id');
	        $where ['channels'] = $this->input->get ( 'channel_id' );
	
	         
	        $where ['date'] =date("Ymd",strtotime($date));
	        $where ['date2'] =date("Ymd",strtotime($date2));
	         
	        $begindate =strtotime ( $date ) ;
	        $enddate = strtotime ( $date2 );
	        $group = "vip_level";
	        $field = "u.vip_level,count(DISTINCT u.accountid) cnt,count(if(u.param=2,true,null)) total";
	        $order = "";
	        $this->load->model ( 'Pay_analysis_model' );
	        $data = $this->Pay_analysis_model->refresh($table, $where, $field, $group, $order, $limit);
	        
	     
	        
	        foreach ($data as $k=>$v){
	            
	            foreach ($data['more'] as $v2){
	                
	                if($v['vip_level']==$v2['vip_level']){
	                   
	                    
	                    if($v2['cnt']==1){
	                        $data[$k]['r1']+=1;
	                        $v['r1']+=1;
	                    }
	                    if($v2['cnt']==2){
	                        $data[$k]['r2']+=1;
	                        $v['r2']+=1;
	                    }
	                    if($v2['cnt']==3){
	                        $data[$k]['r3']+=1;
	                        $v['r3']+=1;
	                    }
	                    if($v2['cnt']==4){
	                        $data[$k]['r4']+=1;
	                        $v['r4']+=1;
	                    }
	                    if($v2['cnt']==5){
	                        $data[$k]['r5']+=1;
	                        $v['r5']+=1;
	                    }
	                     
	                    if($v2['cnt']==6){
	                        $data[$k]['r6']+=1;
	                        $v['r6']+=1;
	                    }
	                    if($v2['cnt']==7){
	                        $data[$k]['r7']+=1;
	                        $v['r7']+=1;
	                    }	                    
	                }	                
	        
	            }
	          
	         } 
	         
	         
	        unset($data['more']);	        
	        foreach ($data as &$v){	            
	            if(empty($v['r1'])){
	                $v['r1']=0;          
	                
	            }
	            if(empty($v['r2'])){
	                $v['r2']=0;
	             }
	             if(empty($v['r3'])){
	                 $v['r3']=0;
	             }
	             if(empty($v['r4'])){
	                 $v['r4']=0;
	             }
	             if(empty($v['r5'])){
	                 $v['r5']=0;
	             }
	             if(empty($v['r6'])){
	                 $v['r6']=0;
	             }
	             if(empty($v['r7'])){
	                 $v['r7']=0;
	             }
	        }
	         
	         
	        if (! empty ($data)) {
	
	            echo json_encode([
	                'status' => 'ok',
	                'data' => $data
	            ]);
	        }
	        else {
	            echo json_encode([
	                'status' => 'fail',
	                'info' => '未查到数据'
	            ]);
	        }
	         
	    } else {
	
	        $this->data['page_title'] = "付费分析->刷新付费统计 ";
	         $this->data ['hide_end_time'] = true;
	        $this->body = 'PayAnalysis/refresh';
	        $this->layout();
	    }
	     
	}

	/**
	 * cli模式运行
	 *
	 * php /var/www/ci/index.php RealTime run
	 */
	public function run(){
		$data = $this->db->query('SELECT appid FROM auth_config')->result_array();
		foreach ($data as $_d) {
			$this->OnlineCal($_d['appid']);
			$this->DeviceCal($_d['appid']);
			usleep(500);
		}
	}
	
	
	

	
}