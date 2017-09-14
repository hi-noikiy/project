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
class VipUser extends MY_Controller {
	public $vipusers = '';
	public function __construct() {
		parent::__construct ();
		$this->load->database();
		$sql = "select accounts from users_vips where user_id={$this->userData->id}";
		$query = $this->db->query($sql);
		if ($query) {
			$result = $query->result_array();
			$this->vipusers =  $result[0]['accounts'];
		}
	}
	/**
	 * 获取用户信息
	 */
	public function getUserInfo() {
		if (parent::isAjax ()) {
			$sdk = $this->load->database('sdk',true);
			$sql = "select a.*,sum(b.money) summoney from u_last_login a,u_paylog b where a.accountid=b.accountid and a.serverid=b.serverid and a.viplev=12 group by a.accountid,a.serverid";
			$query = $sdk->query($sql);
			
		} else {
			
			$this->body = 'VipUser/getUserInfo';
			$this->layout ();
		}
	}
    

}
