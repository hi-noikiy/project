<?php

include 'MY_Controller.php';
ini_set('memory_limit', '1024M');
class HomeNew extends MY_Controller {

    private function permissions()
    {

    }
    public function emptyhtml()
    {

        $this->layout();
    }

    public function auction()
    {
        if (parent::isAjax()) {
            $date = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d');
            $date2 = $this->input->get('date2') ? $this->input->get('date2', true) : date('Y-m-d');
            $where['begintime'] = strtotime($date);
            $where['endtime'] = strtotime($date2) + 86399;
            
            $where['date'] = date("Ymd", strtotime($date));
            
            $item_types_list = include APPPATH . 'config/item_types.php';
            
            $where['serverids'] = $this->input->get('server_id'); // 区服编号
            
            $this->load->model('HomeNew_model');
            $field = '';
            $group = "";
            $order = "id desc";
            $data = $this->HomeNew_model->auction($table, $where, $field, $group, $order, $limit);
            
            foreach ($data as $k => &$v) {
                
                foreach ($item_types_list as $k2 => $v2) {
                    if ($v['award_type'] == $k2) {
                        
                        $v['award_type'] = $v2;
                    }
                    if ($v['award_itemtype'] == $k2) {
                        
                        $v['award_itemtype'] = $v2;
                    }
                }
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'ok',
                'data' => $data
            ]
            ));
        } else {
            $this->data['page_title'] = "汇总-神兽来袭拍卖";
            $this->data['hide_channel_list'] = true;
            $this->data['hide_end_time'] = true;
            
            $this->body = 'Home/auction';
            $this->layout();
        }
    }
   
    


}