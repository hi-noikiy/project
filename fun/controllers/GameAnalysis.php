<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 * 实时数据统计
 */
include_once 'MY_Controller.php';
class GameAnalysis extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    private function loadModel()
    {
        $this->load->model('GameDataAnalysis_model');
    }

    public function Remain()
    {
        if (parent::isAjax()) {
            $this->loadModel();
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d');
            $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
            $btm = strtotime($date1);
            $etm = strtotime($date2 . ' 23:59:59');
            $day_diff = ceil (($etm - $btm) / 86400);
            $remainData = [];
            for ($i=0; $i<$day_diff; $i++) {
                $date = date('Y-m-d', strtotime("+$i days", $btm));
                $data = $this->GameDataAnalysis_model->Remain($date);
                if ($data===false) continue;
                //print_r($data);
                $remainData[$date] = $data;
            }
            //print_r($remainData);exit;
            $dayList = array(0=>'次日',1=>'三日', 2=>'四日', 3=>'五日', 4=>'六日', 5=>'七日', 13=>'十五日',28=>'三十日');
            $output = [];
            foreach ($remainData as $date=>$items) {
                $output[$date]['total'] = $items['total'];
                foreach ($items['data'] as $day=>$item) {
                    $output[$date][$day] = $item['remain_rate'];
                    //$output[$date][$day]['remain_rate'] = $item['remain_rate'];
                    //$output[$date][$day]['lost_rate'] = $item['lost_rate'];
                }
            }
            //print_r($output);exit;
            //$server_id = $this->input->get('server_id');
            //$channel_id = $this->input->get('channel_id');
            //$json_data = $outputData = $legend = $xAxis = [];
            //获取激活数据和获取新增数据
            if (!count($remainData)) {
                exit('{"status":"fail"}');
            }
            //print_r($data);
            echo json_encode(['status'=>'ok', 'data'=>$output]);
        }
        else {
            $this->data['hide_server_list'] = true;
            $this->data['hide_channel_list'] = true;
            $this->body = 'GameRaw/Remain';
            $this->layout();
        }
    }

    public function Lost()
    {
        if (parent::isAjax()) {
            $this->loadModel();
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d');
            $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
            $btm = strtotime($date1);
            $etm = strtotime($date2 . ' 23:59:59');
            $day_diff = ceil (($etm - $btm) / 86400);
            $lostData = [];
            $dateList = [];
            for ($i=0; $i<$day_diff; $i++) {
                $date = date('Y-m-d', strtotime("+$i days", $btm));
                $data = $this->GameDataAnalysis_model->Lost($date);
                if ($data===false) continue;
                //print_r($data);
                $dateList[] = $date;
                $lostData[$date] = $data;
            }
            foreach ($lostData as $date=>$items) {
               foreach($items as $item) {
                   $output[$date][$item['level']] = $item['cnt'];
               }
            }
            //$json_data = $outputData = $legend = $xAxis = [];
            //获取激活数据和获取新增数据
            //$data = $this->GameDataAnalysis_model->Lost($date1);
            if (!count($output)) {
                exit('{"status":"fail"}');
            }
            echo json_encode(['status'=>'ok', 'data'=>$output, 'datelist'=>$dateList]);
        }
        else {
            //$this->data['hide_end_time'] = true;
            $this->data['hide_server_list'] = true;
            $this->data['hide_channel_list'] = true;
            $this->body = 'GameRaw/lost';
            $this->layout();
        }

    }

    public function LostTimeLong()
    {
        if (parent::isAjax()) {
            $this->loadModel();
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) :  date('Y-m-d', strtotime('-7 days'));
            $data = $this->GameDataAnalysis_model->LostTimeLong($date1);
            if ( !count($data) ) {
                exit('{"status":"fail"}');
            }
            echo json_encode(['status'=>'ok', 'data'=>$data]);
        } else {
            $this->data['hide_end_time'] = true;
            $this->data['hide_server_list'] = true;
            $this->data['hide_channel_list'] = true;
            $this->body = 'GameRaw/LostTimeLong';
            $this->layout();
        }
    }

    public function RiskLost()
    {
        if (parent::isAjax()) {
            $this->loadModel();
            $date1 = $this->input->get('date1') ? $this->input->get('date1', true) :  date('Y-m-d', strtotime('-7 days'));
            $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
            $btm = strtotime($date1);
            $etm = strtotime($date2 . ' 23:59:59');
            $day_diff = ceil (($etm - $btm) / 86400);
            $lostData = $wanted_normal_list = [];
            $dateList = [];
            for ($i=0; $i<$day_diff; $i++) {
                $date = date('Y-m-d', strtotime("+$i days", $btm));
                $data = $this->GameDataAnalysis_model->RiskLost($date);
                if ($data===false) continue;
                //print_r($data);
                $dateList[] = $date;
                $lostData[$date] = $data;
            }
            foreach ($lostData as $date=>$items) {
                foreach($items as $wanted_normal=>$cnt) {
                    $output[$date][$wanted_normal] = $cnt;
                    $wanted_normal_list[] = $wanted_normal;
                }
            }
            if ( !count($output) ) {
                exit('{"status":"fail"}');
            }
            $wanted_normal_list = array_unique($wanted_normal_list);
            echo json_encode(['status'=>'ok', 'data'=>$output, 'datelist'=>$dateList, 'wanted_normal'=>$wanted_normal_list]);
        } else {
            //$this->data['hide_end_time'] = true;
            $this->data['hide_server_list'] = true;
            $this->data['hide_channel_list'] = true;
            $this->body = 'GameRaw/RiskLost';
            $this->layout();
        }
    }
}
