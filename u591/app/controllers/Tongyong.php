<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-12-13
 * Time: 下午8:56
 * 实时数据统计
 */
include_once 'MY_Controller.php';
ini_set ( 'memory_limit', '1024M' );
class Tongyong extends MY_Controller {
	private $sdk;
	public function __construct()
	{
		parent::__construct();
		$this->sdk =  $this->load->database('sdk',true);
	}
	/**
	 * 通用
	 * 
	 * @author 王涛 20180124
	 */
	public function  index(){
		if (parent::isAjax ()) {
			$where = $_GET;
			$sqlid = $_GET['beginid'];
			$sql = "select a.* from tongyong_detail a,tongyong b where a.tongyong_id=b.id and a.id=$sqlid limit 1";
			$query = $this->sdk->query($sql);
			$newdata =  array ();
			$next = 0;
			if($query){
				$data = $query->result_array();
				if($data[0]){
					//查询数据
					$newdata['fields_name'] = explode(',', $data[0]['fields_name']);
					$fields = explode(',', $data[0]['fields']);
					foreach ($fields as $v){
 						$field = explode(' ', $v);
 						$fieldss[] = $field[count($field)-1];
					}
					if(strpos($data[0]['use_sql'],'$Ymd')){
						$date = date('Ymd',strtotime($where['date1']));
						$data[0]['use_sql'] = str_replace('$Ymd',$date,$data[0]['use_sql']);
					}
					if(strpos($data[0]['use_sql'],'$Ym')){
						$date = date('Ym',strtotime($where['date1']));
						$data[0]['use_sql'] = str_replace('$Ym',$date,$data[0]['use_sql']);
					}
					$selectSql = "select {$data[0]['fields']} from ".str_replace('$where',$this->whereinfo($where,$data[0]['where_info']),$data[0]['use_sql']);
					$selectQuery = $this->sdk->query($selectSql);
					$selectData = array();
					if($selectQuery){
						$selectData = $selectQuery->result_array();
					}
					$newdata['data'] = $selectData;
					$nextSql = "select id from tongyong_detail where tongyong_id={$data[0]['tongyong_id']} and exec_sort>{$data[0]['exec_sort']} order by exec_sort limit 1";
					$nextQuery = $this->sdk->query($nextSql);
					if($nextQuery){
						$nextData = $nextQuery->result_array();
						isset($nextData[0]['id']) && $next=$nextData[0]['id'];
					}
					$newdata['beginid'] = $next;
					$newdata['field'] = $fieldss;
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $newdata
			] ) );
		} else {
			$id = $_GET['sqlid'];
			$sql = "select a.id,a.use_sql,a.where_info,b.name from tongyong_detail a,tongyong b where a.tongyong_id=b.id and b.id=$id order by exec_sort";
			$query = $this->sdk->query($sql);
			$data = array ();
			if($query){
				$data = $query->result_array();
				if($data){
					$this->data = $this->searchinfo($data);
					$this->data['title'] = $data[0]['name'];
					$this->data['beginid'] = $data[0]['id'];
				}
			}
			$this->data['_request_method'] = 'Tongyong/index';
			$this->body = 'Tongyong/index';
			$this->data['tongyong_search_form']   = $this->load->view('layout/tongyong_search_form', $this->data, true);
			$this->layout ();
		}
	}
	public function  sql(){
		if (parent::isAjax ()) {
			$sql = "select a.*,b.name from tongyong_detail a,tongyong b where a.tongyong_id=b.id";
			$query = $this->sdk->query($sql);
			$data = array ();
			if($query){
				$data = $query->result_array();
				foreach ($data as &$v){
					$v['use_sql'] = "select {$v['fields']} from {$v['use_sql']} ";
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $data
			] ) );
		} else {
			$this->body = 'Tongyong/sql';
			$this->layout ();
		}
	}
	public function  show(){
		if (parent::isAjax ()) {
			$sql = "select * from tongyong";
			$query = $this->sdk->query($sql);
			$data = array ();
			if($query){
				$data = $query->result_array();
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $data
			] ) );
		} else {
			$this->body = 'Tongyong/show';
			$this->layout ();
		}
	}
	
	public function  showindex(){
		if (parent::isAjax ()) {
			$sql = "select * from tongyong";
			$query = $this->sdk->query($sql);
			$data = array ();
			if($query){
				$data = $query->result_array();
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
					'status' => 'ok',
					'data' => $data
			] ) );
		} else {
			$this->body = 'Tongyong/showindex';
			$this->layout ();
		}
	}

	private function searchinfo($data = array()){
		$newdata = array();
		foreach ($data as $v){
			if(strpos($v['use_sql'],'$Y')){
				!isset($newdata['date1']) && $newdata['date1'] = 1;
			}
			$a = explode(';', $v['where_info']);
			foreach ($a as $val){
				$hide = explode(':', $val)[2];
				!isset($newdata[$hide]) && $newdata[$hide] = 1;
			}
		}
		return $newdata;
	}
	
	private function whereinfo($data = array(), $where = ''){
		$where = explode(';', $where);
		$sqlstr = '';
		foreach ($where as $val){
			$whereinfo = explode(':', $val);
			if(empty($data[$whereinfo[2]])) continue;
			if(in_array($whereinfo[1], array('in','not in'))){
				$sqlstr .= ' and '.$whereinfo[0].' '.$whereinfo[1].' ('.$data[$whereinfo[2]].')';
			}else{
				$sqlstr .= ' and '.$whereinfo[0].' '.$whereinfo[1].' "'.$data[$whereinfo[2]].'"';
			}
		}
		return $sqlstr;
	}

}
