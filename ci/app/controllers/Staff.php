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
		$this->body = 'Staff/index';
        $this->layout();
	}
	
	
	public function edit()
	{
		$user_id = $_GET['user_id'];
		$date = strtotime('-1 months');
		$sdk = $this->load->database('sdk',true);
		$sql = "SELECT id,username,serverid FROM u_last_login where viplev=12 and last_login_time>$date";
		$query  = $sdk->query($sql);
		$info = $query->result_array();
		$newarr = $myarr = $otherarr = array();
		foreach ($info as $v){
			$newarr[$v['serverid']][] = $v;
		}
		$this->data['info'] = $newarr;
		
		$sql = "SELECT accounts FROM users_vips where user_id=$user_id";
		$query  = $this->db->query($sql);
		$info = $query->result_array();
		$data = explode();
		foreach ($info[0] as $v){
			$myarr[$v['serverid']][] = $v;
		}
		$this->body = 'Staff/edit';
		$this->layout();
	}
}
