<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/2/24
 * Time: 22:31
 */
include 'MY_Controller.php';
class BaseDevice extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
    }

    public function device_list()
    {
//        $bt =
    }
}