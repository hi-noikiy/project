<?php
class Task{
	private $table = 'u_task_condition';
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
		$where = '';
		if(isset($data['detail']) && $data['detail']){
			$where .= " and detail like '%{$data['detail']}%'";
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
		$sql = "insert into $this->table(param1,param2,param3,param4,param5,param6,param7,param8,num,detail)
		values('{$data['param1']}','{$data['param2']}','{$data['param3']}','{$data['param4']}','{$data['param5']}','{$data['param6']}','{$data['param7']}','{$data['param8']}',
		'{$data['num']}','{$data['detail']}')";
		if(false == $this->db->query($sql)){
			write_log("../log","task_add_error","$sql, ".mysqli_error($this->db).date("Y-m-d H:i:s")."\r\n");
			exit(return_json(array('status'=>1, 'msg'=>"失败")));
		}
		exit(return_json(array('status'=>0, 'msg'=>"成功")));
	}
	
	public function update($data){
		if(!$data['id']){
			exit(return_json(array('status'=>1, 'msg'=>"参数错误")));
		}
		$sql = "update $this->table set param1='{$data['param1']}',param2='{$data['param2']}',param3='{$data['param3']}',param4='{$data['param4']}',param5='{$data['param5']}'
		,param6='{$data['param6']}',param7='{$data['param7']}',param8='{$data['param8']}',num='{$data['num']}',detail='{$data['detail']}' where id='{$data['id']}'";
		if(false == $this->db->query($sql)){
			write_log("../log","task_update_error","$sql, ".mysqli_error($this->db).date("Y-m-d H:i:s")."\r\n");
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
			write_log("../log","task_delete_error","$sql, ".mysqli_error($this->db).date("Y-m-d H:i:s")."\r\n");
			exit(return_json(array('status'=>1, 'msg'=>"失败")));
		}
		exit(return_json(array('status'=>0, 'msg'=>"成功")));
	}
	
}