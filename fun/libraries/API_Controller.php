<?php

defined('BASEPATH') OR exit('No direct script access allowed');
abstract class API_Controller extends CI_Controller {
    protected $json;//接收到的数据
    protected $data;//插入的数据

    const ERR6 = 4006;
    protected $errs = [
        4006=>['errcode'=>4006,'errmsg'=>'create fail'],
    ];
    /**
     * Constructor for the REST API
     *
     * @access public
     * @param string $config Configuration filename minus the file extension
     * e.g: my_rest.php is passed as 'my_rest'
     * @return void
     */
    public function __construct($config = 'api')
    {
        parent::__construct();
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            set_status_header(401);
            echo 'Invalid Request!!!';
            exit;
        }

        $this->load->library('mongo_db');
        $this->load->model('access_token_model');
        $ret = $this->access_token_model->check_access_token($this->input->post('access_token'));
        if ($ret['errcode']!==0)
        {
            echo json_encode($ret);
            exit;
        }
        $this->json = base64_decode($this->input->post('data'));
        $this->data = json_decode($this->json, true);
        $this->data['appid'] = $ret['appid'];
        $this->data['created_at'] = $_SERVER['REQUEST_TIME'];
        //如果是数字类型
        array_walk($this->data, function(&$item, $key){
//            if (in_array($key,['accountid','serverid','channel','viplev','lev']))
            if (ctype_digit($item)) $item = (int) $item;
        });
        $this->mongo_db->switch_db('test');
        $this->config->load($config);
    }


}
