<?php
class Menu{
	private $table = 'u_menu';
	private $db ;
	public function __construct(){
		$this->db = setConn();
	}
	
	public function index($data = array()){
		$where = '';
		if(isset($data['far_id'])){
			$where .= " and far_id='{$data['far_id']}'";
		}
		$sql = "select id,name,far_id from $this->table where 1=1 $where";
		$query = $this->db->query($sql);
		$datas = array();
		while ( $row = $query->fetch_assoc() ) {
			$datas[] = $row;
		}
		exit(return_json(array('status'=>0, 'data'=>$datas)));
	}
	public function find($data){
		if(!$data['id']){
			exit(return_json(array('status'=>1, 'msg'=>"参数错误")));
		}
		$sql = "select id,layer,far_id from $this->table where id='{$data['id']}' limit 1";
		$query = $this->db->query($sql);
		if(!$query){
			exit(return_json(array('status'=>1, 'msg'=>'数据错误')));
		}
		$datas = array();
		$row = $query->fetch_assoc();
		if(!$row){
			exit(return_json(array('status'=>2)));
		}
		$datas[$row['layer']] = $row;
		$layer = $row['layer'];
		while($layer > 0){
			$layer--;
			$sql = "select id,far_id,layer from $this->table where id='{$row['far_id']}' limit 1";
			$query = $this->db->query($sql);
			$row = $query->fetch_assoc();
			$datas[$layer] = $row;
		}
		exit(return_json(array('status'=>0, 'data'=>$datas)));
	}
	
}