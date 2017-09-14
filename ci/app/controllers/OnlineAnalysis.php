<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 * 实时数据统计
 */
include_once 'MY_Controller.php';
class OnlineAnalysis extends MY_Controller {
    public function Index()
    {
        //$this->data['hide_end_time'] = true;
        $this->data['lvl_list']  = array(
            '0-4','5-10', '11-20', '21-30',
            '31-40',  '41-50', '51-60',
            '61-70', '71-80','81-90',
            '91-100','101-110','111-120',
            '121-240','241-300','301-360',
            '361-420', '421-480', '>=481',
        );;
        $this->body = 'OnlineAnalysis/online_time';
        $this->layout();
    }
    public function Habit()
    {
        //$this->body = 'OnlineAnalysis/Habit';
        $this->layout();
    }
    public function online()
    {
    	if (parent::isAjax()) {
    		$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-1 days'));
    		$serverids = $this->input->get('server_id');
    		if(count($serverids) != 1){
    			echo json_encode(['status'=>'fail','info'=>'请选择一个区服']);die;
    		}
    		$where['daytime'] = date('ymd',strtotime($date1));
    		$where['serverid'] = $serverids[0];
    		$status = 'fail';
    		$where['appid'] = $this->appid;
    		$this->load->model('Real_time_model');
    		$data = $this->Real_time_model->online_new($where,  'serverid,max(online) maxonline,SUBSTR(daytime,-4,2)  htime','htime');
    		if ($data)  $status = 'ok';
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode([
    				'status'=> $status,
    				'data'  => $data,
    		])
    		);
    	}else{
    		$this->data['hide_end_time'] = true;
    		$this->data['hide_channel_list'] = true;
    		$this->body = 'OnlineAnalysis/online';
    		$this->layout();
    	}
    }

    public function online_time()
    {
        $date1 = $this->input->get('date1');
        $date2 = $this->input->get('date2');
        $date1 = date('Ymd', strtotime($date1));
        $date2 = date('Ymd', strtotime($date2));

        $serveid = intval($this->input->get('serverid'));
        $channel = intval($this->input->get('channel'));

        $json_data = $outputData = $legend = $xAxis = [];
        $this->load->model('online_analysis_model');
        //获取激活数据和获取新增数据
        $data = $this->online_analysis_model->getPlayerOnline($this->appid,
            $date1, $date2, $serveid,$channel);
        if ( !count($data) ) {
            exit('{"status":"fail"}');
        }
        //print_r($data);
        //exit;

        $date = [];
        $outputData = [];
        foreach ($data as $_data) {
            $date[] = date('m/d', strtotime($_data['sday']));
            $outputData['vip_online'][$_data['sday']]       = $_data['vip_cnt']==0 ? 0 : number_format($_data['vip_online'] / $_data['vip_cnt'] / 60, 2);
            $outputData['active_online'][$_data['sday']]    = $_data['active_cnt']==0 ? 0 : number_format($_data['active_online'] / $_data['active_cnt'] / 60, 2);
            $outputData['new_online'][$_data['sday']]       = $_data['new_cnt']==0 ? 0 : number_format($_data['new_online'] / $_data['new_cnt'] / 60, 2);
        }

        $xAxis = array_unique($date);
        $dt = [
            'vip_online'    =>'付费玩家',
            'active_online' =>'活跃玩家',
            'new_online'    =>'新增玩家',
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

    public function online_time_lvl()
    {
        $lvl_list = array(
            '0-4','5-10', '11-20', '21-30',
            '31-40',  '41-50', '51-60',
            '61-70', '71-80','81-90',
            '91-100','101-110','111-120',
            '121-240','241-300','301-360',
            '361-420', '421-480', '>=481',
        );
        $date1 = $this->input->get('date1');
        $date2 = $this->input->get('date2');
        $date1 = date('Ymd', strtotime($date1));
        $date2 = date('Ymd', strtotime($date2));
        //$serveid_list = $channel_list = [];
        $serveid = $this->input->get('server_id');
        $channel = $this->input->get('channel_id');

        $this->load->model('online_analysis_model');
        $output = '';
        //获取激活数据和获取新增数据
        $data = $this->online_analysis_model->getSumPlayOnline($this->appid,
            $date1, $date2, $serveid,$channel);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                    'status'=>'ok',
                    'data'=>$data,
                ])
            );
    }

    private function loadModel()
    {
        $this->load->model('online_analysis_model');
    }

}
