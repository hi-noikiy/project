<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/27
 * Time: 14:50
 */

defined('BASEPATH') OR exit('No direct script access allowed');
define('LOG_PATH', '/data/log/site/access');
class ApiBackUp Extends CI_Controller
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
    ];
    /**
     * access_token 这个参数改为： appid_MD5（每个app分配一个key + 提交参数data ）,下划线分隔
     *
     * @return bool
     */
    private function access_verify($verify_code, $data)
    {
        //$data         = $_POST['data'];
        if (!$verify_code) exit;
        if (strpos($verify_code, '_')===false) exit;
        list($appid, $token) = explode('_', $verify_code);
        if ($token != md5($this->auth_conf[$appid] . $data)) return false;
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
        $request_method = $this->router->fetch_method();
        if (in_array($request_method, $this->white_list)) {
            return true;
        }
        $access_token = $this->input->post('access_token');
        $_data = str_replace(["\n", " "], ['','+'],$_POST['data']);

        parent::log("ip:{$_SERVER['REMOTE_ADDR']};request_method:$request_method;access_token:$access_token;data:" . base64_decode($_data),
            LOG_PATH . '/' .date('YmdH') . ".log");
        if ( $this->input->server('REQUEST_METHOD') != 'POST' ) {
            set_status_header(401);
            echo 'Invalid Request!!!';
            exit;
        }
        $this->load->database('sdk');
        //替换空格为+,去掉\n
        if (strpos($access_token,'_')===false) {
            $ret = $this->token_verify($access_token);
        }
        else {
            $ret = $this->access_verify($access_token, $_data);
        }

        $log_data = [
            'method'    => $request_method,
            'reqtime'   => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'reqtoken'  => $access_token,
            'reqdata'   => $_data,
            'reqappid'  => isset($ret['appid']) ? $ret['appid'] : '',
        ];
        $this->db->insert('access_log', $log_data);
        if (!isset($ret['appid'])) {
            set_status_header(402);
            echo 'Error Access Token';
            exit;
        }

        if ($ret['errcode']!==0) {
            echo json_encode($ret);
            exit;
        }
        $this->json = base64_decode($_data);
        $this->data = json_decode($this->json, true);
        if (!$this->data) {
            $this->set_response( ['errcode'=>4009,'errmsg'=>"数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象"]);
            exit;
        }
        if (isset($this->data[0]) && is_array($this->data[0])) {
            foreach ($this->data as $k=>$dt) {
                $this->data_multi[$k]      = $dt;
                $this->data_multi[$k]['appid']      = $ret['appid'];
                $this->data_multi[$k]['created_at'] = $_SERVER['REQUEST_TIME'];
                if ($request_method=='DayOnline') {
                    $this->data_multi[$k]['online_date'] = date('Ymd',  $_SERVER['REQUEST_TIME']);
                }
                elseif ($request_method=='Login') {
                    $this->data_multi[$k]['logindate']  = date('Ymd',  $_SERVER['REQUEST_TIME']);
                }
            }
        }
        else {
            $this->data['appid']        = $ret['appid'];
            $this->data['created_at']   = $_SERVER['REQUEST_TIME'];
        }
        $this->config->load('api');
    }

    public function index()
    {
        if (!empty($this->data_multi)) {
            $dataSave = array();
            foreach ($this->data_multi as $data) {
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
            $type = str_pad($this->data['typeid'], 3, '0', STR_PAD_LEFT);
            //print_r($this->data_multi);
            $table_name = "type_{$type}_{$this->data['appid']}";
            //print_r($this->data);
            //print_r($table_name);
            $this->save($table_name);
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
            $sql_chk = "SELECT id FROM u_register_process WHERE appid={$this->data['appid']} AND mac='{$this->data['mac']}' LIMIT 1";
            $query = $this->db->query($sql_chk);
            if ( $query->result() ) {
                $this->save('u_register_process_history');
                return true;
            }
        }
        //第二次启动之后的数据都往历史表里面记录
        $sql_chk = "SELECT id FROM u_register_process_history WHERE appid={$this->data['appid']} AND mac='{$this->data['mac']}' and type_id=0 LIMIT 1";
        $query = $this->db->query($sql_chk);
        if ( $query->result() ) {
            $this->save('u_register_process_history');
            return true;
        }
        $this->save('u_register_process');
    }

    public function GetRegisterProcess()
    {
        $appid = $this->input->get('appid');
        $conf  = include  APPPATH .'/config/event_click_config.php';
        $this->set_response($conf[$appid]);
    }

    /**
     * 安装解压 && 设备激活
     */
    public function DeviceActive()
    {
        //$this->save($this->config->item(__FUNCTION__));
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
        }
    }

    public function BugReport()
    {
        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 设备激活
     */
    public function CreateRole()
    {
        //1、收到客户端发送的创建角色消息（createrole），检查u_players是否已经存在accountid和channel 一样的数据，如果还没有，则往u_players写入数据
        $sql = "SELECT id FROM u_players WHERE accountid={$this->data['accountid']} AND channel={$this->data['channel']} LIMIT 1";
        $query = $this->db->query($sql);
        if ( ! $query->row()->id ) {
            $this->save('u_players', TRUE);
        }
        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 副本
     */
    public function CopyProgress()
    {
        /*
            {
              "userid": "1001043",
              "accountid":"1123232",
              "serverid": "9242",
              "channel": "60016",
              "type": "1",
              "title": "副本名称",
              "lev": "80",
              "is_success":1
            }
        */
        //print_r($this->data);
        //echo 'multi:';
        //print_r($this->data_multi);
        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 每日在线
     */
    public function DayOnline()
    {
        $this->data['online_date'] = date('Ymd');
        $this->save($this->config->item(__FUNCTION__));
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
     * 登录
     */
    public function Login()
    {
        /*
        {
            "mac":"02a58196be8d0e3380b",
            "accountid":"5037092",
            "serverid":"1",
            "channel":"61012",
            "viplev":11,
            "lev":128,
            "clienttype":"KDSG_IOS4_I4",
            "ip":"42.81.64.98"
        }
        */
        if (isset($this->data['ip'])) {
            $ip = $this->data['ip'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->data['ip']  =  ip2long($ip);

        $this->data['logindate']  =  (int)date('Ymd', $_SERVER['REQUEST_TIME']);
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
        print_r($this->db->error());
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
        /*
        {
        "props_type":1,
        "props_id":12,
        "amounts":120,
        "gain_way":2,
        "action":0,
        "userid":1111,
        "accountid":1123232,
        "serverid":1232323,
        "channel":123232
        }
        */

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
                exit;
            }
            $action = $this->data['action'];
            unset($this->data['action']);
            $this->save($this->config->item(__FUNCTION__)[$action]);
        }
    }
    public function Register()
    {
        if (isset($this->data['ip']) && !$this->data['ip']) {
            $ip = $this->data['ip'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->data['ip']  =  ip2long($ip);
        $this->save($this->config->item(__FUNCTION__));
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
     * 数据校验
     *
     * @param $table
     * @param $data
     */
    private function verify_data($table, $data)
    {
        if (empty($table)) {
            $this->set_response( ['errcode'=>4011,'errmsg'=>"table empty"]);
            exit;
        }
        if (isset($data['accountid']) && !$data['accountid']) {
            $this->set_response( ['errcode'=>4010,'errmsg'=>"账号ID怎么能为空?"]);
            exit;
        }

        //TODO::获取表的字段，此处可做缓存
        $sql = "DESC $table";
        $fields = $this->db->query($sql)->result_array();
        $field_list = [];
        foreach ($fields as $field) {
            $field_list[$field['Field']] = $field['Type'];
        }
        $fields = array_keys($field_list);
        foreach ($data as $key=>$val) {
            if  (!in_array($key, $fields)) {
                $this->set_response( ['errcode'=>4009,'errmsg'=>"[{$key}]字段非法"]);
                exit;
            }
            //if (strpos($field_list[$key], 'int') !==false && !is_numeric($val)) {
            //    $this->set_response( ['errcode'=>4008,'errmsg'=>"[{$key}]字段格式错误{$val}"]);
            //    exit;
            //}
        }
    }
    private function save_multi($table, $save_data=null, $response = true)
    {
        $save_data = is_null($save_data) ? $this->data_multi : $save_data;
        foreach ($save_data as $data) {
            //print_r($data);
            $this->verify_data($table, $data);
        }
        $ret = $this->db->insert_batch($table, $save_data);
        if ($ret>0) {
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);
        }
        else {
            $ret = $this->errs[self::ERR6];
            log_message('error', $table
                . "数据写入失败,数据:".$this->json
                .",msg:".json_encode($this->db->error()));
        }
        if ($response===true) {
            $this->set_response($ret);
        }
        return true;
    }
    private function save($table, $response=true)
    {
        if (!empty($this->data_multi)) {
            return $this->save_multi($table);
        }
        if ($table=='online') {

        }
        else{
            $this->verify_data($table, $this->data);
        }
        $ret = $this->db->insert($table, $this->data);
        if ($ret===TRUE && $response===TRUE) {
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);
        }
        else {
            log_message('error', $table
                . "数据写入失败,数据:".$this->json
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

    protected static function get_mac()
    {
        return implode(':',str_split(str_pad(
                base_convert(mt_rand(0,0xffffff),10,16).
                base_convert(mt_rand(0,0xffffff),10,16),12),2)
        );
    }
}