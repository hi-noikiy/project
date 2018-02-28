<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 * 实时数据统计
 */
include_once 'MY_Controller.php';
class SystemAnalysis extends MY_Controller {

    public function Index()
    {
        $this->body = 'LostAnalysis/active';
        $this->layout();
    }

    public function Props()
    {
        $this->body = 'SystemAnalysis/props';
        $this->layout();
    }

    public function Copy()
    {
        $this->body = 'SystemAnalysis/copy';
        $this->layout();
    }

    public function Task()
    {
        //$this->body = 'LostAnalysis/lev_lost';
        $this->layout();
    }

    public function Level()
    {
        $this->body = 'SystemAnalysis/level';
        $this->layout();
    }
    public function Success()
    {
        $this->body = 'SystemAnalysis/success';
        $this->layout();
    }
    public function Emoney()
    {
        $this->body = 'SystemAnalysis/emoney';
        $this->layout();
    }

    /**
     * 升级历程
     */
    public function Upgrade()
    {
        $this->body = 'SystemAnalysis/upgrade';
        $this->layout();
    }

    public function RegFlow()
    {
        $this->data['hide_end_time']= true;
        $this->data['hide_server_list']= true;
        $this->body = 'SystemAnalysis/regflow';
        $this->layout();
    }


    private function loadModel()
    {
        $this->load->model('system_analysis_model');
    }

    public function getEmoney()
    {
        $this->getData('emoney_analysis');
    }
    public function getProps()
    {
        $this->getData('props_analysis');
    }
    public function getCopy()
    {
        $this->getData('copy_analysis');
    }

    public function getUpgrade()
    {
        $this->getSuccessAndLevel('upgrade_analysis');
    }

    public function getRegFlowData()
    {
        $date1 = $this->input->get('date1') ?$this->input->get('date1', true) :
            date('Y-m-d 00:00:00');
        //$date2 = $this->input->get('date2') ? $this->input->get('date2', true) . ' 23:59:59' :
        //    date('Y-m-d 23:59:59');

        $date1 = strtotime($date1);
        $date2 = strtotime("+1 days", $date1);//只统计当日的数据
        // $serverid = $this->input->get('server_id');
        $channel = $this->input->get('channel_id');
        $this->loadModel();
        $ret = $this->system_analysis_model->RegFlow($this->appid, $date1, $date2, $channel);
        //print_r($ret);
        $output = [];
        if (isset($ret['active']) && count($ret['active'])) {
            foreach($ret['active'] as $item) {
                $output[$item['hour']][$item['minute']][$item['channel']]['active'] += $item['cnt'];
            }
        }
        if (isset($ret['register_his']) && count($ret['register_his'])) {
            foreach($ret['register_his'] as $item) {
                $output[$item['channel']]['register_his'] += $item['cnt'];
            }
        }
        if (isset($ret['device']) && count($ret['device'])) {
            foreach($ret['device'] as $item) {
                $output[$item['hour']][$item['minute']][$item['channel']]['device'] += $item['cnt'];
            }
        }
        if (isset($ret['register']) && count($ret['register'])) {
            foreach($ret['register'] as $item) {
                $output[$item['hour']][$item['minute']][$item['channel']]['reg']        += $item['cnt'];
                if (!isset($output[$item['channel']]['device'])) {
                    $output[$item['hour']][$item['minute']][$item['channel']]['device']   = 0;
                    $output[$item['hour']][$item['minute']][$item['channel']]['reg_rate'] = 0;
                }
                else {
                    $output[$item['hour']][$item['minute']][$item['channel']]['reg_rate']   += ($item['cnt'] / $output[$item['hour']][$item['minute']][$item['channel']]['device']) * 100;
                }
            }
        }
        if (isset($ret['role']) && count($ret['role'])) {
            foreach($ret['role'] as $item) {
                $output[$item['hour']][$item['minute']][$item['channel']]['role']       += $item['cnt'];
                $output[$item['hour']][$item['minute']][$item['channel']]['role_rate']  += $item['cnt']/ $output[$item['hour']][$item['minute']][$item['channel']]['device'];
                if (!isset($output[$item['hour']][$item['minute']][$item['channel']]['reg'])) {
                    $output[$item['hour']][$item['minute']][$item['channel']]['reg'] = 0;
                    $output[$item['hour']][$item['minute']][$item['channel']]['reg_rate'] = 0;
                    $output[$item['hour']][$item['minute']][$item['channel']]['trans_rate'] = 0;
                }
                if (!isset($output[$item['hour']][$item['minute']][$item['channel']]['device'])) {
                    $output[$item['hour']][$item['minute']][$item['channel']]['device']   = 0;
                    $output[$item['hour']][$item['minute']][$item['channel']]['role_rate'] = 0;
                    $output[$item['hour']][$item['minute']][$item['channel']]['trans_rate'] = 0;
                }
                else {
                    $output[$item['hour']][$item['minute']][$item['channel']]['role_rate']   += ($item['cnt'] / $output[$item['hour']][$item['minute']][$item['channel']]['device']) * 100 ;
                    $output[$item['hour']][$item['minute']][$item['channel']]['trans_rate']  += ($item['cnt'] / $output[$item['hour']][$item['minute']][$item['channel']]['reg']) * 100 ;
                }
            }

        }
        if (!empty($output)) echo json_encode(['status'=>'ok', 'data'=>$output]);
        else echo json_encode(['status'=>'fail']);
    }

    private function getSuccessAndLevel($action)
    {
        $this->loadModel();
        $lev  = (int)$this->input->get('item_type');
        $lev2 = (int)$this->input->get('item_type2');
        $accountid = $this->input->get('accountid');
        if (!$lev && !$accountid) exit(json_encode(['status'=>'fail']));
        if (!$lev2) $lev2 = 10;

        $serverid = (int)$this->input->get('server_id');
        $ret = $this->system_analysis_model->$action($this->appid, $lev, $lev2, $serverid, $accountid);
        if (!empty($ret)) echo json_encode(['status'=>'ok', 'data'=>$ret]);
        else echo json_encode(['status'=>'fail']);
    }

    /**
     * 成就进度
     */
    public function getSuccess()
    {
        $this->getSuccessAndLevel('success_analysis');
    }

    /**
     * 关卡进度
     */
    public function getLevel()
    {
        $this->getSuccessAndLevel('level_analysis');
    }

    public function view_copy_lev()
    {
        $is_success = (int)$this->input->get('is_success');
        $copy_type  = (int)$this->input->get('type');
        $this->loadModel();
        //($appid, $copy_type, $is_success=0)
        $data = $this->system_analysis_model->copy_player_lev($this->appid, $copy_type, $is_success);
        $this->data['title'] = $is_success == 1 ? '副本通关玩家等级':'副本失败时玩家的等级';
        $this->data['copy_type'] = $copy_type;
        $this->data['copy_title'] = $this->input->get('title');
        $this->data['data'] = $data;
        $this->body = 'SystemAnalysis/view_copy_lev';
        $this->layout();
    }

    private function getData($action)
    {
        $this->loadModel();
        $date1 = $this->input->get('date1') ?$this->input->get('date1', true) :
            date('Y-m-d', strtotime('-7 days'));
        $date2 = $this->input->get('date2') ? $this->input->get('date2', true) :
            date('Y-m-d');
        $date1 = date('Ymd', strtotime($date1));
        $date2 = date('Ymd', strtotime($date2));
        $serverid = $this->input->get('server_id');
        $channel = $this->input->get('channel_id');
        $item_type = $this->input->get('item_type');
        $item_type2 = $this->input->get('item_type2');
        $this->system_analysis_model->init($this->appid, $date1, $date2, 0, $serverid, $channel, false);
        $ret = $this->system_analysis_model->$action($item_type, $item_type2);
        if (!empty($ret)) echo json_encode(['status'=>'ok', 'data'=>$ret]);
        else echo json_encode(['status'=>'fail']);
    }

}
