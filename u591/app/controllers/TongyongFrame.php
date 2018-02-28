<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/27
 * Time: 14:50
 */
class TongyongFrame extends CI_Controller {
	private $sdk;
	public function __construct()
	{
		parent::__construct();
		$this->sdk =  $this->load->database('sdk',true);
	}
	public function  sql_edit(){
		$sqlid = $this->input->get ( 'sqlid' );
		$sqlid = addslashes ( $sqlid );
		if ($this::isAjax ()) {
			$fields_name = $this->input->get ( 'fields_name' );
			$fields = $this->input->get ( 'fields' );
			$use_sql = $this->input->get ( 'use_sql' );
			$where_info = $this->input->get ( 'where_info' );
			$exec_sort = $this->input->get ( 'exec_sort' );
			$tongyong_id = $this->input->get ( 'tongyong_id' );
			if(!$fields_name){
				exit('1');
			}
			if(!$fields){
				exit('1');
			}
			if(!$use_sql){
				exit('1');
			}
			$fields = addslashes ( $fields );
			$fields_name = addslashes ( $fields_name );
			$exec_sort = addslashes ( $exec_sort );
			$tongyong_id = addslashes ( $tongyong_id );
			if($sqlid){ //更新
				$sql = "update tongyong_detail set fields_name='$fields_name',fields='$fields',use_sql='$use_sql',where_info=\"$where_info\",
				exec_sort='$exec_sort',tongyong_id='$tongyong_id' where id={$sqlid}";
			}else{
				$sql = "insert into tongyong_detail(fields_name,fields,use_sql,where_info,exec_sort,tongyong_id) 
				values('$fields_name','$fields','$use_sql',\"$where_info\",'$exec_sort','$tongyong_id')";
			}
			$query = $this->sdk->query($sql);
			if(!$query){
				exit('1');
			}
			exit('0');
		} else {
			$sql = "select * from tongyong";
			$query = $this->sdk->query($sql);
			$data = array ();
			if($query){
				$data = $query->result_array();
			}
			$this->show = $data;
			if($sqlid){
				$sql = "select* from tongyong_detail where id={$sqlid} limit 1";
				$query = $this->sdk->query($sql);
				$data = array ();
				if($query){
					$data = $query->result_array();
					$this->data = $data[0];
				}
			}
			$this->body = 'Tongyong/sql_edit';
			$this->load->view ( $this->body );
		}
	}
	public function  show_edit(){
		if ($this::isAjax ()) {
			$name = $this->input->get ( 'name' );
			if(!$name){
				exit('1');
			}
			$name = addslashes ( $name );
			$sql = "insert into tongyong(name) values('$name')";
			$query = $this->sdk->query($sql);
			if(!$query){
				exit('1');
			}
			exit('0');
		} else {
			$this->body = 'Tongyong/show_edit';
			$this->load->view ( $this->body );
		}
	}
	
	public static function isAjax() {
		$r = isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) ? strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) : '';
		return $r === 'xmlhttprequest';
	}
}