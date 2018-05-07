<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/27
 * Time: 14:50
 */

defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit', '1024M');
define('LOG_PATH', '/data/log/site/queue');
include APPPATH . 'libraries/HttpSQSClient.php';
include APPPATH . 'config/item_level.php';
class RunQueue Extends CI_Controller
{
    protected $json;//接收到的数据
    protected $data;//插入的数据
    protected $data_multi;//插入的数据，多维数组
    protected $data_old;//原始数据

    const ERR6 = 4006;
    const ERR7 = 4007;
    protected $errs = [
        4006=>['errcode'=>4006,'errmsg'=>'create fail'],
        4007=>['errcode'=>4007,'errmsg'=>'update fail'],
    ];

    private $auth_conf = [
        10001=>'9400a745d4d346386749a069305fee6a',
        10002=>'ce23a805d28aaf5e576d4cebe1fbf8e1',
    ];

    private $white_list = [
        'AccessToken',
        'GetRegisterProcess',
        'Online',
    ];

    const SERVER_PLAYER_ACTIVE      = 1;
    const SERVER_PLAYING_METHOD     = 2;
    const SERVER_COMMON_CURRENCY    = 3;
    const SERVER_ELF_STARLEV        = 4;
    const SERVER_LEVEL_DIFFICULTY   = 5;
    const SERVER_PHOTO_LEVEL        = 6;
    private $_timestamp=0;
    private $server_data_tbl = [
        self::SERVER_PLAYER_ACTIVE      => 'u_player_active',//玩家活跃
        self::SERVER_PLAYING_METHOD     => 'u_playing_method',//玩法次数统计
        self::SERVER_COMMON_CURRENCY    => 'u_common_currency',//通用货币获取消耗
        self::SERVER_ELF_STARLEV        => 'u_elf_starlev',//精灵星级&关卡统计
        self::SERVER_LEVEL_DIFFICULTY   => 'u_level_difficulty',//关卡难易程度统计
        self::SERVER_PHOTO_LEVEL        => 'u_photo_level',//图鉴
    ];
    /**
     * access_token 这个参数改为： appid_MD5（每个app分配一个key + 提交参数data ）,下划线分隔
     *
     * @return bool
     */
    private function access_verify($verify_code, $data)
    {
        //$data         = $_POST['data'];
        if (!$verify_code) return ['appid'=>0, 'errcode'=>0];
        if (strpos($verify_code, '_')===false)  {
            $appid = substr($verify_code,0, 5);
            return ['appid'=>$appid, 'errcode'=>0];
        }
        list($appid, $token) = explode('_', $verify_code);
//        if ($token != md5($this->auth_conf[$appid] . $data)) return false;
        return ['appid'=>$appid, 'errcode'=>0];
        //MD5（每个app分配一个key + 提交参数data ）
        // md5(app_secret+serverid+accountid+channel);
        //$access_token = md5('10001');
        //$access_token = '10001_ce23a805d28aaf5e576d4cebe1fbf8e1';
    }
    public function token_verify($access_token)
    {
        $this->load->model('access_token_model');
        $ret = $this->access_token_model->check_access_token($access_token);
        return $ret;
    }

    /**
     * Api constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database('sdk');
    }
    /**
     * 单独提取队列
     *
     * @author 王涛 -- 20170310
     */
    public function run_device()
    {
    	$queue_name = 'api_queue_DeviceActive';
    	$this->config->load('api');
    	parent::log("start api_queue_Login", LOG_PATH . '/start.log');
    	$client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
    	do {
    		$this->data = $this->data_multi = '';
    		//$this->db->reconnect();
    		$queue_data = $client->get($queue_name);
    		if($queue_data == 'HTTPSQS_GET_END') {
    			parent::BetterLog('queue',"empty queue");
    			sleep(10);
    			continue;
    		}
    		parse_str($queue_data, $raw_data);
    		//print_r($raw_data);
    		$access_token = $raw_data['access_token'];
    		$request_method = $raw_data['request_method'];
    		$timestamp = isset($raw_data['timestamp']) && $raw_data['timestamp']>0 ? $raw_data['timestamp'] : time();
    		$this->_timestamp = $timestamp;
    		$_data = $raw_data['request_data'];
    		$_data = str_replace(["\n", " "], ['','+'],$_data);
    		//替换空格为+,去掉\n
    		$ret = $this->access_verify($access_token, $_data);
    		//            if (strpos($access_token,'_')===false) {
    		//                $ret = $this->token_verify($access_token);
    		//            }
    		//            else {
    		//                $ret = $this->access_verify($access_token, $_data);
    		//            }
    		if ($ret===false) {
    			echo "[error]access token error\n";
    			parent::log("[error]access token error,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		if (!isset($ret['appid'])) {
    			echo "[error]appid not set\n";
    			parent::log("[error]appid not set,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		$this->json = base64_decode($_data);
    		$this->data = json_decode($this->json, true);
    		if (!$this->data) {
    			parent::log("{$request_method}数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象", LOG_PATH . '/queue_error.log');
    			continue;
    		}
    		if (isset($this->data[0]) && is_array($this->data[0])) {
    			foreach ($this->data as $k=>$dt) {
    				$this->data_multi[$k]      = $dt;
    				$this->data_multi[$k]['appid']      = $ret['appid'];
    				$this->data_multi[$k]['created_at'] = time();
    				isset($dt['created_at']) && $this->data_multi[$k]['created_at'] = $dt['created_at'];
    				if ($request_method=='DayOnline') {
    					$this->data_multi[$k]['online_date'] = date('Ymd',  $timestamp);
    				}
    				elseif ($request_method=='Login') {
    					$this->data_multi[$k]['logindate']  = date('Ymd',  $timestamp);
    				}
    			}
    		}
    		else {
    			$this->data['appid']        = $ret['appid'];
    			$this->data['created_at']   = $timestamp;
    		}
    		echo date('Y-m-d H:i:s', $timestamp) . ':request_method:'.$request_method,"\n";
    		$this->$request_method();
    		usleep(1000);
    		//usleep(10000);
    	} while(true);
    
    }
    /**
     * 单独提取队列
     *
     * @author 王涛 -- 20170310
     */
    public function run_login()
    {
    	$queue_name = 'api_queue_Login';
    	$this->config->load('api');
    	parent::log("start api_queue_Login", LOG_PATH . '/start.log');
    	$client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
    	do {
    		$this->data = $this->data_multi = '';
    		//$this->db->reconnect();
    		$queue_data = $client->get($queue_name);
    		$this->data_old = $queue_data;
    		if($queue_data == 'HTTPSQS_GET_END') {
    			parent::BetterLog('queue',"empty queue");
    			sleep(10);
    			continue;
    		}
    		parse_str($queue_data, $raw_data);
    		//print_r($raw_data);
    		$access_token = $raw_data['access_token'];
    		$request_method = $raw_data['request_method'];
    		$timestamp = isset($raw_data['timestamp']) && $raw_data['timestamp']>0 ? $raw_data['timestamp'] : time();
    		$this->_timestamp = $timestamp;
    		$_data = $raw_data['request_data'];
    		$_data = str_replace(["\n", " "], ['','+'],$_data);
    		//替换空格为+,去掉\n
    		$ret = $this->access_verify($access_token, $_data);
    		//            if (strpos($access_token,'_')===false) {
    		//                $ret = $this->token_verify($access_token);
    		//            }
    		//            else {
    		//                $ret = $this->access_verify($access_token, $_data);
    		//            }
    		if ($ret===false) {
    			echo "[error]access token error\n";
    			parent::log("[error]access token error,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		if (!isset($ret['appid'])) {
    			echo "[error]appid not set\n";
    			parent::log("[error]appid not set,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		$this->json = base64_decode($_data);
    		$this->data = json_decode($this->json, true);
    		if (!$this->data) {
    			parent::log("{$request_method}数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象", LOG_PATH . '/queue_error.log');
    			continue;
    		}
    		if (isset($this->data[0]) && is_array($this->data[0])) {
    			foreach ($this->data as $k=>$dt) {
    				$this->data_multi[$k]      = $dt;
    				$this->data_multi[$k]['appid']      = $ret['appid'];
    				$this->data_multi[$k]['created_at'] = time();
    				if ($request_method=='DayOnline') {
    					$this->data_multi[$k]['online_date'] = date('Ymd',  $timestamp);
    				}
    				elseif ($request_method=='Login') {
    					$this->data_multi[$k]['logindate']  = date('Ymd',  $timestamp);
    				}
    			}
    		}
    		else {
    			$this->data['appid']        = $ret['appid'];
    			$this->data['created_at']   = $timestamp;
    		}
    		echo date('Y-m-d H:i:s', $timestamp) . ':request_method:'.$request_method,"\n";
    		$this->$request_method();
    		usleep(1000);
    		//usleep(10000);
    	} while(true);
    
    }
    /**
     * 单独提取队列
     *
     * @author 王涛 -- 20170310
     */
    public function run_role()
    {
    	$queue_name = 'api_queue_CreateRole';
    	$this->config->load('api');
    	parent::log("start api_queue_Login", LOG_PATH . '/start.log');
    	$client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
    	do {
    		$this->data = $this->data_multi = '';
    		//$this->db->reconnect();
    		$queue_data = $client->get($queue_name);
    		if($queue_data == 'HTTPSQS_GET_END') {
    			parent::BetterLog('queue',"empty queue");
    			sleep(10);
    			continue;
    		}
    		parse_str($queue_data, $raw_data);
    		//print_r($raw_data);
    		$access_token = $raw_data['access_token'];
    		$request_method = $raw_data['request_method'];
    		$timestamp = isset($raw_data['timestamp']) && $raw_data['timestamp']>0 ? $raw_data['timestamp'] : time();
    		$this->_timestamp = $timestamp;
    		$_data = $raw_data['request_data'];
    		$_data = str_replace(["\n", " "], ['','+'],$_data);
    		//替换空格为+,去掉\n
    		$ret = $this->access_verify($access_token, $_data);
    		//            if (strpos($access_token,'_')===false) {
    		//                $ret = $this->token_verify($access_token);
    		//            }
    		//            else {
    		//                $ret = $this->access_verify($access_token, $_data);
    		//            }
    		if ($ret===false) {
    			echo "[error]access token error\n";
    			parent::log("[error]access token error,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		if (!isset($ret['appid'])) {
    			echo "[error]appid not set\n";
    			parent::log("[error]appid not set,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		$this->json = base64_decode($_data);
    		$this->data = json_decode($this->json, true);
    		if (!$this->data) {
    			parent::log("{$request_method}数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象", LOG_PATH . '/queue_error.log');
    			continue;
    		}
    		if (isset($this->data[0]) && is_array($this->data[0])) {
    			foreach ($this->data as $k=>$dt) {
    				$this->data_multi[$k]      = $dt;
    				$this->data_multi[$k]['appid']      = $ret['appid'];
    				$this->data_multi[$k]['created_at'] = time();
    				if ($request_method=='DayOnline') {
    					$this->data_multi[$k]['online_date'] = date('Ymd',  $timestamp);
    				}
    				elseif ($request_method=='Login') {
    					$this->data_multi[$k]['logindate']  = date('Ymd',  $timestamp);
    				}
    			}
    		}
    		else {
    			$this->data['appid']        = $ret['appid'];
    			$this->data['created_at']   = $timestamp;
    		}
    		echo date('Y-m-d H:i:s', $timestamp) . ':request_method:'.$request_method,"\n";
    		$this->$request_method();
    		usleep(1000);
    		//usleep(10000);
    	} while(true);
    
    }
    /**
     * 单独提取队列
     *
     * @author 王涛 -- 20170310
     */
    public function run_bug()
    {
    	$queue_name = 'api_queue_BugReport';
    	$this->config->load('api');
    	parent::log("start api_queue_Login", LOG_PATH . '/start.log');
    	$client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
    	do {
    		$this->data = $this->data_multi = '';
    		//$this->db->reconnect();
    		$queue_data = $client->get($queue_name);
    		if($queue_data == 'HTTPSQS_GET_END') {
    			parent::BetterLog('queue',"empty queue");
    			sleep(10);
    			continue;
    		}
    		parse_str($queue_data, $raw_data);
    		//print_r($raw_data);
    		$access_token = $raw_data['access_token'];
    		$request_method = $raw_data['request_method'];
    		$timestamp = isset($raw_data['timestamp']) && $raw_data['timestamp']>0 ? $raw_data['timestamp'] : time();
    		$this->_timestamp = $timestamp;
    		$_data = $raw_data['request_data'];
    		$_data = str_replace(["\n", " "], ['','+'],$_data);
    		//替换空格为+,去掉\n
    		$ret = $this->access_verify($access_token, $_data);
    		//            if (strpos($access_token,'_')===false) {
    		//                $ret = $this->token_verify($access_token);
    		//            }
    		//            else {
    		//                $ret = $this->access_verify($access_token, $_data);
    		//            }
    		if ($ret===false) {
    			echo "[error]access token error\n";
    			parent::log("[error]access token error,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		if (!isset($ret['appid'])) {
    			echo "[error]appid not set\n";
    			parent::log("[error]appid not set,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		$this->json = base64_decode($_data);
    		$this->data = json_decode($this->json, true);
    		if (!$this->data) {
    			parent::log("{$request_method}数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象", LOG_PATH . '/queue_error.log');
    			continue;
    		}
    		if (isset($this->data[0]) && is_array($this->data[0])) {
    			foreach ($this->data as $k=>$dt) {
    				$this->data_multi[$k]      = $dt;
    				$this->data_multi[$k]['appid']      = $ret['appid'];
    				$this->data_multi[$k]['created_at'] = time();
    				if ($request_method=='DayOnline') {
    					$this->data_multi[$k]['online_date'] = date('Ymd',  $timestamp);
    				}
    				elseif ($request_method=='Login') {
    					$this->data_multi[$k]['logindate']  = date('Ymd',  $timestamp);
    				}
    			}
    		}
    		else {
    			$this->data['appid']        = $ret['appid'];
    			$this->data['created_at']   = $timestamp;
    		}
    		echo date('Y-m-d H:i:s', $timestamp) . ':request_method:'.$request_method,"\n";
    		$this->$request_method();
    		usleep(1000);
    		//usleep(10000);
    	} while(true);
    
    }
    /**
     * 单独提取队列
     *
     * @author 王涛 -- 20170310
     */
    public function run_register()
    {
    	$queue_name = 'api_queue_Register';
    	$this->config->load('api');
    	parent::log("start api_queue_Register", LOG_PATH . '/start.log');
    	$client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
    	do {
    		$this->data = $this->data_multi = '';
    		//$this->db->reconnect();
    		$queue_data = $client->get($queue_name);
    		$this->data_old = $queue_data;
    		if($queue_data == 'HTTPSQS_GET_END') {
    			parent::BetterLog('queue',"empty queue");
    			sleep(10);
    			continue;
    		}
    		parse_str($queue_data, $raw_data);
    		//print_r($raw_data);
    		$access_token = $raw_data['access_token'];
    		$request_method = $raw_data['request_method'];
    		$timestamp = isset($raw_data['timestamp']) && $raw_data['timestamp']>0 ? $raw_data['timestamp'] : time();
    		$this->_timestamp = $timestamp;
    		$_data = $raw_data['request_data'];
    		$_data = str_replace(["\n", " "], ['','+'],$_data);
    		//替换空格为+,去掉\n
    		$ret = $this->access_verify($access_token, $_data);
    		//            if (strpos($access_token,'_')===false) {
    		//                $ret = $this->token_verify($access_token);
    		//            }
    		//            else {
    		//                $ret = $this->access_verify($access_token, $_data);
    		//            }
    		if ($ret===false) {
    			echo "[error]access token error\n";
    			parent::log("[error]access token error,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		if (!isset($ret['appid'])) {
    			echo "[error]appid not set\n";
    			parent::log("[error]appid not set,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		$this->json = base64_decode($_data);
    		$this->data = json_decode($this->json, true);
    		if (!$this->data) {
    			parent::log("{$request_method}数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象", LOG_PATH . '/queue_error.log');
    			continue;
    		}
    		if (isset($this->data[0]) && is_array($this->data[0])) {
    			foreach ($this->data as $k=>$dt) {
    				$this->data_multi[$k]      = $dt;
    				$this->data_multi[$k]['appid']      = $ret['appid'];
    				$this->data_multi[$k]['created_at'] = time();
    				if ($request_method=='DayOnline') {
    					$this->data_multi[$k]['online_date'] = date('Ymd',  $timestamp);
    				}
    				elseif ($request_method=='Login') {
    					$this->data_multi[$k]['logindate']  = date('Ymd',  $timestamp);
    				}
    			}
    		}
    		else {
    			$this->data['appid']        = $ret['appid'];
    			$this->data['created_at']   = $timestamp;
    		}
    		echo date('Y-m-d H:i:s', $timestamp) . ':request_method:'.$request_method,"\n";
    		$this->$request_method();
    		usleep(1000);
    		//usleep(10000);
    	} while(true);
    
    }
    /**
     * 单独提取队列
     * 
     * @author 王涛 -- 20170208
     */
    public function run_game()
    {
    	$queue_name = 'api_queue_1';
    	$this->config->load('api');
    	parent::log("start queue", LOG_PATH . '/start.log');
    	$client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
    	do {
    		$this->data = $this->data_multi = '';
    		//$this->db->reconnect();
    		$queue_data = $client->get($queue_name);
    		if($queue_data == 'HTTPSQS_GET_END') {
    			parent::BetterLog('queue',"empty queue");
    			sleep(10);
    			continue;
    		}
    		parse_str($queue_data, $raw_data);
    		//print_r($raw_data);
    		$access_token = $raw_data['access_token'];
    		$request_method = $raw_data['request_method'];
    		$timestamp = isset($raw_data['timestamp']) && $raw_data['timestamp']>0 ? $raw_data['timestamp'] : time();
    		$this->_timestamp = $timestamp;
    		$_data = $raw_data['request_data'];
    		$_data = str_replace(["\n", " "], ['','+'],$_data);
    		//替换空格为+,去掉\n
    		$ret = $this->access_verify($access_token, $_data);
    		//            if (strpos($access_token,'_')===false) {
    		//                $ret = $this->token_verify($access_token);
    		//            }
    		//            else {
    		//                $ret = $this->access_verify($access_token, $_data);
    		//            }
    		if ($ret===false) {
    			echo "[error]access token error\n";
    			parent::log("[error]access token error,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		if (!isset($ret['appid'])) {
    			echo "[error]appid not set\n";
    			parent::log("[error]appid not set,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		$this->json = base64_decode($_data);
    		$this->data = json_decode($this->json, true);
    		if (!$this->data) {
    			parent::log("{$request_method}数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象", LOG_PATH . '/queue_error.log');
    			continue;
    		}
    		if (isset($this->data[0]) && is_array($this->data[0])) {
    			foreach ($this->data as $k=>$dt) {
    				$this->data_multi[$k]      = $dt;
    				$this->data_multi[$k]['appid']      = $ret['appid'];
    				$this->data_multi[$k]['created_at'] = time();
    				if ($request_method=='DayOnline') {
    					$this->data_multi[$k]['online_date'] = date('Ymd',  $timestamp);
    				}
    				elseif ($request_method=='Login') {
    					$this->data_multi[$k]['logindate']  = date('Ymd',  $timestamp);
    				}
    			}
    		}
    		else {
    			$this->data['appid']        = $ret['appid'];
    			$this->data['created_at']   = $timestamp;
    		}
    		echo date('Y-m-d H:i:s', $timestamp) . ':request_method:'.$request_method,"\n";
    		$this->$request_method();
    		usleep(1000);
    		//usleep(10000);
    	} while(true);
    
    }
    public function run()
    {
        $queue_name = 'api_queue';
        $this->config->load('api');
        parent::log("start queue", LOG_PATH . '/start.log');
        $client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
        do {
            $this->data = $this->data_multi = '';
            //$this->db->reconnect();
            $queue_data = $client->get($queue_name);
            if($queue_data == 'HTTPSQS_GET_END') {
                parent::BetterLog('queue',"empty queue");
                sleep(10);
                continue;
            }
            parse_str($queue_data, $raw_data);
            //print_r($raw_data);
            $access_token = $raw_data['access_token'];
            $request_method = $raw_data['request_method'];
            $timestamp = isset($raw_data['timestamp']) && $raw_data['timestamp']>0 ? $raw_data['timestamp'] : time();
            $this->_timestamp = $timestamp;
            $_data = $raw_data['request_data'];
            $_data = str_replace(["\n", " "], ['','+'],$_data);
            //替换空格为+,去掉\n
            $ret = $this->access_verify($access_token, $_data);
//            if (strpos($access_token,'_')===false) {
//                $ret = $this->token_verify($access_token);
//            }
//            else {
//                $ret = $this->access_verify($access_token, $_data);
//            }
            if ($ret===false) {
                echo "[error]access token error\n";
                parent::log("[error]access token error,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
                continue;
            }
            if (!isset($ret['appid'])) {
                echo "[error]appid not set\n";
                parent::log("[error]appid not set,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
                continue;
            }
            $this->json = base64_decode($_data);
            if ($request_method=='clientBug') {
            	$this->json = str_replace(array("\n","\r"),array("~","@"),$this->json);
            	//parent::log($this->json, '/data/log/site/cbug_error.log');
            }
            $this->data = json_decode($this->json, true);
            if (!$this->data) {
                parent::log("{$request_method}数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象", LOG_PATH . '/queue_error.log');
                continue;
            }
            if (isset($this->data[0]) && is_array($this->data[0])) {
                foreach ($this->data as $k=>$dt) {
                    $this->data_multi[$k]      = $dt;
                    $this->data_multi[$k]['appid']      = $ret['appid'];
                    $this->data_multi[$k]['created_at'] = time();
                    if ($request_method=='DayOnline') {
                        $this->data_multi[$k]['online_date'] = date('Ymd',  $timestamp);
                    }
                    elseif ($request_method=='Login') {
                        $this->data_multi[$k]['logindate']  = date('Ymd',  $timestamp);
                    }
                }
            }
            else {
                $this->data['appid']        = $ret['appid'];
                $this->data['created_at']   = $timestamp;
            }
            echo date('Y-m-d H:i:s', $timestamp) . ':request_method:'.$request_method,"\n";
            $this->$request_method();
            usleep(1000);
            //usleep(10000);
        } while(true);

    }
    public function run_copy()
    {
    	$queue_name = 'api_queue_0';
    	$this->config->load('api');
    	parent::log("start queue", LOG_PATH . '/start.log');
    	$client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
    	do {
    		$this->data = $this->data_multi = '';
    		//$this->db->reconnect();
    		$queue_data = $client->get($queue_name);
    		if($queue_data == 'HTTPSQS_GET_END') {
    			parent::BetterLog('queue',"empty queue");
    			sleep(10);
    			continue;
    		}
    		parse_str($queue_data, $raw_data);
    		//print_r($raw_data);
    		$access_token = $raw_data['access_token'];
    		$request_method = $raw_data['request_method'];
    		$timestamp = isset($raw_data['timestamp']) && $raw_data['timestamp']>0 ? $raw_data['timestamp'] : time();
    		$this->_timestamp = $timestamp;
    		$_data = $raw_data['request_data'];
    		$_data = str_replace(["\n", " "], ['','+'],$_data);
    		//替换空格为+,去掉\n
    		$ret = $this->access_verify($access_token, $_data);
    		//            if (strpos($access_token,'_')===false) {
    		//                $ret = $this->token_verify($access_token);
    		//            }
    		//            else {
    		//                $ret = $this->access_verify($access_token, $_data);
    		//            }
    		if ($ret===false) {
    			echo "[error]access token error\n";
    			parent::log("[error]access token error,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		if (!isset($ret['appid'])) {
    			echo "[error]appid not set\n";
    			parent::log("[error]appid not set,raw:$queue_data,decode:" . var_export($raw_data, true), LOG_PATH . '/queue_error_'.date('Ymd').'.log');
    			continue;
    		}
    		$this->json = base64_decode($_data);
    		if ($request_method=='clientBug') {
    			$this->json = str_replace(array("\n","\r"),array("~","@"),$this->json);
    			//parent::log($this->json, '/data/log/site/cbug_error.log');
    		}
    		$this->data = json_decode($this->json, true);
    		if (!$this->data) {
    			parent::log("{$request_method}数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象", LOG_PATH . '/queue_error.log');
    			continue;
    		}
    		if ($request_method == 'index' && $this->data['counttype'] == 101) {
    			parent::BetterLog('api/item101_queue','data:'.json_encode($this->data));
    		}
    		if (isset($this->data[0]) && is_array($this->data[0])) {
    			foreach ($this->data as $k=>$dt) {
    				$this->data_multi[$k]      = $dt;
    				$this->data_multi[$k]['appid']      = $ret['appid'];
    				$this->data_multi[$k]['created_at'] = time();
    				if ($request_method=='DayOnline') {
    					$this->data_multi[$k]['online_date'] = date('Ymd',  $timestamp);
    				}
    				elseif ($request_method=='Login') {
    					$this->data_multi[$k]['logindate']  = date('Ymd',  $timestamp);
    				}
    			}
    		}
    		else {
    			$this->data['appid']        = $ret['appid'];
    			$this->data['created_at']   = $timestamp;
    		}
    		echo date('Y-m-d H:i:s', $timestamp) . ':request_method:'.$request_method,"\n";
    		$this->$request_method();
    		usleep(1000);
    		//usleep(10000);
    	} while(true);
    
    }

    private function updateGameProcess($table_name, $data)
    {
        return $this->db->update( $table_name,
            $data, [
                "accountid"     => $data['accountid'],
                "serverid"   => $data['serverid'],
                'process_index' => $data['process_index']
            ]);
    }
    /**
     * 游戏流程
     */
    public function GameProcess()
    {

        $table_name = 'u_game_process';
        if (!empty($this->data_multi)) {
            foreach ($this->data_multi as $data) {
                $data['created_at']=$data['created_at']?$data['created_at']:time();
            	$Ym = date('Ym',$data['created_at']);
            	$table_name = 'u_game_process_'.$Ym;
                $ret = $this->db->insert($table_name, $data);
                if ($ret!==TRUE ) {
                    $this->updateGameProcess($table_name, $data);
                }
            }
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);
        }
        elseif(!empty($this->data)) {           
       
            $this->data['created_at']= $this->data['created_at']?$this->data['created_at']:time();
         
        	$Ym = date('Ym',$this->data['created_at']);
        	$table_name = 'u_game_process_'.$Ym;
            $ret = $this->db->insert($table_name, $this->data);
            if ($ret!==TRUE) {
                $this->updateGameProcess($table_name, $this->data);
            }
        }        
  
    }
    
    /**
     * 客户端bug记录--未实现
     *
     * @author 王涛 20170206
     */
    public function clientBug()
    {
    	$this->data['logdate']   = date('Ymd',  $this->data['created_at']);
    	$this->data['info']   = str_replace(array("~","@"),array("<br/>","\r"),$this->data['info']);
    	if($this->data['status'] == 1){
    		$this->data['info'] = gzdecode(base64_decode($this->data['info']));
    	}
    	unset($this->data['status']);
    	$this->save('client_bug');
    	
    }
    
    public function Paylog()
    {
    	$this->save('paylog');
    }
    /**
     * 世界对战--未实现
     * 
     * @author 王涛 20170123
     */
    public function worldData()
    {
    	$data = $this->data;
    	$gdata = $udata = $idata = array();

    	$ym = '20'.substr($data['endTime'], 0,4);
    	//$ymd = '20'.substr($data['endTime'], 0,6);
    	$g_table_name = "game_data_$ym";
    	$u_table_name = "game_user_$ym";
    	$us_table_name = "game_user_data_$ym";
    	$i_table_name = "game_user_eudemon_$ym";
    	
    	
    	/*插入比赛数据*/
    	$gdata = array(
    			'createTime' =>	time(),
    			'endTime' =>	$data['endTime'],
    			'type' =>	$data['mode'],
    	);
    	if(isset($data['battleType'])){
    		$gdata['btype'] = $data['battleType'];
    	}
    	if($data['gotype']&&isset($data['Bout'])){ //回合数
    		$gdata['continuous'] = $data['Bout'];
    	}
    	if($data['gameround']){ //第几轮
    		$gdata['gameround'] = $data['gameround'];
    	}

    	$this->db->insert($g_table_name, $gdata);
    	$gameid = $this->db->insert_id();
    	$gudata = array(
    			'gameid'=>$gameid,
    			'endTime' =>	$data['endTime'],
    			'type' =>	$data['mode'],
    			'accountid1' =>	$data['AtkAccoutId'],
    			'userid1' =>	$data['AtkUserId'],
    			'serverid1' =>	$data['AtkServerId'],
    			'name1' =>	$data['AtkName'],
    			'status1' =>$data['winFlag'],
    			'dan1' =>	$data['AtkRank'],
    			'viplevel1' =>	$data['AtkVip'],
    			'level1' =>	$data['AtkLevel'],
    			'accountid2' =>	$data['DefAccoutId'],
    			'userid2' =>	$data['DefUserId'],
    			'serverid2' =>	$data['DefServerId'],
    			'name2' =>	$data['DefName'],
    			'status2' =>$data['winFlag']^1,
    			'dan2' =>	$data['DefRank'],
    			'viplevel2' =>	$data['DefVip'],
    			'level2' =>	$data['DefLevel'],
    	);
    	/*插入比赛玩家1数据*/
    	$udata1 = array(
    			'accountid' =>	$data['AtkAccoutId'],
    			'userid' =>	$data['AtkUserId'],
    			'serverid' =>	$data['AtkServerId'],
    			'name' =>	$data['AtkName'],
    			'status' =>$data['winFlag'],
    			'dan' =>	$data['AtkRank'],
    			'viplevel' =>	$data['AtkVip'],
    			'level' =>	$data['AtkLevel'],
    			'gameid'=>$gameid
    	);
    	if($data['AtkCommunityid']){ //第几轮
    		$udata1['communityid'] = $data['AtkCommunityid'];
    	}
    	$this->db->insert($u_table_name, $udata1);
    	$gameuserid1 = $this->db->insert_id();
    	/*插入比赛玩家1精灵数据*/
    	$num = (empty($data['AtkEudType6'])^1)+(empty($data['AtkEudType5'])^1)+(empty($data['AtkEudType4'])^1)+(empty($data['AtkEudType3'])^1)+
    	(empty($data['AtkEudType2'])^1)+(empty($data['AtkEudType1'])^1);
    	for($t=1;$t<=$num;$t++){
    		$idat = array(
    				'eudemon' =>	$data['AtkEudType'.$t],
    				'status' =>	$data['AtkAlive'.$t],
    				'gameuserid' =>	$gameuserid1
    		);
    		if($data['gotype']&&isset($data['AtkLeftLife'.$t])){
    			$idat['hp'] = $data['AtkLeftLife'.$t];
    			$idat['skills1'] = $data['AtkMagicType'.$t.'_1'];
    			$idat['skills2'] = $data['AtkMagicType'.$t.'_2'];
    			$idat['skills3'] = $data['AtkMagicType'.$t.'_3'];
    			$idat['skills4'] = $data['AtkMagicType'.$t.'_4'];
    			$idat['pp1'] = $data['AtkCurUseTimes'.$t.'_1'];
    			$idat['pp2'] = $data['AtkCurUseTimes'.$t.'_2'];
    			$idat['pp3'] = $data['AtkCurUseTimes'.$t.'_3'];
    			$idat['pp4'] = $data['AtkCurUseTimes'.$t.'_4'];
    			$idat['abilities'] = $data['AtkAbilities'.$t];
    			$idat['fruit'] = $data['AtkFruit'.$t];
    			$idat['equip'] = $data['AtkEquip'.$t];
    			$idat['kidney'] = $data['AtkKidney'.$t];
    		}
    		$idata[] = $idat;
    		
    		$gudata['eudemon1'.$t] = $data['AtkEudType'.$t];
    		$gudata['estatus1'.$t] = $data['AtkAlive'.$t];
    		
    	}
    	//$this->db->insert_batch($i_table_name, $atkdata);
    	/*插入比赛玩家1精灵数据*/
    	/*插入比赛玩家2数据*/
    	$udata2 = array(
    			'accountid' =>	$data['DefAccoutId'],
    			'userid' =>	$data['DefUserId'],
    			'serverid' =>	$data['DefServerId'],
    			'name' =>	$data['DefName'],
    			'status' =>$data['winFlag']^1,
    			'dan' =>	$data['DefRank'],
    			'viplevel' =>	$data['DefVip'],
    			'level' =>	$data['DefLevel'],
    			'gameid'=>$gameid
    	);
    	if($data['DefCommunityid']){ //第几轮
    		$udata2['communityid'] = $data['DefCommunityid'];
    	}
    	$this->db->insert($u_table_name, $udata2);
    	$gameuserid2 = $this->db->insert_id();
    	/*插入比赛玩家2精灵数据*/
    	$num = (empty($data['DefEudType6'])^1)+(empty($data['DefEudType5'])^1)+(empty($data['DefEudType4'])^1)+(empty($data['DefEudType3'])^1)+
    	(empty($data['DefEudType2'])^1)+(empty($data['DefEudType1'])^1);
    	for($t=1;$t<=$num;$t++){
    		$idat = array(
    				'eudemon' =>	$data['DefEudType'.$t],
    				'status' =>	$data['DefAlive'.$t],
    				'gameuserid' =>	$gameuserid2
    		);
    		if($data['gotype']&&isset($data['DefLeftLife'.$t])){
    			$idat['hp'] = $data['DefLeftLife'.$t];
    			$idat['skills1'] = $data['DefMagicType'.$t.'_1'];
    			$idat['skills2'] = $data['DefMagicType'.$t.'_2'];
    			$idat['skills3'] = $data['DefMagicType'.$t.'_3'];
    			$idat['skills4'] = $data['DefMagicType'.$t.'_4'];
    			$idat['pp1'] = $data['DefCurUseTimes'.$t.'_1'];
    			$idat['pp2'] = $data['DefCurUseTimes'.$t.'_2'];
    			$idat['pp3'] = $data['DefCurUseTimes'.$t.'_3'];
    			$idat['pp4'] = $data['DefCurUseTimes'.$t.'_4'];
    			$idat['abilities'] = $data['DefAbilities'.$t];
    			$idat['fruit'] = $data['DefFruit'.$t];
    			$idat['equip'] = $data['DefEquip'.$t];
    			$idat['kidney'] = $data['DefKidney'.$t];
    		}
    		$idata[] = $idat;
    		$gudata['eudemon2'.$t] = $data['DefEudType'.$t];
    		$gudata['estatus2'.$t] = $data['DefAlive'.$t];
    	}
    	$this->db->insert($us_table_name, $gudata);
    	$this->db->insert_batch($i_table_name, $idata);
    	/*插入比赛玩家2精灵数据*/
    	/*插入比赛玩家2数据*/
    	/*插入比赛数据*/
    	unset($idata);
    	echo json_encode($this->db->error());
    }
    public function index()
    {
        if (!empty($this->data_multi)) {
            $dataSave = array();
            $no_multi = false;
            foreach ($this->data_multi as $data) {
                if ($data['typeid']==19) {
                    $no_multi = true;
                }
                $type = str_pad($data['typeid'], 3, '0', STR_PAD_LEFT);
                $table_name = "type_{$type}_{$data['appid']}";
                $dataSave[$table_name][] = $data;
            }
            foreach ($dataSave as $table=>$save_data)
            {
                $ret = $this->db->insert_batch($table, $save_data);
                if ($ret!==TRUE) {
                    log_message('error', $table
                        . "数据写入失败,数据:".$this->json
                        .",msg:".json_encode($this->db->error()));
                }
            }
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);
        }
        elseif(!empty($this->data)) {

            //另一种统计方式
            if($this->data['typeid'] == 18){
            	$data = $this->data;
            	$udata = array();  //用户行为数据
            	$idata = array(); //道具产销数据
            	$ltype = 'get_';
            	$u_table_name = "u_behavior_".date('Ymd',$data['created_at']);
            	$i_table_name = "item_trading_".date('Ymd',$data['created_at']);
            	$udata = array(
            			'accountid' =>	$data['accountid'],
            			'userid' =>	$data['userid'],
            			'serverid' =>	$data['serverid'],
            			'channel' =>	$data['channel'],
            			'created_at' =>	$data['created_at'],
            			'client_time' =>time(),
            			'vip_level' =>	$data['vip_level'],
            			'act_id' =>	$data['counttype'],
            			'param' =>	$data['param'],
            	);
            	if($data['user_level']){
            		$udata['user_level'] = $data['user_level'];
            	}
            	if($data['communityid']){
            		$udata['communityid'] = $data['communityid'];
            	}
            	if($data['communitylevel']){
            		$udata['communitylevel'] = $data['communitylevel'];
            	}
            	if($data['param1']){
            		$udata['param1'] = $data['param1'];
            	}
            	$this->db->insert($u_table_name, $udata);
            	$result = $this->db->error();
            	//parent::BetterLog('api/item_error',json_encode($result));
            	if($result['code'] !=0){
            		parent::BetterLog('item_error',json_encode($result).',data:'.json_encode($data));
            	}
            	//道具产销记录
            	$idata['behavior_id'] = $this->db->insert_id();
            	$idata['table_type'] = $data['typeid'];
            	$idata['created_at'] = $data['created_at'];
            	$istatus = 0;
            	foreach ($data as $k =>$v){
            		if(strpos($k, 'get_') !== false || strpos($k, 'consume_') !== false){
            			if(strpos($k, 'get_') !== false){ //获取
            				$ltype = 'get_';
            				$idata['type'] = 0;
            			}else{ //消耗
            				$ltype = 'consume_';
            				$idata['type'] = 1;
            			}
            			if(strpos($k, 'emoney') !== false){ //钻石
            				$idata['item_id'] = 3;
            				$idata['item_num'] = $v;
            				$this->db->insert($i_table_name, $idata);
            				$istatus++;
            			}elseif(strpos($k, 'money') !== false){
            				$idata['item_id'] = 1;
            				$idata['item_num'] = $v;
            				$this->db->insert($i_table_name, $idata);
            				$istatus++;
            			}elseif(strpos($k, 'tired') !== false){
            				$idata['item_id'] = 2;
            				$idata['item_num'] = $v;
            				$this->db->insert($i_table_name, $idata);
            				$istatus++;
            			}elseif(strpos($k, 'currency_') !== false){
            				$idata['item_id'] = '1'.str_pad(explode('currency_', $k)[1],4,'0',STR_PAD_LEFT);
            				$idata['item_num'] = $v;
            				$this->db->insert($i_table_name, $idata);
            				$istatus++;
            			}elseif(strpos($k, 'item_') !== false){
            				$idata['item_id'] = $v;
            				$idata['item_num'] = $data[$ltype.'num_'.explode('item_', $k)[1]];
            				$this->db->insert($i_table_name, $idata);
            				$istatus++;
            			}
            		}
            		/*$result = $this->db->error();
            		if($result['code'] !==0){
            			parent::BetterLog('item_error',json_encode($result));
            		}*/
            	}
            	/*if($istatus == 0){
            		parent::BetterLog('item_error',json_encode($data));
            	}*/
            }else{
            	$type = str_pad($this->data['typeid'], 3, '0', STR_PAD_LEFT);
            	//print_r($this->data_multi);
            	$table_name = "type_{$type}_{$this->data['appid']}";
            	//print_r($this->data);
            	//print_r($table_name);
            	$this->save($table_name);
            }
        }
        else {
            $ret = ['errcode'=>401,'errmsg'=>'empty msg'];
            $this->set_response($ret);
        }
    }

    public function AccessToken()
    {
        $this->load->model('access_token_model');
        $appid      = $this->input->get('appid');
        $secret     = $this->input->get('secret');
        if(empty($appid) OR empty($secret)) {
            echo json_encode(['errcode'=>4001,'errmsg'=>'appid not exist']);
            exit;
        }
        if(!ctype_alnum($appid) OR !ctype_alnum($secret)) {
            echo json_encode(['errcode'=>4003,'errmsg'=>'appid or secret incorrect']);
            exit;
        }
        $ret        = $this->access_token_model->get_access_token($appid, $secret);
        echo json_encode($ret);
    }

    public function RegisterProcess()
    {
        //client_time
        $time = time();
        //启动游戏只记录一条记录
        if ($this->data['type_id']==0) {
            $sql_chk = "SELECT id FROM u_register_process_new WHERE appid={$this->data['appid']} AND mac='{$this->data['mac']}' LIMIT 1";
            $this->db->reconnect();
            $query = $this->db->query($sql_chk);
           // parent::log(json_encode($this->db->error()),  '/data/log/site/api/registerProcess_error.log');
            if($query){
            	if ( $query->result() ) {
            		$this->save('u_register_process_history');
            		return true;
            	}
            }
            
        }
        //第二次启动之后的数据都往历史表里面记录
        $sql_chk = "SELECT id FROM u_register_process_history WHERE appid={$this->data['appid']} AND mac='{$this->data['mac']}' and type_id=0 LIMIT 1";
        echo $sql_chk;
        $this->db->reconnect();
        $query = $this->db->query($sql_chk);
        //parent::log(json_encode($this->db->error()),  '/data/log/site/api/registerProcess_error.log');
        if($query){
        	if ( $query->result() ) {
        		$this->save('u_register_process_history');
        		return true;
        	}
        }
        
       
        $this->save('u_register_process_new');
        $this->save('u_register_process_'.date('Ymd',$this->data['created_at']));
    }

    public function GetRegisterProcess()
    {
        $appid = $this->input->get('appid');
        $conf  = include  APPPATH .'/config/event_click_config.php';
        $this->set_response($conf[$appid]);
    }




    /**
     * 副本
     */
    public function CopyProgress()
    {
        $this->save($this->config->item(__FUNCTION__));
    }

    private function check_online_uk($appid, $accountid, $serverid, $online_date)
    {
        $sql_chk = "SELECT id FROM u_dayonline WHERE appid=$appid"
            ." AND accountid=$accountid and serverid=$serverid"
            ." and online_date=$online_date LIMIT 1";
        $this->db->reconnect();
        $query = $this->db->query($sql_chk);
        //parent::log("debug:$sql_chk", LOG_PATH . '/queue_dayonline.log');
        if ( $query && $query->result() ) {
            $row = $query->result();
            //parent::log("data:". json_encode($query->result()), LOG_PATH . '/queue_dayonline.log');
            $sql = "UPDATE u_dayonline SET online={$this->data['online']} where accountid=$accountid and serverid=$serverid AND online_date=$online_date";;
            $ret = $this->db->simple_query($sql);
            //$ret = $this->update('u_dayonline',
            //    ['id'=>$row->id],
            //    $this->data);
            if ($ret===false ) {
                parent::log("debug:DayOnline update fail:". json_encode($this->data) . ";msg=".json_encode($this->db->error()), LOG_PATH . '/queue_dayonline.log');
                $ret = ['errcode'=>0,'errmsg'=>'success'];
                $this->set_response($ret);
            }
            return true;
        }
        return false;
    }
    /**
     * 每日在线
     */
    public function DayOnline()
    {
 
        $this->data['online_date'] = isset($this->data['client_time']) ? date('Ymd', $this->data['client_time']) : date('Ymd');
        $res = $this->check_online_uk($this->data['appid'], $this->data['accountid'], $this->data['serverid'], $this->data['online_date']);
        if ($res === false) {
            $ret = $this->db->insert('u_dayonline', $this->data);
            if ($ret===TRUE ) {
                parent::log("debug:DayOnline success:". json_encode($this->data), LOG_PATH . '/queue_dayonline.log');
                $ret = ['errcode'=>0,'errmsg'=>'success'];
                $this->set_response($ret);
            }
            else {
                $this->check_online_uk($this->data['appid'], $this->data['accountid'], $this->data['serverid'], $this->data['online_date']);
            }
        }
    }

    /**
     * 养成&强化
     */
    public function Develop()
    {
        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 玩家获得元宝记录
     */
    public function GiveEmoney()
    {
        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 关卡进度
     */
    public function LevelProcess()
    {
        $this->save($this->config->item(__FUNCTION__));
    }



    /**
     * 首次登录记录
     */
    public function FirstLogin()
    {
        $sql = "SELECT id,first_login_at FROM u_players WHERE accountid={$this->data['accountid']} LIMIT 1";
        $query = $this->db->query($sql);
        $time = $this->data['time'] > 0 ? time() : $this->data['time'];
        if ($query) {
            $ret = $query->result_array();
            if ($ret[0]['first_login_at']==0) {
                $sql_update = "UPDATE u_players SET first_login_at=$time,first_login_mac='{$this->data['mac']}' WHERE id={$ret[0]['id']} LIMIT 1";
            }
            else {
                $this->set_response($this->errs[self::ERR7]);
                return true;
            }
        }
        else {
            $sql_update = "INSERT INTO u_players SET first_login_at=$time,first_login_mac='{$this->data['mac']}',accountid={$this->data['accountid']}";
        }
        $res = $this->db->query($sql_update);
        if ($res) {
            $this->set_response(['errcode'=>0,'errmsg'=>'success']);
        } else {
            $this->set_response($this->errs[self::ERR7]);
        }
    }

    public function Online()
    {
        $this->load->database('sdk');
        parent::log("ip:{$_SERVER['REMOTE_ADDR']};request_method:Online;;data:" . json_encode($_POST),
            LOG_PATH . '/Online' .date('YmdH') . ".log");
        $this->data = $_POST;
        $this->save('online');
        //print_r($this->db->error());
    }

    public function Player()
    {
        $this->save($this->config->item(__FUNCTION__));
    }

    public function PlayerBasicInfo()
    {
        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 更新玩家信息
     */
    public function UpdatePlayer()
    {
        $serverid = $this->data['serverid'];
        $userid   = $this->data['userid'];
        unset($this->data['serverid']);
        unset($this->data['userid']);
        $this->update(
            $this->config->item(__FUNCTION__),
            ["userid"=>$userid,"serverid"=>$serverid],
            $this->data);
    }

    /**
     * 道具
     */
    public function Props()
    {
        if (!empty($this->data_multi)) {
            $data_use = $data_get = [];
            foreach ($this->data_multi as $key=>$val) {
                $action = $val['action'];
                unset($this->data_multi[$key]['action']);
                if ($action==0) {
                    $data_get[] = $this->data_multi[$key];
                }
                else {
                    $data_use[] = $this->data_multi[$key];
                }
            }
            if (count($data_get)) $this->save_multi($this->config->item(__FUNCTION__)[0], $data_get, false);
            if (count($data_use)) $this->save_multi($this->config->item(__FUNCTION__)[1], $data_use);
        }
        else {
            if (!isset($this->data['action'])) {
                $this->set_response( ['errcode'=>4009,'errmsg'=>"数据格式错误"]);
                return false;
            }
            $action = $this->data['action'];
            unset($this->data['action']);
            $this->save($this->config->item(__FUNCTION__)[$action]);
        }
    }

    /**
     * 玩家在消费记录
     */
    public function Rmb()
    {
        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 日常行为
     * daily actions
     */
    public function DailyActions()
    {
        $this->save($this->config->item(__FUNCTION__));
    }

    public function PaylogProcess()
    {
        $this->save($this->config->item(__FUNCTION__));
    }
    /**
     * 成就进度
     */
    public function SuccessProcess()
    {
            /*{
            "userid": "1001043",
            "accountid":"1123232",
            "serverid": "9242",
            "channel": "60016",
            "type": "1",
            "title": "副本名称",
            "lev": "80",
            "is_success":1
            }*/
//        $this->data['success_type'] = intval($this->data['type']);
//        unset($this->data['type']);
        $this->save($this->config->item(__FUNCTION__));
    }
    /**
     * 成就进度
     */
    public function UpgradeProcess()
    {
        //d527c8b608f538d6f312243bdfd55461
            /*{
            "userid": "1001043",
            "accountid":"1123232",
            "serverid": "9242",
            "channel": "60016",
            "lev": "80",
            }*/
        //获取上次升级的时间
//        $queryRet = $this->db->select(['created_at'])
//            ->where()
//            ->order_by(array('lev' => 'DESC'))
////            ->limit(1)->get();
//        $queryRet = $this->db->select('created_at')
//            ->where(['accountid'=>$this->data['accountid']])
//            ->limit(1)
//            ->get( $this->config->item(__FUNCTION__));
        $queryRet = $this->db->select(['created_at','upgrade_time'])
            ->where(['accountid'=>$this->data['accountid']])
            ->order_by('lev', 'DESC')
            ->limit(1)
            ->get($this->config->item(__FUNCTION__))
            ->row_array();
        if ($queryRet) {
            //升级时间
            $this->data['upgrade_time'] =  $this->data['created_at'] - $queryRet['created_at'];
        }
        else {
            $this->data['upgrade_time'] = 0;
        }

        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 登出
     * @author Guangpeng Chen
     * @date 2016-10-14
     */
    public function Logout()
    {
        $this->save('u_logout');
    }

    /**
     * 充值成功事件
     * @author Guangpeng Chen
     * @date 2016-10-14
     */
    public function Recharge()
    {
        if (!$this->data['rmb'] || $this->data['rmb']<0 || !is_numeric($this->data['rmb'])) {
            parent::log("充值金额[{$this->data['rmb']}]错误", LOG_PATH . '/queue_error_recharge.log');
            return false;
        }
        if (!$this->data['accountid']) {
            parent::log("充值账号[{$this->data['accountid']}]错误", LOG_PATH . '/queue_error_recharge.log');
            return false;
        }
        $this->save('u_recharge');
    }

    /**
     * 从指定渠道打开游戏
     *
     * @author Guangpeng Chen
     * @date 2016-10-14
     */
    public function GameStartChannel()
    {
        if (!$this->data['channel'] || !$this->data['mac']) {
            parent::log("channel or mac cannot be empty", LOG_PATH . '/queue_error_GameStartChannel.log');
            return false;
        }
        $this->save('u_open_game_channel');
    }
    /**
     * 事件记录
     * @author Guangpeng Chen
     * @date 2016-10-14
     */
    public function EventLog()
    {
        $event_id = $this->data['event_id'];
        $table_list = [
            1 => 'e_open_game_channel',//在指定渠道打开游戏
            2 => 'e_register_success',//注册成功
            3 => 'e_recharge',//充值
            4 => 'e_login',//登录
            5 => 'e_logout',//登出
        ];
        switch ($event_id) {
            case 1:

                break;
            case 2:
                if (!$this->data['channel'] || !$this->data['mac'] || !$this->data['accountid']) {
                    parent::log("EventLog Fail, channel or mac cannot be empty", LOG_PATH . '/queue_error_eventlog.log');
                    return false;
                }
                break;
            case 3:
                break;


        }
        $this->save($table_list[$event_id]);
    }

    private function update($collection, Array $where, Array $data)
    {

        $res = $this->db->update($collection, $data, $where);
        if ($res) {
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);

        } else {
            log_message('error', $collection
                . "更新失败,数据:".$this->json);
            $this->set_response($this->errs[self::ERR7]);
        }
    }
    /**
     * 获得需要累加更新的SQL语句
     *
     * @param string $table_name 表名称
     * @param int $id 主键ID
     * @param array $values 字段对应数值
     * @return string
     */
    private function get_update_string($table_name, $id, array $values)
    {
        $sets = "";
        foreach ($values as $key=>$val) {
            if (strpos($val, '=') !== false ) {
                $sets .= "`$key`$val,";
            }
            else {
                $sets .= "`$key`=`$key`+$val,";
            }
        }
        $sets = rtrim($sets, ',');
        $sql = "UPDATE `$table_name` SET $sets WHERE id=$id LIMIT 1";
        return $sql;
    }

    /**
     * 游戏服务器提交数据接口，数据库结构查看“口袋妖怪-20161110.sql”
     *
     * @author Guangpeng Chen
     * @date  2016-11-10
     *
     */
    public function GameServerData()
    {
        $type_id = $this->data['type_id'];
        unset($this->data['type_id']);
        $log_date = date('Ymd');//记录日期
        $map = [
            'accountid'=> $this->data['accountid'],
            'userid'   => $this->data['userid'],
            'log_date' => $log_date
        ];
        $sets = [];
        $table_name = $this->server_data_tbl[$type_id];
        if($type_id==3){return true;}  //  不保存 u_common_currency
        switch ($type_id) {
            case self::SERVER_PLAYER_ACTIVE:
                $sets['active'] = $this->data['active'];
                break;
            case self::SERVER_PLAYING_METHOD:
                $map['method'] = $this->data['method'];
                //次数
                $sets['playing_times'] = 1;
                //根据服务端发送的秒数累加
                $sets['playing_time']  = $this->data['playing_time'];
                break;
            case self::SERVER_COMMON_CURRENCY:
                $map['daction']   = $this->data['daction'];
                $map['item_type'] = $this->data['item_type'];
                $sets['amount']   = $this->data['amount'];
                break;
            case self::SERVER_ELF_STARLEV:
                if (isset($this->data['nomal_copy']) || isset($this->data['nomal_elite'])) {
                    $other_fields = ['nomal_copy','nomal_elite'];
                }
                break;
            case self::SERVER_LEVEL_DIFFICULTY:
                $map['level_id'] = $this->data['level_id'];
                $getstar = $this->data['getstar'];
                $is_first_battle = $this->data['is_first_battle'];
                //是否成功, 0：失败 1：成功
                $winflag = $this->data['winflag'];
                unset($this->data['winflag']);
                unset($this->data['is_first_battle']);
                unset($this->data['getstar']);
                if ($is_first_battle==1) {
                    $this->data['star'] = $getstar;
                    $this->data['is_first_pass'] = $winflag;
                    $this->data['total_times'] = 1;
                    $this->data['success_times'] = $winflag==1 ? 1: 0;
                    $this->data['failure_times'] = $winflag==0 ? 1: 0;
                    $this->data['total_lev'] = $this->data['lev'];
                    $this->data['total_fighting'] = $this->data['fighting'];
                    $this->data['max_star'] = $getstar;
                    $this->data['avg_fighting'] = $this->data['fighting'];
                    $this->data['avg_lev']  = $this->data['lev'];
                    $this->data['max_star_times']  = 1;
                }
                else {
                    if($winflag==1)  $sets['success_times'] = 1;
                    else $sets['failure_times'] = 1;
                    $sets['total_times'] = 1;
                    $sets['total_lev'] = $this->data['lev'];
                    $sets['total_fighting'] = $this->data['fighting'];
                }
                $other_fields = ['max_star','max_star_times','total_times','total_fighting','total_lev'];
                break;
            case self::SERVER_PHOTO_LEVEL:
                break;
        }
        $check_unique = $this->unique_check($table_name, $map, $other_fields);
        //新增
        if ($check_unique==0) {
            if ($type_id == self::SERVER_PLAYING_METHOD) $this->data['playing_times'] = 1;
            elseif ($type_id == self::SERVER_ELF_STARLEV)
            {
                $this->data['nomal_copy']  = isset($this->data['nomal_copy']) ? $GLOBALS['nomal_copy'][$this->data['nomal_copy']] : 0;
                $this->data['nomal_elite'] = isset($this->data['nomal_elite']) ? $GLOBALS['nomal_elite'][$this->data['nomal_elite']] : 0;
            }
            $this->data['log_date'] = $log_date;
            $ret = $this->db->insert($table_name, $this->data);
            if ($ret!==TRUE) {
                parent::log($table_name . "数据写入失败,数据:".$this->json
                    .",msg:".json_encode($this->db->error()), LOG_PATH . '/queue_error.log');
            }
        }
        else {
            $primary_id = $check_unique;
            //更新
            if (isset($other_fields)) {
                $primary_id = $check_unique->id;
                if ($type_id == self::SERVER_LEVEL_DIFFICULTY) {
                    //根据提交过来的星级，跟旧的对比，比旧的记录大就更新
                    if ($check_unique->max_star< $getstar)  $sets['max_star'] = $getstar;
                    //max_star等于3之前，要累加这个字段的次数
                    if ($check_unique->max_star < 3) $sets['max_star_times'] = 1;
                    $sets['avg_fighting'] = "=" . ceil(($check_unique->total_fighting + $this->data['fighting']) / ($check_unique->total_times+1));
                    $sets['avg_lev']      = "=" . ceil(($check_unique->total_lev + $this->data['lev']) / ($check_unique->total_times+1));
                }
                elseif ($type_id == self::SERVER_ELF_STARLEV) {
                    $nomal_copy  = $GLOBALS['nomal_copy'][$this->data['nomal_copy']];
                    $nomal_elite = $GLOBALS['nomal_elite'][$this->data['nomal_elite']];
                    if ($nomal_copy > $check_unique->nomal_copy) $sets['nomal_copy'] = "=$nomal_copy";
                    if ($nomal_elite > $check_unique->nomal_elite) $sets['nomal_elite'] = "=$nomal_elite";
                }
            }
            elseif ($type_id==self::SERVER_ELF_STARLEV || $type_id==self::SERVER_PHOTO_LEVEL) {
                $this->db->update($table_name, $this->data, ['id'=>$check_unique]);
                return true;
            }
            $update_str = $this->get_update_string($table_name, $primary_id, $sets);
            $this->db->simple_query($update_str);
        }
    }
    /**
     * 检测某个表是否唯一记录
     *
     * @param $table
     * @param array $map
     * @param string $other_fields
     * @return int
     */
    private function unique_check($table, array $map, $other_fields='')
    {
        $where = '1=1';
        if (empty($other_fields) ) {
            $fields = 'id';
        }
        else {
            $fields = 'id,' . (is_array($other_fields) ? implode(',', $other_fields) : $other_fields);
        }
        foreach ($map as $key=>$val) {
            $where .= " AND `$key`='$val'";
        }
        $sql_chk = "SELECT $fields FROM `$table` WHERE ".$where." LIMIT 1";
        $query = $this->db->query($sql_chk);
        if($query)$row   = $query->row();
        if ($row ) {
            if (empty($other_fields)) return $row->id;
            return $row;
        }
        return 0;
    }

    /**
     * 数据校验
     *
     * @param $table
     * @param $data
     */
    private function verify_data($table, $data)
    {
        return true;
        if (empty($table)) {
            $this->set_response( ['errcode'=>4011,'errmsg'=>"table empty"]);
            return false;
        }
        if (isset($data['accountid']) && !$data['accountid']) {
            $this->set_response( ['errcode'=>4010,'errmsg'=>"账号ID怎么能为空?"]);
            return false;
        }

        //TODO::获取表的字段，此处可做缓存
        $sql = "DESC $table";
        $q = $this->db->query($sql);
        if (!$q) {
            parent::BetterLog('sql_error' . __METHOD__, $q);
            return false;
        }
        $fields = $q->result_array();
        $field_list = [];
        foreach ($fields as $field) {
            $field_list[$field['Field']] = $field['Type'];
        }
        $fields = array_keys($field_list);
        foreach ($data as $key=>$val) {
            if  (!in_array($key, $fields)) {
                $this->set_response( ['errcode'=>4009,'errmsg'=>"[{$table}.{$key}]字段非法", 'data'=>$data]);
                parent::log("[{$table}.{$key}]字段非法", LOG_PATH . '/queue_error.log');
                return false;
            }
        }
        return true;
    }
    private function save_multi($table, $save_data=null, $response = true)
    {
        $save_data = is_null($save_data) ? $this->data_multi : $save_data;
        foreach ($save_data as $data) {
            //print_r($data);
            $res = $this->verify_data($table, $data);
            if ($res!==true) return false;
        }
        $ret = $this->db->insert_batch($table, $save_data);
        if ($ret>0) {
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);
        }
        else {
            $ret = $this->errs[self::ERR6];
            parent::log($table . "数据写入失败,数据:".$this->json
                .",msg:".json_encode($this->db->error()), LOG_PATH . '/queue_error.log');
        }
        if ($response===true) {
            $this->set_response($ret);
        }
       
        return true;
    }
    private function save($table, $response=true, $replace=false)
    {
        if (!empty($this->data_multi)) {
            return $this->save_multi($table);
        }
        if ($table=='online') {

        }
        else{
            $this->verify_data($table, $this->data);
        }
        $this->db->reconnect();
        if ($replace===true) {
            $ret = $this->db->replace($table, $this->data);
        }
        else {
            $ret = $this->db->insert($table, $this->data);
        }
        if ($ret===TRUE && $response===TRUE) {
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);
        }
        else {
            parent::BetterLog('queue/error',$table . "数据写入失败,数据:".$this->json
                .",msg:".json_encode($this->db->error()));
            $this->set_response($this->errs[self::ERR6]);
        }
 
    }
    public function __destruct()
    {
        $this->json = null;
        $this->data = null;
    }


    private function set_response($data = NULL)
    {
        //set_status_header(200);
        //echo json_encode($data, JSON_UNESCAPED_UNICODE),"\n";
        return true;
    }
    /**
     * 只记录苹果用户登录
     * @author 王涛 20171228
     */
    public function applelogin(){
    	if (isset($this->data['ip']) && $this->data['ip']) {
    		$ip = $this->data['ip'];
    	} else {
    		$ip = $_SERVER['REMOTE_ADDR'];
    	}
    	$this->data['ip']  =  ip2long($ip);
    	$this->data['logindate']  =  date('Ymd', $this->data['created_at']);
    	$ym = date('Ym', $this->data['created_at']);
    	//$this->save($this->config->item(__FUNCTION__));
    	//parent::BetterLog('queue_login',"data:".json_encode( $this->data).';error:'.json_encode( $this->db->error()));
    	//$this->save('u_login_'.date('Ymd'));
    	//添加最后登录表
    	$lastdata = array(
    			'username' => 	$this->data['username'],
    			'userid' => 	$this->data['userid'],
    			'accountid' => 	$this->data['accountid'],
    			'serverid' => 	$this->data['serverid'],
    			'channel' => 	$this->data['channel'],
    			'viplev' => 	$this->data['viplev'],
    			'lev' => 	$this->data['lev'],
    			'appid' => 	$this->data['appid'],
    			'client_type' => 	$this->data['client_type'],
    			'last_login_ip' => 	$this->data['ip'],
    			'last_login_time' => 	$this->data['created_at'],
    			'last_login_mac' => 	$this->data['mac'],
    			'communityid' => 	isset($this->data['communityid'])?$this->data['communityid']:0,
    	);
    	$sql = "INSERT INTO u_apple_login_".$ym."(`serverid`, `channel`, `appid`, `accountid`, `username`,userid,viplev,lev,client_type,ip,created_at,mac,trainer_lev,client_version,communityid)
    	VALUES ({$lastdata['serverid']},{$lastdata['channel']},{$lastdata['appid']},{$lastdata['accountid']},'{$lastdata['username']}',{$lastdata['userid']},{$lastdata['viplev']},{$lastdata['lev']}
    	,'{$lastdata['client_type']}',{$lastdata['last_login_ip']},{$lastdata['last_login_time']},'{$lastdata['last_login_mac']}','{$this->data['trainer_lev']}','{$this->data['client_version']}','{$lastdata['communityid']}')";
    	$this->db->query($sql);
    }
    /**
     * 登录
     */
    public function Login()
    {
 
        if (isset($this->data['ip']) && $this->data['ip']) {
            $ip = $this->data['ip'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->data['ip']  =  ip2long($ip);
        if(!isset($this->data['created_at'])){
        	$this->data['created_at']=time();
        }
        if(!isset($this->data['appid'])){
        	$this->data['appid']=0;
        }
        $this->data['logindate']  =  date('Ymd', $this->data['created_at']);
        //$this->save($this->config->item(__FUNCTION__));
         //parent::BetterLog('queue_login',"data:".json_encode( $this->data).';error:'.json_encode( $this->db->error()));
        //$this->save('u_login_'.date('Ymd'));
        //添加最后登录表
        $lastdata = array(
        	'username' => 	$this->data['username'],
        		'userid' => 	$this->data['userid'],
        		'accountid' => 	$this->data['accountid'],
        		'serverid' => 	$this->data['serverid'], 
        		'channel' => 	$this->data['channel'],
        		'viplev' => 	$this->data['viplev'],
        		'lev' => 	$this->data['lev'],
        		'appid' => 	$this->data['appid'],
        		'client_type' => 	$this->data['client_type'],
        		'last_login_ip' => 	$this->data['ip'],
        		'last_login_time' => 	$this->data['created_at'],
        		'last_login_mac' => 	$this->data['mac'],
                'communityid' => 	isset($this->data['communityid'])?$this->data['communityid']:0,
        );
        $sql = "INSERT INTO u_login_".$this->data['logindate']."(`serverid`, `channel`, `appid`, `accountid`, `username`,userid,viplev,lev,client_type,ip,created_at,mac,trainer_lev,client_version,communityid)
VALUES ({$lastdata['serverid']},{$lastdata['channel']},{$lastdata['appid']},{$lastdata['accountid']},'{$lastdata['username']}',{$lastdata['userid']},{$lastdata['viplev']},{$lastdata['lev']}
,'{$lastdata['client_type']}',{$lastdata['last_login_ip']},{$lastdata['last_login_time']},'{$lastdata['last_login_mac']}','{$this->data['trainer_lev']}','{$this->data['client_version']}','{$lastdata['communityid']}')
ON DUPLICATE KEY UPDATE `channel`=VALUES(channel),`viplev`=VALUES(viplev),`lev`=VALUES(lev),`client_type`=VALUES(client_type),`ip`=VALUES(ip),
`created_at`=VALUES(created_at),`mac`=VALUES(mac),`username`=VALUES(username),`trainer_lev`=VALUES(trainer_lev),`client_version`=VALUES(client_version),`userid`=VALUES(userid),`communityid`=VALUES(communityid)
";
        //parent::BetterLog('queue_login_test',$sql);
      
    
      $this->db->reconnect();
      $this->db->query($sql);
   
        $result = $this->db->error();
        if($result['code'] ==  2006){ //数据库中断
        	$client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
        	$client->put('api_queue_Login', $this->data_old);
        	parent::BetterLog('queue_login_error',json_encode($this->data).json_encode($result));
        }
        $sql = <<<SQL
INSERT INTO u_last_login(`serverid`, `channel`, `appid`, `accountid`, `username`,userid,viplev,lev,client_type,last_login_ip,last_login_time,last_login_mac,communityid)
VALUES ({$lastdata['serverid']},{$lastdata['channel']},{$lastdata['appid']},{$lastdata['accountid']},"{$lastdata['username']}",{$lastdata['userid']},{$lastdata['viplev']},{$lastdata['lev']}
,"{$lastdata['client_type']}",{$lastdata['last_login_ip']},{$lastdata['last_login_time']},"{$lastdata['last_login_mac']}",{$lastdata['communityid']}) 
ON DUPLICATE KEY UPDATE `channel`=VALUES(channel),`viplev`=VALUES(viplev),`lev`=VALUES(lev),`client_type`=VALUES(client_type),`last_login_ip`=VALUES(last_login_ip),
`last_login_time`=VALUES(last_login_time),`last_login_mac`=VALUES(last_login_mac),`username`=VALUES(username),`communityid`=VALUES(communityid)
SQL;
        $this->db->reconnect();
        $this->db->query($sql);     
     
    }

    /**
     * Bug反馈
     */
    public function BugReport()
    {
    	$this->save($this->config->item(__FUNCTION__));
    }
    /**
     * 角色创建-优先入库
     */
    public function CreateRole()
    {
    	//1、收到客户端发送的创建角色消息（createrole），检查u_players是否已经存在accountid和channel 一样的数据，如果还没有，则往u_players写入数据
    	$sql = "SELECT id FROM u_players WHERE accountid={$this->data['accountid']} AND channel={$this->data['channel']} LIMIT 1";
    	$this->db->reconnect();
    	$query = $this->db->query($sql);
    	if ( $query && $query->row()->id ) {
    		$this->save('u_players', TRUE);
    	}
    	$sql = "SELECT id FROM u_roles WHERE accountid={$this->data['accountid']} AND userid={$this->data['userid']} LIMIT 1";
    	$this->db->reconnect();
    	$query = $this->db->query($sql);
    	if ( $query && $query->row()->id ) {
    		return false;
    	}
    	$this->save($this->config->item(__FUNCTION__));
    }
    /**
     * 安装解压 && 设备激活
     */
    public function DeviceActive()
    {
    	//$this->save($this->config->item(__FUNCTION__));
    	echo __FUNCTION__;
    	$res = $this->db->insert($this->config->item(__FUNCTION__), $this->data);
    	//写入数据到唯一的设备激活表
    	$data[0] = $this->data;
    	$this->db->insert('u_device_unique', $this->data);
    	//$this->insert_batch("u_device_unique", $data);
    	/*$chk = "select id from u_device_unique WHERE appid={$this->data['appid']} AND mac='{$this->data['mac']}' LIMIT 1";
    	$qr  = $this->db->query($chk);
    	if (!$qr || !$qr->result()) {
    		$this->db->insert( 'u_device_unique', $this->data);
    	}
    	if ($res) {
    		$ret = ['errcode'=>0,'errmsg'=>'success'];
    		$this->set_response($ret);
    
    	} else {
    		log_message('error', $this->config->item(__FUNCTION__)  . "插入失败,数据:".$this->json);
    		$this->set_response($this->errs[self::ERR7]);
    	}*/
    }
    /**
     * 用户注册
     */
    public function Register()
    {
   		 if (isset($this->data['ip']) && $this->data['ip']) {
            $ip = $this->data['ip'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->data['ip']  =  ip2long($ip);
    	$this->data['reg_date'] = date('Ymd',$this->data['created_at']);
    	$this->save('u_register');
    	$result = $this->db->error();
    	if($result['code'] ==  2006){ //数据库中断
    		$client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
    		$client->put('api_queue_Register', $this->data_old);
    		parent::BetterLog('queue_register_error',json_encode($this->data).json_encode($result));
    	}
    }
    
    /**
     * 精灵塔通关阵容
     *
     * @author 王涛 20170321
     */
    public function Gametower()
    {
    	unset($this->data['appid']);
    	$data = $this->data;
    	$comdata = array(
    			'tower'=>$data['Group'],
    			'logdate'=>$data['EndTime'],
    			'integral'=>$data['Grade'],
    			'serverid'=>$data['ServerId'],
    			'playerid'=>$data['UserId'],
    			'created_at'=>$data['created_at'],
    	);
    	/*$ym = '20'.substr($data['endTime'], 0,4);
    	$ymd = '20'.substr($data['endTime'], 0,6);*/
    	$m = '20'.substr($data['EndTime'], 0,4);
    	for($i=1;$i<=12;$i++){
    		if(!isset($data['EudType'.$i])){
    			break;
    		}
    		$towerdata = $comdata;
    		$towerdata['eudemon'] = $data['EudType'.$i];
    		$towerdata['hp'] = $data['LeftLife'.$i];
    		for($j=1;$j<=4;$j++){
    			$towerdata['skills'.$j] = $data['MagicType'.$i.'_'.$j];
    			$towerdata['pp'.$j] = $data['CurUseTimes'.$i.'_'.$j];
    		}
    		$multdata[]=$towerdata;
    		//$this->db->insert('game_tower_'.$m, $towerdata);
    	}
    	$this->db->insert_batch('game_tower_'.$m, $multdata);
    	//$this->save('game_tower_'.$m);
    }
    /**
     * 神兽来袭
     *
     * @author 王涛 20170320
     */
    public function Gamedeath()
    {
    	unset($this->data['appid']);
    	$this->data['logdate']  =  date('Ymd', $this->data['die_time']);
    	$this->save('game_death');
    }
    /**
     * 报警
     */
    public function warninfo()
    {
    	unset($this->data['appid']);
    	$this->save('warninfo');
    }
    
    /**
     * 社团争霸报名
     */
    public function Community()
    {
    	unset($this->data['appid']);
    	$this->save('game_sign');
    }
    /**
     * 掉线统计
     */
    public function Drops()
    {
    	unset($this->data['appid'],$this->data['created_at']);
    	$this->save('game_drops');
    }
    /**
     * 战斗匹配时长
     *
     * @author 王涛 20170428
     */
    public function match()
    {
    	$this->data['logdate']  =  date('Ymd', $this->data['created_at']);
    	$Ym = date('Ym', $this->data['created_at']);
    	unset($this->data['appid']);
    	$this->save('game_match_'.$Ym);
    }

    /**
     * 社团副本
     *
     * @author 王涛 20170509
     */
    public function Communityprocess()
    {
    	$this->data['logdate']  =  date('Ymd', $this->data['operate_time']);
    	$this->data['onh']  =  date('H', $this->data['operate_time']);
    	unset($this->data['appid'],$this->data['created_at']);
    	$this->save('game_community');
    }
    /**
     * 椰蛋树活动
     *
     * @author 王涛 20170509
     */
    public function Gameegg()
    {
    	$this->data['logdate']  =  date('Ymd', $this->data['operate_time']);
    	unset($this->data['appid'],$this->data['created_at']);
    	$this->save('game_egg');
    }
    
    /**
     * 冠军之夜排名
     *
     * @author 王涛 20170512
     */
    public function Gamerank()
    {
    	$this->data['logdate']  =  date('Ymd', $this->data['created_at']);
    	unset($this->data['appid']);
    	$this->save('game_rank');
    }
    
    /*
     * 跨服战的配置   zzl 20170710
     */
    public function crossserver(){    	
    	$this->data['logdate']  =  date('Ymd', $this->data['updatetime']);    
    	$sql = "SELECT id FROM cross_server WHERE server_id={$this->data['server_id']}  LIMIT 1";
    	$query = $this->db->query($sql);      	
    	if($query && $query->result()){
    		$sql="update cross_server set pkgameserver={$this->data['pkgameserver']},pkexeserver={$this->data['pkexeserver']},pkcomserver={$this->data['pkcomserver']},pkeliteserver={$this->data['pkeliteserver']},pkweekendserver={$this->data['pkweekendserver']},openweekendtime={$this->data['openweekendtime']},pksyngameserver={$this->data['pksyngameserver']},opensyngameflag={$this->data['opensyngameflag']},updatetime={$this->data['updatetime']},port={$this->data['port']} WHERE server_id={$this->data['server_id']}";
    		$query = $this->db->query($sql);    	
    	}  else {
    		$this->save('cross_server');    	
    		
    	}    	
        }
    
    
    /**
     * 亲密度珍肴养成统计   zzl 20170801
     */
    
    public function intimacy()
    {   	
    
    	$this->data['logdate']  =  date('Ymd', $this->data['created_at']);    	
    	unset($this->data['appid']);
    	$sql = "SELECT id FROM intimacy WHERE accountid={$this->data['accountid']} and serverid={$this->data['serverid']} and logdate={$this->data['logdate']} LIMIT 1";
    	$query = $this->db->query($sql);   
        
    	if($query && $query->result()){
    		$sql = "update intimacy   set logdate={$this->data['logdate']} ,attack_avg={$this->data['attack_avg']},defend_avg={$this->data['defend_avg']},special_attack_avg={$this->data['special_attack_avg']},life_avg={$this->data['life_avg']},special_defend_avg={$this->data['special_defend_avg']},rotom_grade={$this->data['rotom_grade']},speed_avg={$this->data['speed_avg']}   WHERE accountid={$this->data['accountid']} and serverid={$this->data['serverid']}";
    		$query = $this->db->query($sql);   
    	
    	}
    	else {    	
    		$this->save('intimacy_'.date('Ym',$this->data['created_at'])); 
    
    	}    
    }
    
    public function  activityClick()
    {    	
    
     if (!empty($this->data_multi)) {
            $dataSave = array();
            $no_multi = false;
            foreach ($this->data_multi as $data) {
           
                $table_name='activity_click_'.date('Ymd',$data['created_at']);          
                
                $data['logdate']  =  date('Ymd',$data['created_at']);
                
                unset($data['appid']);
                if(!empty($data)){                    
                    $dataSave[$table_name][] = $data;
                }
             
            }
            foreach ($dataSave as $table=>$save_data)
            {
             //   $save_data['logdate']  =  date('Ymd',$save_data['created_at']);
                unset($save_data['appid']);
                $ret = $this->db->insert_batch($table, $save_data);
                
           
        
                if ($ret!==TRUE) {
                    log_message('error', $table
                        . "数据写入失败,数据:".$this->json
                        .",msg:".json_encode($this->db->error()));
                }
            }
        
        }        
       else {
        
    	$this->data['logdate']  =  date('Ymd', $this->data['created_at']);
    	unset($this->data['appid']);    
    	$this->save('activity_click_'.date('Ymd',$this->data['created_at']));
          }
    }
    
    /*
     * 邀请好友统计需求  zzl 20170901
     */
    public  function  InviteFriend(){ 
        $this->data['logdate']  =  date('Ymd', $this->data['created_at']);
        unset($this->data['appid']);
        $this->save('u_invite');
     
    }
    
    /**
     * 推送消息
     */
    public function tuisonginfo()
    {
    	 if (isset($this->data['ip']) && $this->data['ip']) {
            $ip = $this->data['ip'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->data['ip']  =  ip2long($ip);
        $this->data['logindate']  =  date('Ymd', $this->data['created_at']);
        !isset($this->data['devicetype']) && $this->data['devicetype'] = 0;
        !isset($this->data['regid']) && $this->data['regid'] = 0;
      if(!$this->data['regid']){
      	return false;
      }
      if(isset($this->data['game_id'])){
      	$sql = <<<SQL
INSERT INTO push_regid(`last_login_mac`, `login_time`, `devicetype`, `regid`, `logdate`,userid,serverid,accountid,ip,role_create_time,game_id)
VALUES ("{$this->data['mac']}",{$this->data['created_at']},"{$this->data['devicetype']}","{$this->data['regid']}","{$this->data['logindate']}","{$this->data['userid']}"
,"{$this->data['serverid']}","{$this->data['accountid']}","{$this->data['ip']}","{$this->data['role_create_time']}","{$this->data['game_id']}")
ON DUPLICATE KEY UPDATE `login_time`=VALUES(login_time),`devicetype`=VALUES(devicetype),`regid`=VALUES(regid),`logdate`=VALUES(logdate)
        ,`userid`=VALUES(userid),`serverid`=VALUES(serverid),`accountid`=VALUES(accountid),`ip`=VALUES(ip),`role_create_time`=VALUES(role_create_time),`game_id`=VALUES(game_id)
SQL;
      }else{
      		if(empty($this->data['created_at'])){
      			$this->data['created_at']=time();
      		}
      	$sql = <<<SQL
INSERT INTO push_regid(`last_login_mac`, `login_time`, `devicetype`, `regid`, `logdate`,userid,serverid,accountid,ip,role_create_time)
VALUES ("{$this->data['mac']}",{$this->data['created_at']},"{$this->data['devicetype']}","{$this->data['regid']}","{$this->data['logindate']}","{$this->data['userid']}"
,"{$this->data['serverid']}","{$this->data['accountid']}","{$this->data['ip']}","{$this->data['role_create_time']}")
ON DUPLICATE KEY UPDATE `login_time`=VALUES(login_time),`devicetype`=VALUES(devicetype),`regid`=VALUES(regid),`logdate`=VALUES(logdate)
        ,`userid`=VALUES(userid),`serverid`=VALUES(serverid),`accountid`=VALUES(accountid),`ip`=VALUES(ip),`role_create_time`=VALUES(role_create_time)
SQL;
      }        
        $this->db->query($sql);
 
    }

        /*
     * 玩家综合信息 zzl 20170929
     */
 public function UserInfo()
    {

        $this->data['logdate'] = date('Ymd', $this->data['client_time']);
        unset($this->data['appid']);
        
        $sql = '';
        if ($this->data['accountid'] && $this->data['serverid']) {
            $sql = "select id from u_userinfo where accountid={$this->data['accountid']} and serverid={$this->data['serverid']} limit 1";
        }
        
        $query = $this->db->query($sql);  
    
        $result = $query->result_array();        
 $this->data['channel']=$this->data['channel']?$this->data['channel']:0;
$this->data['vip_level']=$this->data['vip_level']?$this->data['vip_level']:0;
$this->data['client_time']=$this->data['client_time']?$this->data['client_time']:0;
$this->data['user_level']=$this->data['user_level']?$this->data['user_level']:0;
$this->data['create_time']=$this->data['create_time']?$this->data['create_time']:0;
$this->data['total_days']=$this->data['total_days']?$this->data['total_days']:0;
$this->data['prestige']=$this->data['prestige']?$this->data['prestige']:0;
$this->data['synscience_avg']=$this->data['synscience_avg']?$this->data['synscience_avg']:0;
$this->data['godstep']=$this->data['godstep']?$this->data['godstep']:0;
$this->data['stonestep_avg']=$this->data['stonestep_avg']?$this->data['stonestep_avg']:0;
$this->data['stonelevel_avg']=$this->data['stonelevel_avg']?$this->data['stonelevel_avg']:0;
$this->data['level_avg']=$this->data['level_avg']?$this->data['level_avg']:0;
$this->data['intimacy_avg']=$this->data['intimacy_avg']?$this->data['intimacy_avg']:0;
$this->data['individual_avg']=$this->data['individual_avg']?$this->data['individual_avg']:0;
$this->data['effort_avg']=$this->data['effort_avg']?$this->data['effort_avg']:0;
$this->data['baofen_avg']=$this->data['baofen_avg']?$this->data['baofen_avg']:0;
$this->data['prestige_avg']=$this->data['prestige_avg']?$this->data['prestige_avg']:0;
$this->data['handbook_avg']=$this->data['handbook_avg']?$this->data['handbook_avg']:0;
$this->data['adventure_max']=$this->data['adventure_max']?$this->data['adventure_max']:0;
$this->data['adventure_lev']=$this->data['adventure_lev']?$this->data['adventure_lev']:0;
$this->data['adventure_num']=$this->data['adventure_num']?$this->data['adventure_num']:0;
$this->data['equipsouledu_num']=$this->data['equipsouledu_num']?$this->data['equipsouledu_num']:0;
$this->data['soulaverage_level']=$this->data['soulaverage_level']?$this->data['soulaverage_level']:0;
$this->data['two_suit']=$this->data['two_suit']?$this->data['two_suit']:0;
$this->data['four_suit']=$this->data['four_suit']?$this->data['four_suit']:0;
$this->data['orangesoul_num']=$this->data['orangesoul_num']?$this->data['orangesoul_num']:0;
$this->data['purplesoul_num']=$this->data['purplesoul_num']?$this->data['purplesoul_num']:0;
$this->data['bluesoul_num']=$this->data['bluesoul_num']?$this->data['bluesoul_num']:0;
$this->data['greensoul_num']=$this->data['greensoul_num']?$this->data['greensoul_num']:0;
       
       
        
        if (!empty($result[0])) {  
     	$sql = "update u_userinfo set userid={$this->data['userid']},channel={$this->data['channel']},vip_level={$this->data['vip_level']},client_time={$this->data['client_time']},user_level={$this->data['user_level']},create_time={$this->data['create_time']},total_days={$this->data['total_days']},prestige={$this->data['prestige']},synscience_avg={$this->data['synscience_avg']},godstep={$this->data['godstep']},stonestep_avg={$this->data['stonestep_avg']},stonelevel_avg={$this->data['stonelevel_avg']},level_avg={$this->data['level_avg']},intimacy_avg={$this->data['intimacy_avg']},individual_avg={$this->data['individual_avg']},effort_avg={$this->data['effort_avg']},baofen_avg={$this->data['baofen_avg']},prestige_avg={$this->data['prestige_avg']},handbook_avg={$this->data['handbook_avg']},logdate={$this->data['logdate']},adventure_max={$this->data['adventure_max']},adventure_lev={$this->data['adventure_lev']},adventure_num={$this->data['adventure_num']},equipsouledu_num={$this->data['equipsouledu_num']},soulaverage_level={$this->data['soulaverage_level']},two_suit={$this->data['two_suit']},four_suit={$this->data['four_suit']},orangesoul_num={$this->data['orangesoul_num']},purplesoul_num={$this->data['purplesoul_num']},bluesoul_num={$this->data['bluesoul_num']},greensoul_num={$this->data['greensoul_num']} WHERE accountid={$this->data['accountid']} and serverid={$this->data['serverid']}";
            $query = $this->db->query($sql);   
     
        } else {
            
            $this->save('u_userinfo');
        
        }
     
    //    parent::log(json_encode($this->data), LOG_PATH . '/SkillRateLog.log');
     //   parent::log($this->db->last_query(),LOG_PATH . '/SkillRateLog.log');
    }
    
    
    /*
     * vip信息  zzl 20171109
     */
    public  function  UserVip(){ 
        
        $this->data['logdate'] = date('Ymd', $this->data['client_time']);
        unset($this->data['appid']);
      $this->save('user_vip');

    }
 
 
  /*
   *     精灵使用率接口     zzl 20171109
   */
    public  function  UesedEud(){
        $this->data['logdate'] = date('Ymd', $this->data['client_time']);
        unset($this->data['appid']);      
        
        
       $data['userid']=$this->data['userid'];
       $data['accountid']=$this->data['accountid'];
       $data['serverid']=$this->data['serverid'];
       $data['channel']=$this->data['channel'];
       $data['viplev']=$this->data['viplev'];
       $data['client_time']=$this->data['client_time'];
       $data['lev']=$this->data['lev'];
       $data['created_at']=$this->data['created_at'];
       $data['win_flag']=$this->data['win_flag'];
       $data['eud_num']=$this->data['eud_num'];
       $data['logdate'] = date('Ymd', $this->data['client_time']);
       $data['bout_flag']=time().rand(1000,9999);
       
    
      
       for ($x=1;$x<=$data['eud_num'];$x++){
           $eud_type= 'eud_type'.$x;
           $data['eud_id']= $this->data[$eud_type];
           $data_new[$x]= $data;

       }
        
      $this->db->insert_batch('ueseeud', $data_new);  
   
   
    }
    
    /*
     *        技能使用  zzl 20171109
     */
    public  function  UesedMagic(){
    
        $this->data['logdate'] = date('Ymd', $this->data['client_time']);
        unset($this->data['appid']);      
        
   
        $data['turn_num']=$this->data['turn_num'];
        $data['maigc_num']=$this->data['maigc_num'];
        $data['bout_flag']=time().rand(1000,9999);
        $data['serverid']==$this->data['serverid'];
        if($this->data['client_time']){
            $data['logdate'] = date('Ymd', $this->data['client_time']);
        } else {
            $data['logdate'] = date('Ymd', time());
            
        }
     
        $data['client_time'] =  time();
        
        
        
        for ($x=1;$x<=$data['maigc_num'];$x++){
            $magic_id= 'magictype'.$x;
            $used_times= 'used_times'.$x;
            $data['magic_id']= $this->data[$magic_id];
            $data['used_times']= $this->data[$used_times];
            $data_new[$x]= $data;
            
   
        }        
   
        $this->db->insert_batch('uesedmagic', $data_new);
      
    }
    
    /*
     *        技能使用  zzl 20171202
     */
    public  function  Auction(){       

        $this->data['logdate'] =$this->data['created_at']? date('Ymd', $this->data['created_at']):date("Ymd",time());
        unset($this->data['appid']);        
        
        $this->save('auction');
        
     
    
    }
    
    
    /*
     *      时尚用  首充统计  zzl 20171212
     */
    public  function  FirstRecharge(){  
        
        $this->data['logdate'] =$this->data['client_time']? date('Ymd', $this->data['client_time']):date("Ymd",time());
        unset($this->data['appid']);
        
        $this->save('first_recharge');        
      
     
        
    }
    
    /*
     * 精灵使用率  zzl  2018.1.15
     */
    public  function  SkillRate(){
    
    	$this->data['logdate'] =$this->data['created_at']? date('Ymd', $this->data['created_at']):date("Ymd",time());
    	unset($this->data['appid']); 
    	
    	$this->save('skill_rate');


    
    }
   
}