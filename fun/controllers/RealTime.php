<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 * 实时数据统计
 */
//ini_set('display_errors', 'On');
include 'MY_Controller.php';
class RealTime extends MY_Controller {
    public $bt = '';
    public $et = '';
    /**
     * @var $Real_time_model Real_time_model
     */
    public $Real_time_model;
    public function __construct()
    {
        $this->bt = date('Y-m-d', strtotime('-1 days'));
        $this->et = date('Y-m-d');
        parent::__construct();
        $this->load->model('Real_time_model');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('Auth/login', 'refresh');
        }
        $this->body = 'home';
        $this->layout();
    }

    /**
     * 实时在线-sdk数据库取数据库
     */
    public function OnlineRt()
    {
        if (parent::isAjax()) {
            /*$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
            $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');*/
            $date1 = date('Y-m-d H:i', strtotime('-1 mins'));
            $date2 = date('Y-m-d H:i');
            $status = 'fail';
            $tm_date1 = strtotime($date1);
            $tm_date2 = strtotime($date2);
            $date1 = date('ymdHi', $tm_date1);
            $date2 = date('ymdHi', $tm_date2);
            $serverid = $this->input->get('server_id');
            if(isset($this->data['all_server_list']) && !$serverid){
            	$serverid = $this->data['server_list'];
            }
            $data = $this->Real_time_model->online_rt($this->appid,  $date1,$date2,$serverid);
            if ($data)  $status = 'ok';
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                        'status'=> $status,
                        'data'  => $data,
                    ])
                );
        }
        else {
            /*$bt = date('Y-m-d H:i', strtotime('-1 mins'));
            $et = date('Y-m-d H:i');
            parent::setLayOutParam('bt', $bt);
            parent::setLayOutParam('et', $et);*/
            //print_r($this->data);
            $this->data['hide_start_time'] = true;
            $this->data['hide_end_time'] = true;
        	
            $this->data['date_time_picker'] = true;
            $this->data['hide_channel_list'] = true;
            $this->body = 'RealTime/online_rt';
            $this->layout();
        }
    }
    public function Online()
    {
        if (parent::isAjax()) {

        }
        else {
            $bt = date('Y-m-01');
            parent::setLayOutParam('bt', $bt);
            //print_r($this->data);
            $this->data['hide_channel_list'] = true;
            $this->body = 'RealTime/online';
            $this->layout();
        }
    }
    /**
     * 获取收入数据
     */
    public function Income()
    {
        $this->body = 'RealTime/income';
        $this->layout();

    }

    /**
     * 安装解压
     */
    public function Device()
    {
        $this->data['hide_server_list'] = true;
        $this->body = 'RealTime/device';
        $this->layout();
    }

    /**
     * 设备激活
     */
    public function DeviceActive()
    {
        $this->data['hide_server_list'] = true;
        $this->body = 'RealTime/device_active';
        $this->layout();
    }
    public function NewPlayer()
    {
        //$this->data['hide_end_time'] = true;
        $this->data['hide_server_list'] = true;
        $this->body = 'RealTime/new_player';
        $this->layout();
    }

    /**
     * 活跃玩家
     */
    public function ActivePlayer()
    {
        $this->body = 'RealTime/player';
        $this->layout();
    }

    /**
     * 获取实时在线数据
     */
    public function getOnlineData()
    {
        $this->getData(real_time_model::TBL_ONLINE);

    }
    /**
     * 获取实时在线数据
     */
    public function getIncomeData()
    {
        $this->getData(real_time_model::TBL_INCOME);
    }

    /**
     * 获取实时角色创建数据
     */
    public function getNewRoleData()
    {
        $this->getNewRole();
    }


    /**
     * 获取实时在线数据
     */
    public function getActivePlayerData()
    {
        $this->getData(real_time_model::TBL_DAY_ONLINE);
    }

    /**
     * 获取实时设备激活数据
     */
    public function getDeviceData()
    {
        $this->getData(real_time_model::TBL_DEVICE);
    }
    /**
     * 获取实时设备激活数据
     */
    public function getDeviceActiveData()
    {
        $this->getDataNew('DeviceActive');
    }



    /**
     * 获取各种数据
     *
     * @param $model Object 模型
     */
    private function getDataNew($table)
    {
    	//获取每小时统计数据
    	$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
    	$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
    	$tm_date1 = strtotime($date1. ' 00:00:00');
    	$tm_date2 = strtotime($date2. ' 23:59:59');
    	$date1 = date('Ymd', $tm_date1);
    	$date2 = date('Ymd', $tm_date2);
    	$serverid = $this->input->get('server_id');
    	$channel  = $this->input->get('channel_id');
    	$count_total  = 0;
    	//echo $date1, '---', $date2;
    	//exit;
    	//$this->load->model('real_time_model');
    	$this->load->database();
    
    	$this->Real_time_model->init($this->appid, '', '', '', '', null, $this->db);
    	$json_data = $outputData = $legend =  [];
    	$data_all = $this->Real_time_model->DeviceActiveData($this->appid, $tm_date1, $tm_date2, $channel);
    	//print_r($data_all);
    	$data = $data_all['device'];
    	if (isset($data_all['register'])) {
    		$reg_data = [];
    		foreach ($data_all['register'] as $item) {
    			$reg_data[$item['date']] = $item['cnt'];
    		}
    	}
    	$daycount = floor(($tm_date2-$tm_date1)/86400);
    	for($i=0;$i<=$daycount;$i++){
    		$days[] = date('Ymd',strtotime("$date1 +$i days"));
    	}
    	//$hours = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
    	if ( !count($data) ) {
    		exit('{"status":"fail"}');
    	}
    	foreach ($data as $_d) {
    		$count_total += (int)$_d['cnt'];
    		$outputData[$_d['date']] = (int)$_d['cnt'];
    	}
    	
    	$_day_index = array_keys($outputData);
    	$diff = array_diff( $days, $_day_index);
    	if ($diff) {
    		foreach($diff as $_diff_day) {
    			$outputData[$_diff_day] = 0;
    		}
    	}
    	ksort($outputData);
    	$_day_index_reg = array_keys($reg_data);
    	$diff_reg = array_diff( $days, $_day_index_reg);
    	if ($diff_reg) {
    		foreach($diff_reg as $_diff_day) {
    			$reg_data[$_diff_day] = 0;
    		}
    	}
    	ksort($reg_data);
    	$rares = array();
    	foreach ($outputData as $k=>$v){
    		$rares[$k] = $v==0?0:round($reg_data[$k]/$v*100,2);
    	}
    	//print_r($outputData);exit;
    	$output = array();
    	$legend['data'][] = '激活数量';
    	$legend['data'][] = '设备注册数量';
    	$legend['data'][] = '注册转化率';
    	$json_data[] = [
    			'name' => '激活数量',
    			'type' => 'line',
    			'smooth'=>true,
    			'data'  => array_values($outputData),
    	];
    	$json_data[] = [
    			'name' => '设备注册数量',
    			'type' => 'line',
    			'smooth'=>true,
    			'data'  => array_values($reg_data),
    	];
    	$json_data[] = [
    			'name' => '注册转化率',
    			'type' => 'line',
    			'smooth'=>true,
    			'data'  => array_values($rares),
    	];
    	$output = array_merge($output,[
    			'status'     =>'ok',
    			'series'     =>$json_data,
    			'legend'     =>$legend,
    			'count_total'=>$count_total,
    			'category'=>$days,
    			'output'=>$outputData,
    			'rares'=>$rares
    	]);
    	if (isset($reg_data)) $output['reg_data'] = $reg_data;
    	//print_r($)
    	//exit;
    	$this->output
    	->set_content_type('application/json')
    	->set_output(json_encode($output));
    }
    /**
     * 获取各种数据
     *
     * @param $model Object 模型
     */
    private function getData($table)
    {
        //获取每小时统计数据
        $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
        $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
        $tm_date1 = strtotime($date1. ' 00:00:00');
        $tm_date2 = strtotime($date2. ' 23:59:59');
        $date1 = date('Ymd', $tm_date1);
        $date2 = date('Ymd', $tm_date2);
        $serverid = $this->input->get('server_id');
        if(!$serverid && isset($this->data['all_server_list'])){
        	$serverid = $this->data['all_server_list'];
        }
        $channel  = $this->input->get('channel_id');
        $count_total  = 0;
        //echo $date1, '---', $date2;
        //exit;
        //$this->load->model('real_time_model');
        $this->load->database();

        $this->Real_time_model->init($this->appid, '', '', '', '', null, $this->db);
        $json_data = $outputData = $legend =  [];
        if ($table=='DeviceActive') {
            $data_all = $this->Real_time_model->DeviceActiveData($this->appid, $tm_date1, $tm_date2, $channel);
            //print_r($data_all);
            $data = $data_all['device'];
            if (isset($data_all['register'])) {
                $reg_data = [];
                foreach ($data_all['register'] as $item) {
                    $reg_data[$item['date']] = $item['cnt'];
                }
            }
        }
        else {
            $data = $this->Real_time_model->get_perhour($date1, $date2, $serverid, $channel,$table);
        }
        $hours = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
        if ( !count($data) ) {
            exit('{"status":"fail"}');
        }
        foreach ($data as $_d) {
            $count_total += (int)$_d['cnt'];
            $outputData[$_d['date']][$_d['hour']] = (int)$_d['cnt'];
        }
        //print_r($outputData);exit;
        $output = array();
        if ($table==Real_time_model::TBL_ONLINE) {
            $this->load->model('Online_analysis_model');
            $this->Online_analysis_model->init( $this->appid, $date1, $date2, 0, null, $this->db);
            $onlineAvgOut = [];
            $onlineAvg = $this->Online_analysis_model->GetOnlineTimeAvg($serverid, $channel);
            foreach ($onlineAvg as $item) {
                $onlineAvgOut[$item['date']]['online_time'] = $item['total_online_time'];
                $onlineAvgOut[$item['date']]['online_num']  = $item['total_online_num'];
                $onlineAvgOut[$item['date']]['avg']         = number_format($item['total_online_time'] / $item['total_online_num'] / 60, 2);
            }
            $output['online_avg'] = $onlineAvgOut;
            unset($onlineAvgOut);
            unset($onlineAvg);
        }
        //$today = date('Ymd');
        //$yesterday = date('Ymd', strtotime('-1 days'));

        foreach ($outputData as $_day=>$_od) {
            $name = (string)$_day;
            $legend['data'][] = $name;
            $_hour_index = array_keys($_od);
            $diff = array_diff( $hours, $_hour_index);
            if ($diff) {
                foreach($diff as $_diff_hour) {
                    $_od[$_diff_hour] = 0;
                }
            }
            ksort($_od);
            $json_data[] = [
                'name' => $name,
                'type' => 'line',
                'smooth'=>true,
                'data'  => $_od,
            ];
        }
        $output = array_merge($output,[
            'status'     =>'ok',
            'series'     =>$json_data,
            'legend'     =>$legend,
            'count_total'=>$count_total,
        ]);
        if (isset($reg_data)) $output['reg_data'] = $reg_data;
        //print_r($)
        //exit;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }
    private function getNewRole()
    {
        //获取每小时统计数据
        $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
        $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
        $tm_date1 = strtotime($date1. ' 00:00:00');
        $tm_date2 = strtotime($date2. ' 23:59:59');
        $date1 = date('Ymd', $tm_date1);
        $date2 = date('Ymd', $tm_date2);
        $serverid = $this->input->get('server_id');
        $channel  = $this->input->get('channel_id');
        $count_total  = 0;
        $this->load->database();

        $this->Real_time_model->init($this->appid, '', '', '', '', null, $this->db);
        $json_data = $outputData = $legend =  [];
        $data_reg  = $this->Real_time_model->get_perhour($date1, $date2, $serverid,$channel,real_time_model::TBL_REGISTER);
        $data_role = $this->Real_time_model->get_perhour($date1, $date2, $serverid,$channel,real_time_model::TBL_NEW_ROLES);
        $hours = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
        if ( !count($data_reg) &&  ! count($data_role)) {
            exit('{"status":"fail"}');
        }
        foreach ($data_role as $_d) {
            //$count_total += (int)$_d['cnt'];
            $outputData[$_d['date']]['role'][$_d['hour']] = (int)$_d['cnt'];
        }
        foreach ($data_reg as $_d) {
            //$count_total += (int)$_d['cnt'];
            $outputData[$_d['date']]['reg'][$_d['hour']] = (int)$_d['cnt'];
            //$outputData['rate'][$_d['hour']] = round($outputData['role'][$_d['hour']] / (int)$_d['cnt'], 2);
        }
        //echo json_encode($outputData);exit;
        $title_list = ['reg'=>'注册数','role'=>'角色数','rate'=>'转化率'];
        foreach ($outputData as $date=>$items) {
            foreach ($items as $type=>$_od) {
                //$legend['data'][] = $title_list[$type];
                //$name = isset($day_idx[$_day]) ? $day_idx[$_day] :(string)$_day;
                $_hour_index = array_keys($_od);
                $diff = array_diff( $hours, $_hour_index);
                if ($diff) {
                    foreach($diff as $_diff_hour) {
                        $outputData[$date][$type][$_diff_hour] = 0;
                        //$_od[$_diff_hour] = 0;
                    }
                }
                //if ($type!='rate') $chart_type = 'line';
                //else $chart_type = 'bar';
                //ksort($_od);
                //$json_data[] = [
                //    'name' => $title_list[$type],
                //    'type' => $chart_type,
                //    //'type' => 'bar',
                //    'smooth'=>true,
                //    'data'  => $_od,
                //];
            }

        }


        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(
                    [
                        'status'=>'ok',
                        'series'  => $outputData,
                        //'series'=>$json_data,
                        //'legend'=>$legend,
                        //'count_total'=>$count_total,
                    ])
            );
        //return [$legend, $count_total, $json_data];
        //print_r($)
        //exit;

    }
}
