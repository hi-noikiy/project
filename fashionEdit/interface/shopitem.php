<?php
class Shopitem{
	private $table = 'u_shopitem';
	private $db ;
	public function __construct(){
		$this->db = setConn();
	}
	public function find($data){
		if(!$data['id']){
			exit(return_json(array('status'=>1, 'msg'=>"参数错误")));
		}
		$sql = "select * from $this->table where id='{$data['id']}' limit 1";
		$query = $this->db->query($sql);
		$row = array();
		if($query){
			$row = $query->fetch_assoc();
		}
		exit(return_json(array('status'=>0, 'data'=>$row)));
	}
	public function index($data = array(),$order=' id desc',$limit = 20){
		$page = isset($data['page'])?$data['page']:1;
		$start = $limit*($page-1);
		$where = " check_status='{$data['check_status']}'";
		if($data['name']){
			$where .= " and name like '%{$data['name']}%'";
		}
		if($data['icon']){
			$where .= " and icon like '%{$data['icon']}%'";
		}
		if($data['sort']){
			$where .= " and sort ='{$data['sort']}'";
		}
		if($data['sort2']){
			$where .= " and sort2 ='{$data['sort2']}'";
		}
		if($data['season']){
			$where .= " and season ='{$data['season']}'";
		}
		if($data['brand']){
			$where .= " and brand ='{$data['brand']}'";
		}
		//风格
		$fwhere = "";
		if($data['style21']){
			$fwhere .= " or '{$data['style21']}' in (style21,style22,style23)";
		}elseif($data['style1']){
			$fwhere .= " or '{$data['style1']}' in (style1,style2,style3)";
		}
		if($data['style22']){
			$fwhere .= " or '{$data['style22']}' in (style21,style22,style23)";
		}elseif($data['style2']){
			$fwhere .= " or '{$data['style2']}' in (style1,style2,style3)";
		}
		if($fwhere){
			$where .= " and (0 $fwhere)";
		}
		
		//颜色
		$fwhere = "";
		if($data['color1']){
			$fwhere .= " or '{$data['color1']}' in (color1,color2)";
		}
		if($data['color2']){
			$fwhere .= " or '{$data['color2']}' in (color1,color2)";
		}
		if($fwhere){
			$where .= " and (0 $fwhere)";
		}
		if($data['pattern1']){
			$where .= " and '{$data['pattern1']}' in (pattern1,pattern2)";
		}
		if($data['material1']){
			$where .= " and '{$data['material1']}' in (material1,material2)";
		}
		if($data['collar']){
			$where .= " and collar ='{$data['collar']}'";
		}
		if($data['model']){
			$where .= " and model ='{$data['model']}'";
		}
		if($data['pop_element1']){
			$where .= " and '{$data['pop_element1']}' in (pop_element1,pop_element2,pop_element3,pop_element4
			,pop_element5,pop_element6,pop_element7,pop_element8,pop_element9,pop_element10)";
		}
		$csql = "select count(*) c from $this->table where $where";
		$result = $this->db->query($csql);
		$count = $result->fetch_assoc();
		
		$count = $count['c'];
		$allpage = ceil($count/$limit);
		$sql = "select * from $this->table where $where order by $order limit $start,$limit";
		$query = $this->db->query($sql);
		$datas = array();
		while ( $row = $query->fetch_assoc() ) {
			$datas[] = $row;
		}
		exit(return_json(array('status'=>0, 'data'=>$datas,'allpage'=>$allpage,'count'=>$count)));
	}
	
	public function add($data){
		if(!$data){
			exit(return_json(array('status'=>1, 'msg'=>"参数错误")));
		}
		$field = '';
		$show = '';
		foreach ($data as $k=>$v){
			$field .= "$k,";
			$show .= "'$v',";
		}
		$sql = "insert into $this->table(".rtrim($field,',').")
		values(".rtrim($show,',').")";
		if(false == $this->db->query($sql)){
			write_log("../log","icon_add_error","$sql, ".mysqli_error($this->db).date("Y-m-d H:i:s")."\r\n");
			exit(return_json(array('status'=>1, 'msg'=>"失败")));
		}
		exit(return_json(array('status'=>0, 'msg'=>"成功")));
	}
	
	public function update($data){
		if(!$data['id']){
			exit(return_json(array('status'=>1, 'msg'=>"参数错误")));
		}
		$id = $data['id'];
		unset($data['id']);
		$wsql = '';
		foreach ($data as $k=>$v){
			$wsql .= "$k='$v',";
		}
		$sql = "update $this->table set ".rtrim($wsql,',')." where id='$id'";
		if(false == $this->db->query($sql)){
			write_log("../log","icon_update_error","$sql, ".mysqli_error($this->db).date("Y-m-d H:i:s")."\r\n");
			exit(return_json(array('status'=>1, 'msg'=>"失败")));
		}
		exit(return_json(array('status'=>0, 'msg'=>"成功")));
	}
	public function delete($data){
		if(!$data['id']){
			exit(return_json(array('status'=>1, 'msg'=>"参数错误")));
		}
		$sql = "delete from $this->table where id='{$data['id']}'";
		if(false == $this->db->query($sql)){
			write_log("../log","icon_delete_error","$sql, ".mysqli_error($this->db).date("Y-m-d H:i:s")."\r\n");
			exit(return_json(array('status'=>1, 'msg'=>"失败")));
		}
		exit(return_json(array('status'=>0, 'msg'=>"成功")));
	}
	
}