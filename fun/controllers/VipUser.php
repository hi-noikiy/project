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
	public function __construct() {
		parent::__construct ();
	}
	function https_post($data) {
		$url = include APPPATH .'/config/ts.php'; //商店类型字典
		$url .= 'guanwang/gamemail.php';
		$str = '';
		if ($data) {
			foreach ( $data as $key => $value ) {
				$str .= $key . "=" . $value . "&";
			}
		}
		$curl = curl_init ( $url ); // 启动一个CURL会话
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
		// curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
		curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
		if ($str) {
			curl_setopt ( $curl, CURLOPT_POSTFIELDS, $str ); // Post提交的数据包
		}
		curl_setopt ( $curl, CURLOPT_TIMEOUT, 5 ); // 设置超时限制防止死循环
		// curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec ($curl);
		curl_close ($curl);
		return $tmpInfo;
	}
	/**
	 * 反馈内容
	 */
	public function gamemail() {
		$id = $this->input->get('id');
		$message = $this->input->get('message');
		if(!$id || !$message){
			exit(json_encode(['status'=>'1','msg'=>'参数错误']));
		}
		$sdk = $this->load->database('sdk',true);
		$sql = "SELECT serverid,userid from u_bugreport where id='$id' limit 1";
		$query = $sdk->query($sql);
		if ($query) {
			$output = $query->result_array();
			if(isset($output[0])){
				$data['serverid'] = $output[0]['serverid'];
				$data['playerid'] = $output[0]['userid'];
				$data['message'] = $message;
				$appKey = '0dbddcc74ed6e1a3c3b9708ec32d0532';
				ksort($data);
				$md5str = http_build_query($data).$appKey;
				$data['sign'] = md5($md5str);
				$result = $this->https_post($data);
				if(!$result){
					exit(json_encode(['status'=>'1','msg'=>'发送超时']));
				}
				$resultdecode = json_decode($result,true);
				if($resultdecode['status'] == '0'){
					$sql = "update u_bugreport set status = '$message' where id='$id'";
					$query = $sdk->query($sql);
				}
				exit($result);
			}
		}
		exit(json_encode(['status'=>'1','msg'=>'id不存在']));
	}
	/**
	 * 获取用户信息
	 */
	public function getUserInfo() {
		$sdk = $this->load->database('sdk',true);
		$user_id = $this->userData->id;
		$sql = "SELECT a.* FROM u_last_login a,mydb.users_vips b where a.accountid=b.accountid and a.serverid=b.serverid and b.user_id=$user_id";
		$query = $sdk->query($sql);
		if ($query) {
			$result = $query->result_array();
			if($result){
				$btime = strtotime(date('Ymd'));
				$etime = $btime+24*60*60-1;
				foreach ($result as &$v){
					$v['last_login_ip'] = long2ip($v['last_login_ip']);
					$v['last_login_time'] = date('Y-m-d H:i',$v['last_login_time']);
					$sql = "select sum(money) sumoney from u_paylog where  accountid={$v['accountid']} and serverid={$v['serverid']} and created_at between $btime and $etime limit 1";
					$query = $sdk->query($sql);
					if($query){
						$myresult = $query->result_array();
						$v['curpay'] = $myresult[0]['sumoney']?$myresult[0]['sumoney']:0;
					}
					$v['nearly'] = 0;
					$mybir = strtotime(date('Y').date('md',strtotime($v['birthday'])));
					if($mybir>=time() && $mybir-time()<3*24*60*60){
						$v['nearly'] = 1;
					}
				}
			}
		}

		$this->data['info'] = $result;
		$this->body = 'VipUser/getUserInfo';
		$this->layout ();
	}
	
	/**
	 * 获取用户反馈信息
	 */
	public function backInfo() {
		if(parent::isAjax ()){
			$user_id = $this->userData->id;
			$date1 = $this->input->get('date1') ? $this->input->get('date1', true) : date('Y-m-d', strtotime('-7 days'));
			$date2 = $this->input->get('date2') ?  $this->input->get('date2', true) : date('Y-m-d');
			$date1 = strtotime($date1);
			$date2 = strtotime($date2 . ' 23:59:59');
			$sdk = $this->load->database('rootsdk',true);
			$sql = "SELECT a.* FROM u_bugreport a,mydb.users_vips b where a.accountid=b.accountid and a.serverid=b.serverid and b.user_id=$user_id and a.created_at 
			between $date1 and $date2";
			$query = $sdk->query($sql);
			$output = array();
			if ($query) {
				$output = $query->result_array();
				foreach($output as $key=>$item) {
					$output[$key]['date'] = date('Y-m-d H:i:s', $item['created_at']);
				}
			}
			
			if (!empty($output)) echo json_encode(['status'=>'ok', 'data'=>$output]);
			else echo json_encode(['status'=>'fail']);
				
		}else{
			$this->data['hide_channel_list'] = true;
			$this->data['hide_server_list'] = true;
			$this->body = 'VipUser/backinfo';
			$this->layout ();
		}
	}

}
