<?php
class Icon{
	private $table = 'u_icon_map';
	private $db ;
	public function __construct(){
		$this->db = setConn();
	}
	public function index($data = array(),$order=' id desc',$limit = 20){
		$where = '';
		if(isset($data['icon'])){
			$where .=" and icon like '{$data['icon']}%' ";
		}
		$sql = "select * from $this->table where 1=1 $where order by $order";
		$query = $this->db->query($sql);
		$datas = array();
		while ( $row = $query->fetch_assoc() ) {
			$datas[] = $row;
		}
		exit(return_json(array('status'=>0, 'data'=>$datas)));
	}
	
	
}