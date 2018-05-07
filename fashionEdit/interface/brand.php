<?php
class Brand{
	private $table = 'u_brand';
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
		$limit = isset($data['limit'])?$data['limit']:$limit;
		$start = $limit*($page-1);
		$where = '';
		if(isset($data['name']) && $data['name']){
			$where .= " and name like '{$data['name']}%'";
		}
		if(isset($data['id'])){
			$where .= " and id='{$data['id']}'";
		}
		$csql = "select count(*) c from $this->table where 1=1 $where";
		$result = $this->db->query($csql);
		$count = $result->fetch_assoc();
		
		$count = $count['c'];
		$allpage = ceil($count/$limit);
		$sql = "select * from $this->table where 1=1 $where order by $order limit $start,$limit";
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
		$filepath = '../upload/brand/';
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $data['logo'], $result)){
			$type = $result[2];
			if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
				$name = 'logo/'.date('YmdHis').rand(100,999).'.'.$type;
				$new_file = $filepath.$name;
				if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $data['logo'])))){
					$data['logo'] = $name;
				}else{
					$data['logo'] = '';
				}
			}
		}
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $data['photo'], $result)){
			$type = $result[2];
			if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
				$name = 'photo/'.date('YmdHis').rand(100,999).'.'.$type;
				$new_file = $filepath.$name;
				if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $data['photo'])))){
					$data['photo'] = $name;
				}else{
					$data['photo'] = '';
				}
			}
		}
		
		$sql = "insert into $this->table(name,shorthand,logo,photo,website,shop,describe1)
		values('{$data['name']}','{$data['shorthand']}','{$data['logo']}','{$data['photo']}','{$data['website']}','{$data['shop']}','{$data['describe1']}')";
		if(false == $this->db->query($sql)){
			write_log("../log","brand_add_error","$sql, ".mysqli_error($this->db).date("Y-m-d H:i:s")."\r\n");
			exit(return_json(array('status'=>1, 'msg'=>"失败")));
		}
		exit(return_json(array('status'=>0, 'msg'=>"成功")));
	}
	
	public function update($data){
		if(!$data['id']){
			exit(return_json(array('status'=>1, 'msg'=>"参数错误")));
		}
		$filepath = '../upload/brand/';
		$upsql = '';
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $data['logo'], $result)){
			$type = $result[2];
			if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
				$name = 'logo/'.date('YmdHis').rand(100,999).'.'.$type;
				$new_file = $filepath.$name;
				if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $data['logo'])))){
					$upsql .= ",logo='$name'";
				}
			}
		}
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $data['photo'], $result)){
			$type = $result[2];
			if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
				$name = 'photo/'.date('YmdHis').rand(100,999).'.'.$type;
				$new_file = $filepath.$name;
				if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $data['photo'])))){
					$upsql .= ",photo='$name'";
				}
			}
		}
		$sql = "update $this->table set name='{$data['name']}',shorthand='{$data['shorthand']}',website='{$data['website']}',shop='{$data['shop']}',describe1='{$data['describe1']}'"
				.$upsql." where id='{$data['id']}'";
		if(false == $this->db->query($sql)){
			write_log("../log","brand_update_error","$sql, ".mysqli_error($this->db).date("Y-m-d H:i:s")."\r\n");
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
			write_log("../log","brand_delete_error","$sql, ".mysqli_error($this->db).date("Y-m-d H:i:s")."\r\n");
			exit(return_json(array('status'=>1, 'msg'=>"失败")));
		}
		exit(return_json(array('status'=>0, 'msg'=>"成功")));
	}
	
	
}