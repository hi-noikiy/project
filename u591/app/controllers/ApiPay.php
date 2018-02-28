	<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 付费分析
* ==============================================
* @date: 2016-9-12
* @author: luoxue
* @version:
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class ApiPay extends CI_Controller{
	protected $json;
	protected $data;
	protected $appKey = '0dbddcc74ed6e1a3c3b9708ec32d0532';
	
	public function __construct(){
		
		parent::__construct();
 		if ( $this->input->server('REQUEST_METHOD') != 'POST') {
 			set_status_header(401);
 			echo 'Invalid Request!!!';
 			exit;
 		}
		$this->load->database('sdk');
		$sign = $this->input->post('sign');
		
		$this->json = base64_decode($this->input->post('data'));
		$this->data = json_decode($this->json, true);
		$mySign = $this->Sign($this->data);
  		if($mySign != $sign){
  			echo json_encode(['errcode'=>4003,'errmsg'=>'sign incorrect']);
  			exit;
  		}
		$this->config->load('api');
	}
	
	
	public function PaylogProcess(){
		$this->save($this->config->item(__FUNCTION__));
	}
	public function ApplePaylog(){
		$this->save('u_apple_paylog');
	}
	
	
	protected function Sign($data){
		ksort($data);
		$md5Str = http_build_query($data);
		$mySign = md5($md5Str.$this->appKey);
		return $mySign;
	}
 /**
     * 数据校验
     *
     * @param $table
     * @param $data
     */
    private function verify_data($table, $data) {
        //获取表的字段，此处可做缓存
        $sql = "DESC $table";
        $fields = $this->db->query($sql)->result_array();
        
        $field_list = [];
        foreach ($fields as $field) {
            $field_list[$field['Field']] = $field['Type'];
        }
        $fields = array_keys($field_list);
        foreach ($data as $key=>$val) {
            if  (!in_array($key, $fields)) {
                $this->set_response( ['errcode'=>4009,'errmsg'=>"[{$key}]字段非法"]);
                exit;
            }
            if (strpos($field_list[$key], 'int') !==false && !is_numeric($val)) {
                $this->set_response( ['errcode'=>4008,'errmsg'=>"[{$key}]字段格式错误{$val}"]);
                exit;
            }
        }
    }
    
    private function save($table) {
        $this->verify_data($table, $this->data);
        
        $ret = $this->db->insert($table, $this->data);
        if ($ret===TRUE) {
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);
        }
        else {
            log_message('error', $table
                . "数据写入失败,数据:".$this->json
                .",msg:".json_encode($this->db->error()));
            $this->set_response($this->errs[self::ERR6]);
        }

    }
    public function __destruct() {
    	$this->json = null;
    	$this->data = null;
    }
    
    
    private function set_response($data = NULL) {
    	set_status_header(200);
    	echo json_encode($data);
    	return true;
    }
	
}