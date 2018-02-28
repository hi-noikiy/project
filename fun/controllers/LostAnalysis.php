<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 * 实时数据统计
 */
include_once 'MY_Controller.php';
class LostAnalysis extends MY_Controller {
    public function Index()
    {
        $this->data['hide_end_time'] = true;
        $this->body = 'LostAnalysis/active';
        $this->layout();
    }

    public function VIP_lost()
    {
        $this->body = 'LostAnalysis/vip_lost';
        $this->layout();
    }

    public function lev_lost()
    {
        $this->body = 'LostAnalysis/lev_lost';
        $this->layout();
    }
    public function Back()
    {
        $this->body = 'LostAnalysis/active_back';
        $this->layout();
    }
    private function loadModel()
    {
        $this->load->model('player_lost_model');
    }
    public function active()
    {
        $this->loadModel();
        $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
        //$date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
        $date1 = date('Ymd', strtotime($date1));
        //$date2 = date('Ymd', strtotime($date2));

        $channel    = $this->input->get('channel_id');
        $serverid   = $this->input->get('server_id');
        $data_type  = $this->input->get('data_type');//数据类型,0活跃流失1vip流失2等级流失
        //获取流失数据
        $data = $this->player_lost_model->getLostData($this->appid, $date1, $data_type, $serverid, $channel);
        if ( !count($data) ) {
            exit('{"status":"fail"}');
        }
        $total = 0;
        foreach ($data as $key=>$item) {
            $total += $item['usercount'];
            //$data[$key]['lost_1_rate'] = $item['lost']
        }
        echo json_encode(['status'=>'ok', 'collection'=>$data, 'total'=>$total]);
        //print_r($data);
        exit;
        $date = [];
        $outputData = $rawData = [];
        foreach ($data as $_data) {
            $date[] = date('m/d', strtotime($_data['sday']));
            if ($_data['usercount']>0) {
                $rawData[$_data['sday']]['lost_1']   = ($_data['usercount'] - $_data['lost_1']);
                $rawData[$_data['sday']]['lost_3']   = ($_data['usercount'] - $_data['lost_3']);
                $rawData[$_data['sday']]['lost_7']   = ($_data['usercount'] - $_data['lost_7']);
                $rawData[$_data['sday']]['lost_14']  = ($_data['usercount'] - $_data['lost_14']);
                $rawData[$_data['sday']]['lost_30']  = ($_data['usercount'] - $_data['lost_30']);


                $outputData['lost_1'][$_data['sday']]   = ($_data['usercount'] - $_data['lost_1'])  / $_data['usercount'];
                $outputData['lost_3'][$_data['sday']]   = ($_data['usercount'] - $_data['lost_3'])  / $_data['usercount'];
                $outputData['lost_7'][$_data['sday']]   = ($_data['usercount'] - $_data['lost_7'])  / $_data['usercount'];
                $outputData['lost_14'][$_data['sday']]  = ($_data['usercount'] - $_data['lost_14']) / $_data['usercount'];
                $outputData['lost_30'][$_data['sday']]  = ($_data['usercount'] - $_data['lost_30']) / $_data['usercount'];
            }
            else {
                $outputData['lost_1'][$_data['sday']]   = 0;
                $outputData['lost_3'][$_data['sday']]   = 0;
                $outputData['lost_7'][$_data['sday']]   = 0;
                $outputData['lost_14'][$_data['sday']]  = 0;
                $outputData['lost_30'][$_data['sday']]  = 0;
            }
        }
        //print_r($outputData);
        //exit;

        $xAxis = array_unique($date);
        $dt = [
            'lost_1'=>'次日流失',
            'lost_3'=>'3日流失',
            'lost_7'=>'7日流失',
            'lost_14'=>'14日流失',
            'lost_30'=>'30日流失',

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
                    //'xAxis'     =>$xAxis,
                    //'series'    =>$json_data,
                    //'legend'    =>$legend,
                    'rawLostData'=>$rawData,
                    //'rawLoginData'=>$data_au,
                ])
            );
    }

    /**
     * 回流
     */
    public function activeBack()
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

        $channel    = $this->input->get('channel_id');
        $serverid   = $this->input->get('serverid');
        $json_data = $outputData = $legend = $xAxis = [];
        //获取流失数据
        $data = $this->player_lost_model->getLostBackData($this->appid, $date1, $date2, $serverid, $channel);
        if ( !count($data) ) {
            exit('{"status":"fail"}');
        }
        //print_r($data_au);
        //print_r($data);
        //exit;

        $date = [];
        $outputData = $rawData = [];
        foreach ($data as $_data) {
            $date[] = date('m/d', strtotime($_data['sday']));
            $outputData['lost_7'][$_data['sday']]    = $_data['lost_8'];
            $outputData['lost_14'][$_data['sday']]   = $_data['lost_15'];
            $outputData['lost_30'][$_data['sday']]   = $_data['lost_31'];
        }
        //print_r($outputData);
        //exit;

        $xAxis = array_unique($date);
        $dt = [
            'lost_7'=>'7日回流',
            'lost_14'=>'14日回流',
            'lost_30'=>'30日回流',

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
                    'xAxis'     =>$xAxis,
                    'series'    =>$json_data,
                    'legend'    =>$legend,
                ])
            );
    }

}
