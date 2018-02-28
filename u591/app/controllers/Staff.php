<?php
include 'MY_Controller.php';
class Staff extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
	public function index()
	{
		$sql = "SELECT a.id,email FROM users a,users_groups b,groups c where a.id=b.user_id and b.group_id=c.id and c.name='kefu'";
		$query  = $this->db->query($sql);
		$this->data['info'] = $query->result_array();
		foreach ($this->data['info'] as &$v){
			$sql = "SELECT id,username,serverid FROM users_vips where user_id={$v['id']}";
			$query  = $this->db->query($sql);
			$info = $query->result_array();
			$v['info'] = $info;
			$v['num'] = count($info);
		}
		$this->body = 'Staff/index';
        $this->layout();
	}
	
	
	public function edit()
	{
		$user_id = $_REQUEST['user_id'];
		$sdk = $this->load->database('rootsdk',true);
		if($_POST){
			$accounts = implode(',', $_POST['accounts']);
			if($user_id && $accounts){
				$sql = "SELECT accountid,serverid,userid,username,$user_id as user_id FROM u_last_login where id in($accounts)";
				$query  = $sdk->query($sql);
				$data = $query->result_array();
				$this->insert_batch("users_vips", $data,$this->db);
			}
		}
		$date = strtotime('-1 months');
		$sql = "SELECT id,username,serverid FROM u_last_login where viplev=12 and last_login_time>$date and accountid>=1000";
		$sql .= " and id not in(SELECT a.id FROM u_last_login a,mydb.users_vips b where a.accountid=b.accountid and a.serverid=b.serverid and b.user_id!=$user_id)";
		$query  = $sdk->query($sql);
		$info = $query->result_array();
		$newarr = $myarr  = array();
		
		foreach ($info as $v){
			$newarr[$v['serverid']][] = $v;
		}
		$this->data['info'] = $newarr;
		
		$sql = "SELECT a.id FROM u_last_login a,mydb.users_vips b where a.accountid=b.accountid and a.serverid=b.serverid and b.user_id=$user_id";
		$query  = $sdk->query($sql);
		$data = $query->result_array();
		foreach ($data as $v){
			$myarr[$v['id']] = $v['id'];
		}
		$this->data['myinfo'] = $myarr;
		$this->body = 'Staff/edit';
		$this->layout();
	}
	
	public function del()
	{
		$vipid = $_REQUEST['vipid'];
		$sql = "delete from users_vips where id=$vipid";
		$query  = $this->db->query($sql);
	}
}
