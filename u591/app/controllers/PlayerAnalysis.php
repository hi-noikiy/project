<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 * 实时数据统计
 */
include_once 'MY_Controller.php';
class PlayerAnalysis extends MY_Controller {
    /**
     * @var $player_analysis_model Player_analysis_model
     */
    public $player_analysis_model;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 合服流失统计
     *
     * @author 王涛 20170428
     */
    public function Lost()
    {
    	if (parent::isAjax()) {
    		$date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
    		$where['date'] = date('Ymd',strtotime($date));
    		$where['serverids'] = $this->input->get('server_id');
    		$this->load->model ( 'Sdk_sum_model' );
    		$data = $this->Sdk_sum_model->loginlost($where);
    		$data['rare'] = $data['before']?round(($data['before']-$data['after'])/$data['before']*100,2):0;
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
    		else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
    	}else{
    		$this->data['bt'] = '';
    		$this->body = 'PlayerAnalysis/lost';
    		$this->layout();
    	}
    }
    /**
     * Vip登录详情
     *
     * @author 王涛 20170406
     */
    public function VipLogin()
    {
    	if (parent::isAjax()) {
    		$date = $this->input->get('date1') ?$this->input->get('date1', true) : date('Y-m-d');
    		$where['date'] = date('Ymd',strtotime($date));
    		$where['serverids'] = $this->input->get('server_id');
    		$where['channels'] = $this->input->get('channel_id');
    		$this->load->model ( 'Sdk_sum_model' );
    		$logininfo = $this->Sdk_sum_model->viplogin($where);
    		$data= array();
    		foreach ($logininfo['day0'] as $v){
    			$data[$v['viplev']]['day0'] = $v['c'];
    		}
    		foreach ($logininfo['day1'] as $v){
    			$data[$v['viplev']]['day1'] = $v['c'];
    		}
    		foreach ($logininfo['day3'] as $v){
    			$data[$v['viplev']]['day3'] = $v['c'];
    		}
    		foreach ($logininfo['day7'] as $v){
    			$data[$v['viplev']]['day7'] = $v['c'];
    		}
    		foreach ($data as $k=>&$v){
    			$v['day0'] = isset($v['day0'])?$v['day0']:0;
    			$v['day1'] = isset($v['day1'])?$v['day1']:0;
    			$v['day3'] = isset($v['day3'])?$v['day3']:0;
    			$v['day7'] = isset($v['day7'])?$v['day7']:0;
    			$v['rare1'] = ($v['day0']>0?round($v['day1']/$v['day0'],2)*100:0).'%';
    			$v['rare3'] = ($v['day0']>0?round($v['day3']/$v['day0'],2)*100:0).'%';
    			$v['rare7'] = ($v['day0']>0?round($v['day7']/$v['day0'],2)*100:0).'%';
    		}
    		ksort($data);
    		if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
    		else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
    	}else{
    		$this->data['hide_end_time'] = true;
    		$this->body = 'PlayerAnalysis/viplogin';
    		$this->layout();
    	}
    }
    /**
     * 用户信息查询
     *
     * @author 王涛 20170123
     */
    public function user()
    {
    	if (parent::isAjax()) {
    		$where['serverids'] = $this->input->get('server_id');
    		$where['userid'] = $this->input->get('userid');
    		$where['username'] = $this->input->get('username');
    		$where['accountid'] = $this->input->get('accountid');
    	/* 	if(count($where['serverids']) != 1){
    			echo json_encode(['status'=>'fail','info'=>'请选择一个区服']);die;
    		} */
    		if($where['username']){
    			unset($where['userid'],$where['accountid']);
    		}elseif($where['userid']){
    			unset($where['accountid']);
    		}elseif(!$where['accountid']){
    			echo json_encode(['status'=>'fail','info'=>'请选择一个查询条件']);die;
    		}
    		$field = "l.serverid,username,l.accountid,userid,lev,viplev,l.channel,last_login_time lasttime,last_login_ip lip,l.client_type,last_login_mac mac,r.created_at registertime,r.ip rip";
    		$group = "userid";
    		$this->loadModel();
    		$userinfo = $this->player_analysis_model->getUserinfo($where,$field,$group);
    		foreach ($userinfo as $k=>$v){
    			$userinfo[$k]['servername'] = $this->data['server_list'][$v['serverid']];
    			$userinfo[$k]['channel'] = $this->data['channel_list'][$v['channel']]?$this->data['channel_list'][$v['channel']]:$v['channel'];
    			$userinfo[$k]['lip'] = long2ip($v['lip']);
    			$userinfo[$k]['rip'] = long2ip($v['rip']);
    			$userinfo[$k]['lasttime'] = date('Ymd H:i:s',$v['lasttime']);
    			$userinfo[$k]['registertime'] = date('Ymd H:i:s',$v['registertime']);
    			$this->load->model('GameEmoney_model');
    			$data = $this->GameEmoney_model->userEmoney(array('accountid'=>$v['accountid'],'serverid'=>$v['serverid']),'emoney');
    			$userinfo[$k]['emoney'] = $data[0]['emoney'];
    			$paydata = $this->GameEmoney_model->payMoney(array('accountid'=>$v['accountid'],'serverid'=>$v['serverid']),'total_recharge_num');
    			$userinfo[$k]['total_recharge_num'] = $paydata[0]['total_recharge_num'];
    			
    		}
    		if (!empty($userinfo)) echo json_encode(['status'=>'ok', 'data'=>$userinfo]);
    		else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
    	}else{
    		$this->data['account_id_filter'] = true;
    		$this->data['hide_channel_list'] = true;
    		$this->data['user_id_filter'] = true;
    		$this->data['hide_start_time'] = true;
    		$this->data['hide_end_time'] = true;
    		$this->data['user_name_filter'] = true;
    		$this->body = 'PlayerAnalysis/user';
    		$this->layout();
    	}
    }
    public function NewPlayer()
    {
        $this->data['hide_server_list'] = true;
        $this->body = 'PlayerAnalysis/new';
        $this->layout();
    }
    public function ActivePlayer()
    {
        $this->body = 'PlayerAnalysis/ActivePlayer';
        $this->layout();
    }

    /**
     * 留存统计
     */
    public function Remain()
    {
        $this->body = 'PlayerAnalysis/UserRemain';        
        $this->data ['hide_server_list'] = true;
        $this->layout();
    }
    /**
     * 有效玩家
     */
    public function EffectivePlayer()
    {
        $this->body = 'PlayerAnalysis/EffectivePlayer';
        $this->layout();
    }
    public function Life()
    {
        $accountid = $this->input->get('accountid');
        $data = [];
        if ($accountid > 0 && is_numeric($accountid)) {
            $this->loadModel();
            $data = $this->player_analysis_model->Life($this->appid, $accountid);
        }
        //print_r($data);exit;
        $this->data['accountid'] = $accountid;
        $this->data['common_data'] = $data['common_data'];
        $this->data['data'] = $data['data'];
        //print_r($data);
        $this->body = 'PlayerAnalysis/Life';
        $this->layout();
    }

    public function DeviceDetail()
    {
        $this->body = 'PlayerAnalysis/DeviceDetail';
        $this->layout();
    }
    private function loadModel()
    {
        $this->load->model('player_analysis_model');
    }
    public function getNewPlayerData()
    {
        $this->loadModel();
        $date1 = $this->input->get('date1') ?
            $this->input->get('date1', true) :
            date('Y-m-d', strtotime('-7 days'));
        $date2 = $this->input->get('date2') ?
            $this->input->get('date2', true) :
            date('Y-m-d');
        $date1 = date('Ymd', strtotime($date1));
        $date2 = date('Ymd', strtotime($date2));
        $channel_id = $this->input->get('channel_id');
        //echo $date1, '---', $date2,'---',$server_id;

        $json_data = $outputData = $legend = $xAxis = [];
        //获取激活数据和获取新增数据
        $data = $this->player_analysis_model->RegisterDevice($this->appid, $date1, $date2, 0, $channel_id);
        if ( !count($data) ) {
            exit('{"status":"fail"}');
        }
        //print_r($data);

        $raw = $date = $outputData = [];
        foreach ($data['register'] as $_data) {
            $date[$_data['date']] = date('m/d', strtotime($_data['date']));
            $outputData['register'][$_data['date']] = $_data['total'];
            $outputData['device'][$_data['date']]    = 0;
            $raw[$_data['date']]['register'] = $_data['total'];
            $raw[$_data['date']]['device'] = 0;
        }
        foreach ($data['device'] as $_data) {
            $date[$_data['date']] = date('m/d', strtotime($_data['date']));
            $outputData['device'][$_data['date']]   = $_data['total'];
            $raw[$_data['date']]['device'] = $_data['total'];
            if (!isset($outputData['register'][$_data['date']])) {
                $outputData['register'][$_data['date']] = 0;
                $raw[$_data['date']]['register'] = 0;
            }
        }
        ksort($outputData['device']);
        ksort($outputData['register']);
        //print_r($outputData['register']);
        //print_r($outputData['device']);
        ksort($date);
        $xAxis = array_values(array_unique($date));
        $dt = ['register'=>'新增账户','device'=>'安装解压'];
        foreach ($outputData as $_dt=>$_od) {
            $legend['data'][] = $dt[$_dt];
            //print_r($_od);
            //ksort($_od);
            $json_data[] = [
                'name' => $dt[$_dt],
                'type' => 'line',
                'smooth'=> true,
                'data'  => array_values($_od),
            ];
        }
        //print_r($json_data);exit;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                    'status'=>'ok',
                    'xAxis'=>$xAxis,
                    'series'=>$json_data,
                    'legend'=>$legend,
                    'raw'=>$raw,
                ])
            );

    }

    /**
     * 活跃账号数
     */
    public function ActiveAccounts($by_channel=false)
    {
        if (parent::isAjax()) {
            $db_sdk = $this->load->database('sdk', TRUE);
            $this->loadModel();

//            $server_id  = $this->input->get('server_id');
            $channel = $this->input->get('channel_id');
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
            $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
            $date1 = str_replace('-','', $date1);
            $date2 = str_replace('-','', $date2);
            $data = $this->player_analysis_model->getRealAuData($this->appid, $date1, $date2, 0, $channel, $by_channel);
            if ($data) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                            'status'=>'ok',
                            'data'  => $data
                        ])
                    );
            }
            else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                            'status'=>'fail',
                        ])
                    );
            }
        }
        else {
            $this->data['hide_server_list'] = true;
            if ($by_channel===true) {
                $this->data['bt'] = date('Y-m-d', strtotime('-1 days'));
                $this->data['hide_end_time'] = true;
                $this->data['by_channel'] = true;
            }
            else {
                $this->data['bt'] = date('Y-m-d', strtotime('-7 days'));
                $this->data['et'] = date('Y-m-d');
            }

            $this->body = 'PlayerAnalysis/ActiveAccounts';
            $this->layout();
        }
    }

    public function ActiveAccountsByChannel()
    {
        $this->ActiveAccounts(true);
    }

    public function getActiveData()
    {
        $this->loadModel();
        $date1 = $this->input->get('date1') ?
            $this->input->get('date1', true) :
            date('Y-m-d', strtotime('-7 days'));
        $date2 = $this->input->get('date2') ?
            $this->input->get('date2', true) :
            date('Y-m-d');
        $date1 = date('Ymd', strtotime($date1));
        $date2 = date('Ymd', strtotime($date2));
        $server_id = $this->input->get('server_id');
        $channel_id = $this->input->get('channel_id');

        
        //echo $date1, '---', $date2;
        //exit;

        $json_data = $outputData = $legend = $xAxis = [];
        //获取激活数据和获取新增数据
        $data = $this->player_analysis_model->getActiveData($this->appid, $date1, $date2, $server_id, $channel_id);
        if ( !count($data) ) {
            exit('{"status":"fail"}');
        }
        
        $date = [];
        $outputData = [];
        $oData = [];
        foreach ($data as $_data) {
            $date[] = date('m/d', strtotime($_data['sday']));
            $oData[$_data['sday']] = $_data;
            $oData[$_data['sday']]['novip'] = $_data['dau'] - $_data['vip_role'];;
            $oData[$_data['sday']]['text'] = "<a href='javascript:showdetail({$_data['sday']})'>服务器详细</a>";;
            $outputData['role'][$_data['sday']] = $_data['new_role'];
            $outputData['dau'][$_data['sday']] = $_data['dau'];
            $outputData['wau'][$_data['sday']] = $_data['wau'];
            $outputData['mau'][$_data['sday']] = $_data['mau'];
//            $outputData['dau_ac'][$_data['sday']] = $_data['dau_ac'];
//            $outputData['wau_ac'][$_data['sday']] = $_data['wau_ac'];
//            $outputData['mau_ac'][$_data['sday']] = $_data['mau_ac'];
            $outputData['vip_role'][$_data['sday']] = $_data['vip_role'];
            $outputData['normal'][$_data['sday']] = $_data['dau'] - $_data['vip_role'];
        }

        $xAxis = array_unique($date);
        $dt = ['role'=>'新增角色','dau'=>'DAU', 'wau'=>'WAU','mau'=>'MAU',
            'vip'=>'付费玩家', 'normal'=>'非付费玩家','vip_role'=>'付费玩家',
//            'dau_ac'=>'日活跃账号',
//            'wau_ac'=>'周活跃账号','mau_ac'=>'月活跃账号'
        ];
        foreach ($outputData as $_dt=>$_od) {
            $legend['data'][] = $dt[$_dt];
            //ksort($_od);
            $json_data[] = [
                'name' => $dt[$_dt],
                'type' => 'line',
                'smooth'=> true,
                'data'  => array_values($_od),
            ];
        }
        
        $where['begindate'] = $date1;
        $where['enddate'] = $date2;
        $where['appid'] = $this->appid;
        $where['serverid'] = $server_id;
        $where['channel'] = $channel_id;
        $field = "online_date,sum(if(online/60>5,1,0)) m1,sum(if(online/60>120,1,0)) m2,sum(if(online/60>500,1,0)) m3";
        $group = 'online_date';
        $this->load->model('Online_analysis_model');
        $result = $this->Online_analysis_model->getOnlineData($where,$field,$group);
        foreach ($result as $_data) {
        	$oData[$_data['online_date']]['m1'] = $_data['m1'];
        	$oData[$_data['online_date']]['m2'] = $_data['m2'];
        	$oData[$_data['online_date']]['m3'] = $_data['m3'];
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'=>'ok',
                'xAxis'=>$xAxis,
                'series'=>$json_data,
                'legend'=>$legend,
            		'odata'=>$oData,
                ])
            );

    }

    public function getRemainData()
    {
        $this->loadModel();
        $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
        $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
        $date1 = date('Ymd', strtotime($date1));
        $date2 = date('Ymd', strtotime($date2));
        //echo $date1, '---', $date2;
        //exit;
        $server_id = $this->input->get('server_id');
        $channel_id = $this->input->get('channel_id');

        $json_data = $outputData = $legend = $xAxis = [];
        //获取激活数据和获取新增数据
        $data = $this->player_analysis_model->getRemainData($this->appid, $date1, $date2, $server_id, $channel_id);
        if ( !count($data) ) {
            exit('{"status":"fail"}');
        }
        //print_r($data);
        //exit;
        
        $new_user = $this->player_analysis_model->newUser($this->appid, $date1, $date2, $server_id, $channel_id);

        $outputData = $date = $raw = [];
        foreach ($data as $_data) {
            $date[] = date('m/d', strtotime($_data['sday']));
            //$outputData['role'][$_data['sday']] = $_data['usercount'];
            //$outputData['day1'][$_data['sday']] = $_data['day1'] / $_data['usercount'];
            //$outputData['day3'][$_data['sday']] = $_data['day3'] / $_data['usercount'];
            //$outputData['day7'][$_data['sday']] = $_data['day7'] / $_data['usercount'];
            //$outputData['day30'][$_data['sday']] = $_data['day30'] / $_data['usercount'];
            $outputData['day1'][$_data['sday']] = $_data['usercount']==0 ?0:number_format($_data['day1'] / $_data['usercount'], 2) * 100 ;
            $outputData['day3'][$_data['sday']] = $_data['usercount']==0 ?0:number_format($_data['day3'] / $_data['usercount'], 2) * 100 ;
            $outputData['day7'][$_data['sday']] = $_data['usercount']==0 ?0:number_format($_data['day7'] / $_data['usercount'], 2) * 100 ;
            $outputData['day15'][$_data['sday']] = $_data['usercount']==0 ?0:number_format($_data['day15'] / $_data['usercount'], 2) * 100 ;
            $outputData['day30'][$_data['sday']] = $_data['usercount']==0 ?0:number_format($_data['day30'] / $_data['usercount'], 2) * 100 ;

            $raw[$_data['sday']]['role']        = $_data['usercount'];
            $raw[$_data['sday']]['day1']        = $_data['day1'];
        //    $raw[$_data['sday']]['day1_rate']   = $_data['usercount']==0 ?0:number_format($_data['day1'] / $_data['usercount'], 2) * 100 ;
            $raw[$_data['sday']]['day3']        = $_data['day3'];
       //     $raw[$_data['sday']]['day3_rate']   = $_data['usercount']==0 ?0:number_format($_data['day3'] / $_data['usercount'], 2) * 100 ;
            $raw[$_data['sday']]['day7']        = $_data['day7'];
       //      $raw[$_data['sday']]['day7_rate']   = $_data['usercount']==0 ?0:number_format($_data['day7'] / $_data['usercount'], 2) * 100 ;
            $raw[$_data['sday']]['day15']        = $_data['day15'];
         //   $raw[$_data['sday']]['day15_rate']   = $_data['usercount']==0 ?0:number_format($_data['day15'] / $_data['usercount'], 2) * 100 ;
            $raw[$_data['sday']]['day30']       = $_data['day30'];
         //   $raw[$_data['sday']]['day30_rate']  = $_data['usercount']==0 ?0:number_format($_data['day30'] / $_data['usercount'], 2) * 100 ;
            
          
         foreach ($new_user as $k2=>$v2){            	
            	if($v2['reg_date']==$_data['sday']){
            	  $raw[$_data['sday']]['role']=$v2['new_user'];            		
            	  $raw[$_data['sday']]['day1_rate']   = $_data['usercount']==0 ?0:number_format($_data['day1'] / $v2['new_user'], 2) * 100 ;
            	  $raw[$_data['sday']]['day3_rate']   = $_data['usercount']==0 ?0:number_format($_data['day3'] / $v2['new_user'], 2) * 100 ;
            	  $raw[$_data['sday']]['day7_rate']   = $_data['usercount']==0 ?0:number_format($_data['day7'] / $v2['new_user'], 2) * 100 ;
            	  $raw[$_data['sday']]['day15_rate']   = $_data['usercount']==0 ?0:number_format($_data['day15'] /$v2['new_user'], 2) * 100 ;
            	  $raw[$_data['sday']]['day30_rate']  = $_data['usercount']==0 ?0:number_format($_data['day30'] / $v2['new_user'], 2) * 100 ;  
            		
            	}
              	
            }
        }
        $xAxis = array_unique($date);
        $dt = [
            //'role'=>'新增玩家',
            'day1'=>'次日留存',
            'day3'=>'3日留存',
            'day7'=>'7日留存',
            'day30'=>'30日留存'
        ];
        foreach ($outputData as $_dt=>$_od) {
            $legend['data'][] = $dt[$_dt];
            //ksort($_od);
            $json_data[] = [
                'name' => $dt[$_dt],
                'type' => 'line',
                'smooth'=> true,
                'data'  => array_values($_od),
            ];
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                    'status'=>'ok',
                    'xAxis'=>$xAxis,
                    'series'=>$json_data,
                    'legend'=>$legend,
                    'raw'=>$raw,
                ])
            );

    }

    public function getEffectivePlayerData()
    {
        $this->loadModel();
        $date1 = $this->input->get('date1') ?
            $this->input->get('date1', true) :
            date('Y-m-d', strtotime('-7 days'));
        $date2 = $this->input->get('date2') ?
            $this->input->get('date2', true) :
            date('Y-m-d');
        $date1 = date('Ymd', strtotime($date1));
        $date2 = date('Ymd', strtotime($date2));
        //echo $date1, '---', $date2;
        //exit;
        $server_id = $this->input->get('server_id');

        $json_data = $outputData = $legend = $xAxis = [];
        //获取激活数据和获取新增数据
        $data = $this->player_analysis_model->getRemainData($this->appid, $date1, $date2, $server_id);
        if ( !count($data) ) {
            exit('{"status":"fail"}');
        }
        //print_r($data);
        //exit;

        $date = [];
        $outputData = [];
        foreach ($data as $_data) {
            $date[] = date('m/d', strtotime($_data['sday']));
            $outputData['role'][$_data['sday']] = $_data['usercount'];
            //$outputData['day1'][$_data['sday']] = $_data['day1'] / $_data['usercount'];
            //$outputData['day3'][$_data['sday']] = $_data['day3'] / $_data['usercount'];
            //$outputData['day7'][$_data['sday']] = $_data['day7'] / $_data['usercount'];
            //$outputData['day30'][$_data['sday']] = $_data['day30'] / $_data['usercount'];
            $outputData['day1'][$_data['sday']] = $_data['day1'];
            $outputData['day3'][$_data['sday']] = $_data['day3'];
            $outputData['day7'][$_data['sday']] = $_data['day7'];
            $outputData['day30'][$_data['sday']] = $_data['day30'];
        }

        $xAxis = array_unique($date);
        $dt = [
            'role'=>'新增角色',
            'day1'=>'次日留存',
            'day3'=>'3日留存',
            'day7'=>'7日留存',
            'day30'=>'30日留存'
        ];
        foreach ($outputData as $_dt=>$_od) {
            $legend['data'][] = $dt[$_dt];
            //ksort($_od);
            $json_data[] = [
                'name' => $dt[$_dt],
                'type' => 'line',
                'smooth'=> true,
                'data'  => array_values($_od),
            ];
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'=>'ok',
                'xAxis'=>$xAxis,
                'series'=>$json_data,
                'legend'=>$legend
                ])
            );

    }

    /**
     * 设备详情
     */
    public function getDeviceDetailData ()
    {
        $this->loadModel();
        //$date1 = $this->input->get('date1') ?
        //    $this->input->get('date1', true) :
        //    date('Y-m-d', strtotime('-7 days'));
        //$date2 = $this->input->get('date2') ?
        //    $this->input->get('date2', true) :
        //    date('Y-m-d');
        //$date1 = strtotime($date1 .' 00:00:00');
        //$date2 = strtotime($date2 . ' 23:59:59');
        //$server_id = $this->input->get('server_id');

        //echo $date1, '---', $date2;
        //exit;

        $json_data = $outputData = $legend = $xAxis = [];

        $data = $this->player_analysis_model->getDeviceData($this->appid, 0, 0, 0, 0);
        if ( !count($data) ) {
            exit('{"status":"fail"}');
        }

        $date = [];
        $outputData = [];
        $total = 0;
        foreach ($data as $_data) {
            $total += $_data['cnt'];//总活跃数
            $date[] = $_data['client_type'];
            $legend['data'][] = $_data['client_type'];
            $outputData[$_data['client_type']] = $_data['cnt'];
        }

        $xAxis = array_unique($date);
        //ksort($_od);
        $json_data[] = [
            'name' => '活跃玩家',
            'type' => 'bar',
            'smooth'=> true,
            'data'  => array_values($outputData),
        ];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                    'status'=>'ok',
                    'xAxis'=>$xAxis,
                    'series'=>$json_data,
                    'legend'=>$legend,
                    'total' =>$total,
                ])
            );

    }

  
    
    
    
    
    
    /**
     * cli模式运行
     *
     * php /var/www/ci/index.php RealTime run
     */
    public function run()
    {
        $data = $this->db->query('SELECT appid FROM auth_config')->result_array();
        foreach ($data as $_d) {
            $this->OnlineCal($_d['appid']);
            $this->DeviceCal($_d['appid']);
            usleep(500);
        }
    }

    /**
     * 统计实时在线——每小时
     */
    private function OnlineCal($appid)
    {
        $t1     = strtotime('-1 hours');
        $t2     = time();
        $hour   = date('H', $t2);
        $date   = date('Ymd', $t2);
        $this->load->model('dayonline_model');
        $this->dayonline_model->init($appid,$t1, $t2);
        $data = $this->dayonline_model->hour_counts($t1, $t2, $hour, $date);
        if (count($data)) {
            $this->db->insert_batch('sum_online_hour', $data);
        }
        return true;
    }

    /**
     * 统计实时在线——每小时
     */
    private function DeviceCal($appid)
    {
        $t1     = strtotime('-1 hours');
        $t2     = time();
        $hour   = date('H', $t2);
        $date   = date('Ymd', $t2);
        $this->load->model('device_model');
        $this->device_model->init($appid,$t1, $t2);
        $data = $this->device_model->hour_counts($t1, $t2, $hour, $date);
        if (count($data)) {
            $this->db->insert_batch('sum_online_hour', $data);
        }
        return true;
    }
    
    /*
     * 邀请好友统计需求  zzl 0901
     */
    public function inviteFriend(){

        if (parent::isAjax ()) {
            $beginDate = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
            $endDate = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );

            $where ['begindate'] = date ( 'Ymd', strtotime ( $beginDate ) );
            $where ['enddate'] = date ( 'Ymd', strtotime ( $endDate ) );
            $group="viplev";
            $field="";
            $this->loadModel();
            $data = $this->player_analysis_model->inviteFriend($where, $field, $group);
             
            if (! empty ( $data)) {
                foreach ($data as $key => $val) {
                    $data [$key] ['text'] .= "<a class='xi' be='{$where['begindate']}' en='{$where['enddate']}' tid='{$val['viplev']}' href='javascript:showIframe({$where['begindate']},{$where['enddate']}, {$val['viplev']}, 1)'>等级分布</a> <a class='fu' be='{$where['begindate']}' en='{$where['enddate']}' tid='{$val['viplev']}' href='javascript:showIframe({$where['begindate']}, {$where['enddate']}, {$val['viplev']}, 2)'>区服分布</a>";
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
           
            $this->body = 'PlayerAnalysis/inviteFriend';
            $this->layout ();
        }
    }
    
    /*
     * 典型玩家数据 zzl  20171025
     */
    public function tipical(){

        if (parent::isAjax ()) {
            $date = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d' );
            $where ['days'] = $this->input->get ( 'days' ) ? $this->input->get ( 'days', true ) : 10;
            $where ['days2'] = $this->input->get ( 'days2' ) ? $this->input->get ( 'days2', true ) : 15;
            $where ['vip_level'] = $this->input->get ( 'vip_level' ) ? $this->input->get ( 'vip_level', true ) : '';
            $where ['channel'] = $this->input->get ( 'channel' );           
          
            $where ['date']=date ( 'Ymd', strtotime ( $date ) );
            $where ['begindate'] = date ( 'Ymd', strtotime ( $date ) );
          
            $where ['serverids'] = $this->input->get ( 'server_id' );
            $where ['channels'] = $this->input->get ( 'channel_id' );     
             
            $table = '';
            $group="total_days";
            $order='total_days';
            $field="count(*) as signin ,userid,accountid,serverid,channel,vip_level,client_time,ROUND(AVG(user_level),2) as user_level ,create_time,total_days,ROUND(AVG(prestige),2) as prestige,ROUND(AVG(synscience_avg),2) as synscience_avg,ROUND(AVG(godstep),2) as godstep,ROUND(AVG(stonestep_avg),2) as stonestep_avg,ROUND(AVG(stonelevel_avg),2) as stonelevel_avg,ROUND(AVG(level_avg),2) as level_avg,ROUND(AVG(intimacy_avg),2) as intimacy_avg,ROUND(AVG(individual_avg),2) as individual_avg,ROUND(AVG(effort_avg),2) as effort_avg,ROUND(AVG(baofen_avg),2) as baofen_avg,ROUND(AVG(prestige_avg),2) as prestige_avg,ROUND(AVG(handbook_avg),2) as handbook_avg,logdate,created_at";            
            $this->loadModel();
            $data = $this->player_analysis_model->tipical( $table, $where, $field, $group, $order, $limit);
            foreach ($data as &$v){                
                foreach ($data['data2'] as $v2){
                   if($v['total_days']==$v2['total_days']){
                       
                       $v['pay_avg']=round($v2['avg_money'],2);                       
                   }                
                }                
                
           if($data['data3']){
               foreach($data['data3'] as $v3){
                   if($v['total_days']==$v3['total_days']){
                       $v['consume']=round($v3['consume'],2);                        
                   }
               }
           }
                
            }             
             
            if (! empty ( $data))
                echo json_encode ( [
                    'status' => 'ok',
                    'data' => $data
                ] );
                else
                    echo json_encode ( [
                        'status' => 'fail',
                        'info' => '未查到数据'
                    ] );
        } else {
            
            $this->data ['show_vip_level'] = true;
            $this->data ['show_days'] = true;
            $this->data ['type_list'] = $types;
            $this->data ['hide_end_time'] = true;
            $this->data ['hide_server_list'] = true;             
            $this->body = 'PlayerAnalysis/tipical';
            $this->layout ();
        }
        
        
        
        
        
    }
    
    
    
    
}
