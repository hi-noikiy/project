<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/27
 * Time: 14:50
 */

defined('BASEPATH') OR exit('No direct script access allowed');
define('LOG_PATH', '/data/log/site/access');
define('BASE_LOG_DIR', '/data/log/site/');
include APPPATH . 'libraries/HttpSQSClient.php';
class Api Extends CI_Controller
{
    protected $json;//接收到的数据
    protected $data;//插入的数据
    protected $data_multi;//插入的数据，多维数组

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
        'OnlineTest',
    	'Mac',
    ];
    /**
     * @var array 优先入库的接口
     */
    private $first_db = [
        /*'Register',
        'BugReport',
        'CreateRole',
        'Login',
        'DeviceActive',*/
    ];
    /**
     * @var array 优先入库的接口
     */
    private $only_queue = [
    		'Register',
    		'BugReport',
    		'CreateRole',
    		'Login',
    		'DeviceActive',
    ];
    /**
     * @var array 单独走队列的接口
     */
    private $only_db = [
    		'DayOnline',
    		'RegisterProcess',
    ];
    /**
     * access_token 这个参数改为： appid_MD5（每个app分配一个key + 提交参数data ）,下划线分隔
     *
     * @return bool
     */
    private function access_verify($verify_code)
    {
        if (!$verify_code) return ['appid'=>0, 'errcode'=>0];
        if (strpos($verify_code, '_')===false)  {
            $appid = substr($verify_code,0, 5);
            return ['appid'=>$appid, 'errcode'=>0];
        }
        list($appid, $token) = explode('_', $verify_code);
        return ['appid'=>$appid, 'errcode'=>0];
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
        $request_method = $this->router->fetch_method();

        if (in_array($request_method, $this->white_list)) {
            return true;
        }
        //优先入库
        if (in_array($request_method, $this->first_db)) {
            $this->config->load('api');
            $this->load->database('sdk');
            $access_token = $this->input->post('access_token');
            $ret = $this->access_verify($access_token);
            $_data = str_replace(["\n", " "], ['','+'],$_POST['data']);
            $json = base64_decode($_data);
            $this->data = json_decode($json, true);
            $this->data['appid']      = $ret['appid'];
            $this->data['created_at'] = time();
            parent::BetterLog("api/$request_method",
                "ip={$_SERVER['REMOTE_ADDR']}&request_method=$request_method&access_token=$access_token&data_raw=$_data");
//             parent::BetterLog("api/{$request_method}_decode",
//                 "request_method:$request_method;access_token:$access_token;data:" . base64_decode($_data));
            return true;
        }
        $access_token = $this->input->post('access_token');
        if (!$access_token) {
            set_status_header(401);
            $this->set_response( ['errcode'=>401,'errmsg'=>'fail']);
            exit;
        }
        $_data = str_replace(["\n", " "], ['','+'],$_POST['data']);
        $json = base64_decode($_data);
        $data = json_decode($json, true);
        if (in_array($request_method, array('Login','Register'))) {
            $data['ip'] = $_SERVER['REMOTE_ADDR'];
            $_data = base64_encode(json_encode($data));
        }
        if ($request_method == 'index' && $data['counttype'] == 101) {
        	parent::BetterLog('api/item101_info','data:'.json_encode($data));
        }
        $queue_data = [];
        $queue_data['request_method']       = $request_method;
        $queue_data['access_token']         = $access_token;
        $queue_data['request_data']         = $_data;
        $queue_data['timestamp']            = time();
        if($data['created_at']){
        	$queue_data['timestamp']            = $data['created_at'];
        }
        $queue_data_json = http_build_query($queue_data);
        $queue_name = 'api_queue_0';
        parent::BetterLog("api/$request_method",
            "ip={$_SERVER['REMOTE_ADDR']}&request_method=$request_method&access_token=$access_token&data_raw=$_data");
        /*if($request_method == 'Login'){
        	parent::BetterLog("api/{$request_method}_decode",
        			"request_method:$request_method;access_token:$access_token;data:" . base64_decode($_data));
        }*/
        
        $client = new HttpSQSClient('127.0.0.1', 1218, 'u591');
        //走单独队列
        if (in_array($request_method, $this->only_db)) {
        	$queue_name = 'api_queue_1';
        }
        //每个接口走单独队列
        if (in_array($request_method, $this->only_queue)) {
        	$queue_name = 'api_queue_'.$request_method;
        }
        $ret = $client->put($queue_name, $queue_data_json);
        if($request_method == "Register" && $ret !== true){
        	parent::BetterLog("api/{$request_method}_in",date('Y-m-d H:i:s'));
        }
        set_status_header(200);
        $this->set_response( ['errcode'=>0,'errmsg'=>'success']);
        exit;
    }

    /**
     * 用户注册
     */
    public function Register()
    {
        /*$this->data['ip']  =  ip2long($_SERVER['REMOTE_ADDR']);
        $this->data['reg_date'] = date('Ymd');
        $this->save('u_register');*/
    }
    /**
     * 广告商发送的设备信息
     */
    public function Mac()
    {
    	$this->load->database('sdk');
    	$data = json_decode(file_get_contents("php://input"),true);
    	
    	$this->data['logdate']  =date('Ymd');
    	$this->data['media_source']  =$data['media_source'];
    	if($data['event_name'] == 'af_complete_registration'){
    		parent::BetterLog("api/Mac","ip:{$_SERVER['REMOTE_ADDR']};request_method:Mac;;registerdata:" . json_encode($data));
    		$event_value = json_decode($data['event_value'],true);
    		$this->data['accountid'] = $event_value['af_account_id'];
    		$this->save('ad_register');
    	}elseif($data['event_name'] == 'af_login'){
    		parent::BetterLog("api/Mac","ip:{$_SERVER['REMOTE_ADDR']};request_method:Mac;;logindata:" . json_encode($data));
    		$event_value = json_decode($data['event_value'],true);
    		$this->data['accountid'] = $event_value['af_account_id'];
    		$this->save('ad_login_'.date('Y'));
    	}
    }
    public function Online()
    {
        $this->load->database('sdk');
        parent::BetterLog("api/Online","ip:{$_SERVER['REMOTE_ADDR']};request_method:Online;;data:" . json_encode($_POST));
        $this->data = $_POST;
        $this->save('online');
    }
    /**
     * 安装解压 && 设备激活
     */
    public function DeviceActive()
    {
        /*//$this->save($this->config->item(__FUNCTION__));
        $res = $this->db->insert($this->config->item(__FUNCTION__), $this->data);
        //写入数据到唯一的设备激活表
        $chk = "select id from u_device_unique WHERE appid={$this->data['appid']} AND mac='{$this->data['mac']}' LIMIT 1";
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
     * 角色创建-优先入库
     */
    public function CreateRole()
    {
        /*//1、收到客户端发送的创建角色消息（createrole），检查u_players是否已经存在accountid和channel 一样的数据，如果还没有，则往u_players写入数据
        $sql = "SELECT id FROM u_players WHERE accountid={$this->data['accountid']} AND channel={$this->data['channel']} LIMIT 1";
        $query = $this->db->query($sql);
        if ( ! $query->row()->id ) {
            $this->save('u_players', TRUE);
        }
        $sql = "SELECT id FROM u_roles WHERE accountid={$this->data['accountid']} AND userid={$this->data['userid']} LIMIT 1";
        $query = $this->db->query($sql);
        if ( $query && $query->row()->id ) {
            return false;
        }
        $this->save($this->config->item(__FUNCTION__));*/
    }

    /**
     * Bug反馈
     */
    public function BugReport()
    {
       // $this->save($this->config->item(__FUNCTION__));
    }
    /**
     * 登录
     */
    public function Login()
    {
       /* if (isset($this->data['ip']) && $this->data['ip']) {
            $ip = $this->data['ip'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->data['ip']  =  ip2long($ip);
        $this->data['logindate']  =  date('Ymd', $this->data['created_at']);
        $this->save($this->config->item(__FUNCTION__));
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
        );
        $sql = "INSERT INTO u_login_".date('Ymd')."(`serverid`, `channel`, `appid`, `accountid`, `username`,userid,viplev,lev,client_type,ip,created_at,mac,trainer_lev,client_version)
VALUES ({$lastdata['serverid']},{$lastdata['channel']},{$lastdata['appid']},{$lastdata['accountid']},'{$lastdata['username']}',{$lastdata['userid']},{$lastdata['viplev']},{$lastdata['lev']}
,'{$lastdata['client_type']}',{$lastdata['last_login_ip']},{$lastdata['last_login_time']},'{$lastdata['last_login_mac']}','{$this->data['trainer_lev']}','{$this->data['client_version']}')
ON DUPLICATE KEY UPDATE `channel`=VALUES(channel),`viplev`=VALUES(viplev),`lev`=VALUES(lev),`client_type`=VALUES(client_type),`ip`=VALUES(ip),
`created_at`=VALUES(created_at),`mac`=VALUES(mac),`username`=VALUES(username),`trainer_lev`=VALUES(trainer_lev),`client_version`=VALUES(client_version),`userid`=VALUES(userid)
";
        $this->db->query($sql);
        $sql = <<<SQL
INSERT INTO u_last_login(`serverid`, `channel`, `appid`, `accountid`, `username`,userid,viplev,lev,client_type,last_login_ip,last_login_time,last_login_mac)
VALUES ({$lastdata['serverid']},{$lastdata['channel']},{$lastdata['appid']},{$lastdata['accountid']},"{$lastdata['username']}",{$lastdata['userid']},{$lastdata['viplev']},{$lastdata['lev']}
,"{$lastdata['client_type']}",{$lastdata['last_login_ip']},{$lastdata['last_login_time']},"{$lastdata['last_login_mac']}") 
ON DUPLICATE KEY UPDATE `channel`=VALUES(channel),`viplev`=VALUES(viplev),`lev`=VALUES(lev),`client_type`=VALUES(client_type),`last_login_ip`=VALUES(last_login_ip),
`last_login_time`=VALUES(last_login_time),`last_login_mac`=VALUES(last_login_mac),`username`=VALUES(username)
SQL;
        $this->db->query($sql);*/
    }
    public function OnlineTest()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        print_r($_POST);
        $this->load->database('sdk');
        $this->data = $_POST;
        $this->save('online');
        print_r($this->db->error());
    }
    public function GameProcess()
    {
    }
    public function index()
    {
    }
    
    /**
     * 新支付表
     *
     * @author 王涛 20170214
     */
    public function Paylog()
    {
    }
    /**
     * 客户端bug记录
     *
     * @author 王涛 20170206
     */
    public function clientBug()
    {
    }
    /**
     * 神兽来袭相关
     * 
     * @author 王涛 20170123
     */
    public function worldData()
    {
    }

    /**
     * 2016-11-08
     * 游戏服务端发送端数据
     */
    public function GameServerData()
    {
    }

    public function AccessToken()
    {
    }

    public function RegisterProcess()
    {
    }

    public function GetRegisterProcess()
    {
    }


    /**
     * 副本
     */
    public function CopyProgress()
    {
    }

    /**
     * 每日在线
     */
    public function DayOnline()
    {
    }

    /**
     * 养成&强化
     */
    public function Develop()
    {
    }

    /**
     * 玩家获得元宝记录
     */
    public function GiveEmoney()
    {
    }

    /**
     * 关卡进度
     */
    public function LevelProcess()
    {
    }


    /**
     * 首次登录记录
     */
    public function FirstLogin()
    {
    }
    
    /**
     * 社团争霸报名
     */
    public function Community()
    {
    }
    /**
     * 掉线统计
     */
    public function Drops()
    {
    }

    public function Player()
    {
    }

    public function PlayerBasicInfo()
    {
    }

    /**
     * 更新玩家信息
     */
    public function UpdatePlayer()
    {
    }

    /**
     * 道具
     */
    public function Props()
    {
    }


    /**
     * 玩家在消费记录
     */
    public function Rmb()
    {
    }

    /**
     * 日常行为
     * daily actions
     */
    public function DailyActions()
    {
    }

    public function PaylogProcess()
    {
    }
    /**
     * 成就进度
     */
    public function SuccessProcess()
    {
    }
    /**
     * 成就进度
     */
    public function UpgradeProcess()
    {
    }

    private function update($collection, Array $where, Array $data)
    {
    }



    /**
     * 数据校验
     *
     * @param $table
     * @param $data
     */
    private function verify_data($table, $data)
    {
    }
    private function save_multi($table, $save_data=null, $response = true)
    {
    }
    private function save($table, $response=true)
    {
    	$this->db->reconnect();
        $ret = $this->db->insert($table, $this->data);
        if ($ret===TRUE && $response===TRUE) {
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);
        }
        else {
            print_r($this->db->error());
            parent::BetterLog('api/error',$table . "数据写入失败,数据:".$this->json
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
        set_status_header(200);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        return true;
    }

    private function _log_request()
    {
        $is_inserted = 0;
        return $is_inserted;
    }
    
    /**
     * 精灵塔通关阵容
     *
     * @author 王涛 20170321
     */
    public function Gametower()
    {
    }
    /**
     * 神兽来袭
     *
     * @author 王涛 20170320
     */
    public function Gamedeath()
    {
    }
    /**
     * 战斗匹配时长
     * 
     * @author 王涛 20170428
     */
    public function match()
    {
    }
    /**
     * 社团副本
     *
     * @author 王涛 20170509
     */
    public function Communityprocess()
    {
    }
    /**
     * 椰蛋树活动
     *
     * @author 王涛 20170509
     */
    public function Gameegg()
    {
    }
    /**
     * 冠军之夜排名
     *
     * @author 王涛 20170512
     */
    public function Gamerank()
    {
    }
    
    /*
     * 跨服战的配置   zzl 20170710
     */
    public function crossserver(){    	 
    	 
    	 
    }
    
    /**
     * 报警
     */
    public function warninfo()
    {
    	
    }
    
    /**
     * 亲密度珍肴养成统计   zzl 20170801
     */
    public function intimacy()
    {
    	 
    }
    
    /*
     * 福利活动各档次活动点击   zzl  20170808
     */
   public function  activityClick()
   {
 	
    }
    /*
     * 邀请好友统计需求  zzl 20170901
     */
    public  function  InviteFriend(){
        
        
    }
}