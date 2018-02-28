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
	public function Index() {
		$this->body = 'LostAnalysis/active';
		$this->layout ();
	}
	public function __construct() {
		parent::__construct ();
	}
	/**
	 * 获取区服列表
	 */
	public function GetServer() {
		if (parent::isAjax ()) {
		 
			$type = $this->input->get ( 'type' );
			$database = $this->getDbData ( $type );
			
			//繁体访问这个
		/* 	if(empty($database)){
			    $database = $this->load->database ( 'fanti', TRUE );
			} */
		
			$sql = "select * from g_serverid2ip order by id desc";			
		
			$query = $database->query ( $sql );		
		
		
			$data = array ();
			if ($query) {
				$data = $query->result_array ();
			}
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'status' => 'fail' 
				] );
		} else {
			
			$this->body = 'SystemAnalysis/getserver';
			$this->layout ();
		}
	}
	
	/**
	 * 获取用户信息
	 */
	public function getUserInfo() {
		if (parent::isAjax ()) {
			$where ['userid'] = $this->input->get ( 'userid' );
			$where ['serverids'] = $this->input->get ( 'server_id' );
			
			$this->load->model ( 'System_analysis_model' );
			$data = $this->System_analysis_model->getUserInfo ( $where );
			
			foreach ( $data as &$v ) {
				$v ['last_login_ip'] = long2ip ( $v ['last_login_ip'] );
				$v ['last_login_time'] = date ( 'Ymd', $v ['last_login_time'] );
			}
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'status' => 'fail' 
				] );
		} else {
			
			$this->body = 'SystemAnalysis/getUserInfo';
			$this->layout ();
		}
	}
	public function getDbData($preserver) {	
		switch ($preserver) {
			case 8 :
				$database = $this->load->database ( 'hun1', true );
				break;
			case 6 :
				$database = $this->load->database ( 'yinghe', true );
				break;
			case 3 :
				$database = $this->load->database ( 'yingyongbao', true );
				break;
			case 15 :
				$database = $this->load->database ( 'p8android', true );
				break;
			case 5 :
				$database = $this->load->database ( 'p8ios1', true );
				break;
		}
		return $database;
	}
	public function Props() {
		$this->body = 'SystemAnalysis/props';
		$this->layout ();
	}
	public function Copy() {
		$this->body = 'SystemAnalysis/copy';
		$this->layout ();
	}
	public function Task() {
		// $this->body = 'LostAnalysis/lev_lost';
		$this->layout ();
	}
	public function Level() {
		$this->body = 'SystemAnalysis/level';
		$this->layout ();
	}
	public function Success() {
		$this->body = 'SystemAnalysis/success';
		$this->layout ();
	}
	public function Emoney() {
		$this->body = 'SystemAnalysis/emoney';
		$this->layout ();
	}
	
	/**
	 * 升级历程
	 */
	public function Upgrade() {
		$this->body = 'SystemAnalysis/upgrade';
		$this->layout ();
	}
	private function loadModel() {
		$this->load->model ( 'system_analysis_model' );
	}
	public function getEmoney() {
		$this->getData ( 'emoney_analysis' );
	}
	public function getProps() {
		$this->getData ( 'props_analysis' );
	}
	public function getCopy() {
		$this->getData ( 'copy_analysis' );
	}
	public function getUpgrade() {
		$this->getSuccessAndLevel ( 'upgrade_analysis' );
	}
	private function getSuccessAndLevel($action) {
		$this->loadModel ();
		$lev = ( int ) $this->input->get ( 'item_type' );
		$lev2 = ( int ) $this->input->get ( 'item_type2' );
		$accountid = $this->input->get ( 'accountid' );
		if (! $lev && ! $accountid)
			exit ( json_encode ( [ 
					'status' => 'fail' 
			] ) );
		if (! $lev2)
			$lev2 = 10;
		
		$serverid = ( int ) $this->input->get ( 'server_id' );
		$ret = $this->system_analysis_model->$action ( $this->appid, $lev, $lev2, $serverid, $accountid );
		if (! empty ( $ret ))
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $ret 
			] );
		else
			echo json_encode ( [ 
					'status' => 'fail' 
			] );
	}
	
	/**
	 * 成就进度
	 */
	public function getSuccess() {
		$this->getSuccessAndLevel ( 'success_analysis' );
	}
	
	/**
	 * 关卡进度
	 */
	public function getLevel() {
		$this->getSuccessAndLevel ( 'level_analysis' );
	}
	public function view_copy_lev() {
		$is_success = ( int ) $this->input->get ( 'is_success' );
		$copy_type = ( int ) $this->input->get ( 'type' );
		$this->loadModel ();
		// ($appid, $copy_type, $is_success=0)
		$data = $this->system_analysis_model->copy_player_lev ( $this->appid, $copy_type, $is_success );
		$this->data ['title'] = $is_success == 1 ? '副本通关玩家等级' : '副本失败时玩家的等级';
		$this->data ['copy_type'] = $copy_type;
		$this->data ['copy_title'] = $this->input->get ( 'title' );
		$this->data ['data'] = $data;
		$this->body = 'SystemAnalysis/view_copy_lev';
		$this->layout ();
	}
	private function getData($action) {
		$this->loadModel ();
		$date1 = $this->input->get ( 'date1' ) ? $this->input->get ( 'date1', true ) : date ( 'Y-m-d', strtotime ( '-7 days' ) );
		$date2 = $this->input->get ( 'date2' ) ? $this->input->get ( 'date2', true ) : date ( 'Y-m-d' );
		$date1 = date ( 'Ymd', strtotime ( $date1 ) );
		$date2 = date ( 'Ymd', strtotime ( $date2 ) );
		$serverid = $this->input->get ( 'server_id' );
		$channel = $this->input->get ( 'channel_id' );
		$item_type = $this->input->get ( 'item_type' );
		$item_type2 = $this->input->get ( 'item_type2' );
		$this->system_analysis_model->init ( $this->appid, $date1, $date2, 0, $serverid, $channel, false );
		$ret = $this->system_analysis_model->$action ( $item_type, $item_type2 );
		if (! empty ( $ret ))
			echo json_encode ( [ 
					'status' => 'ok',
					'data' => $ret 
			] );
		else
			echo json_encode ( [ 
					'status' => 'fail' 
			] );
	}
	
	
	/*
	 * cross_server   跨服操作配置
	 */
	public function crossServer() {
		if (parent::isAjax ()) {
			
			$sql = "select * from cross_server order by id desc";
			$this->db_sdk = $this->load->database ( 'sdk', TRUE );
			
			$query = $this->db_sdk->query ( $sql );
			
			$data = array ();
			if ($query) {
				$data = $query->result_array ();
			}
			
			foreach ( $data as $k => &$v ) {
				
				$v ['openweekendtime'] = date ( 'Ymd H:i:s', $v ['openweekendtime'] );
				$v ['created_at'] = date ( 'Ymd H:i:s', $v ['created_at'] );
			}
			if (! empty ( $data ))
				echo json_encode ( [ 
						'status' => 'ok',
						'data' => $data 
				] );
			else
				echo json_encode ( [ 
						'status' => 'fail' 
				] );
		} else {
			
			$this->body = 'SystemAnalysis/crossServer';
			$this->layout ();
		}
	}
	
}
