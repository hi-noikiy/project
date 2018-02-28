<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/2/21
 * Time: 10:12
 */
class Base_model extends CI_Model
{
    protected $db1;
    protected $db_sdk;

    protected $appid;
    protected $bt;
    protected $et;

    public function __construct()
    {
        parent::__construct();
        $this->db1    = $this->load->database('default', TRUE);
        $this->db_sdk = $this->load->database('sdk', TRUE);
    }

    public function init($appid,$bt, $et)
    {
        $this->appid = $appid;
        $this->bt = $bt;
        $this->et = $et;
    }
    /**
     * 设置数据库连接,考虑到后期可能会有多个服务器
     * @param $db_name string 数据库配置名
     */
    public function setSdkDb($db_name)
    {
        $this->db_sdk = $this->load->database($db_name, TRUE);
    }

}