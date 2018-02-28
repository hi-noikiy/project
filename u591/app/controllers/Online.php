<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-11-7
 * Time: 下午9:41
 */
class Online extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        echo '{"hello":"world"}';
    }

    public function testInsert()
    {
        $user = array('username' => 'a',
            'name' => 'b');
        $this->mongo_db->insert('users', $user);

    }
    public function get_user()
    {
        print_r($this->mongo_db->limit(10)->get('users'));
    }
    public function online_list()
    {
//        $this->load->view('welcome_message');
//        $this->load->library('mongo_db');

        print_r($this->mongo_db->select(array('userid','accountid','serverid'))->limit(10)->offset(10)->get('dayonline'));
    }
}