<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/19
 * Time: 11:40
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
    //set the class variable.
    public $template  = array();
    public $data      = array();

    /*Loading the default libraries, helper, language */
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form','language','url'));
        $this->load->library(array('ion_auth','form_validation','session'));
    }
    /*Front Page Layout*/
    public function layout() {
        // making template and send data to view.
//        $this->template['header']   = $this->load->view('layout/header', $this->data, true);
        $this->template['left']   = $this->load->view('layout/left', $this->data, true);
        $this->template['body']   = $this->load->view($this->body, $this->data, true);
        $this->template['footer'] = $this->load->view('layout/footer', $this->data, true);
        $this->template['page_name'] = empty($this->page_name) ? '' : $this->page_name;
        $this->load->view('layout/front', $this->template);
    }
}