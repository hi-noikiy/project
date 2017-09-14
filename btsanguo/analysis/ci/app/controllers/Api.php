<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/27
 * Time: 14:50
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Api Extends CI_Controller
{
    protected $json;//接收到的数据
    protected $data;//插入的数据

    const ERR6 = 4006;
    const ERR7 = 4007;
    protected $errs = [
        4006=>['errcode'=>4006,'errmsg'=>'create fail'],
        4007=>['errcode'=>4007,'errmsg'=>'update fail'],
    ];


    /**
     * Api constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $request_method = $this->router->fetch_method();
//        $this->load->database('sdk');
        $this->load->model('access_token_model');
        if ($request_method!='AccessToken' ) {
            $this->load->database('sdk');
            if ( $this->input->server('REQUEST_METHOD') != 'POST')
            {
                set_status_header(401);
                echo 'Invalid Request!!!';
                exit;
            }
//            $this->load->library('mongo_db');
            $ret = $this->access_token_model->check_access_token($this->input->post('access_token'));

            $log_data = [
                'method'    => $request_method,
                'reqtime'   => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'reqtoken'  => $this->input->post('access_token'),
                'reqdata'   => $this->input->post('data'),
                'reqappid'  => isset($ret['appid']) ? $ret['appid'] : '',
            ];
            $this->db->insert('access_log', $log_data);

            if ($ret['errcode']!==0)
            {
                echo json_encode($ret);
                exit;
            }
            $this->json = base64_decode($this->input->post('data'));
//            print_r($this->json);
            $this->data = json_decode($this->json, true);
//            var_dump($this->data);exit;
            $this->data['appid'] = $ret['appid'];
            $this->data['created_at'] = $_SERVER['REQUEST_TIME'];
//            print_r($this->data);
            //如果是数字类型
//            array_walk($this->data, function(&$item, $key){
////                if (ctype_digit($item)) $item = (int) $item;
//            });
//            $this->mongo_db->switch_db('test');
            $this->config->load('api');
        }
    }

    public function index()
    {

    }
    public function AccessToken()
    {
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

    /**
     * 设备激活
     */
    public function DeviceActive()
    {
        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 设备激活
     */
    public function CreateRole()
    {
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
        $this->save($this->config->item(__FUNCTION__));
    }

    /**
     * 每日在线
     */
    public function DayOnline()
    {
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


    public function Online()
    {
        $this->save($this->config->item(__FUNCTION__));
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
        $action = $this->data['action'];
        unset($this->data['action']);
        $this->save($this->config->item(__FUNCTION__)[$action]);
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

    private function save($table)
    {
        //获取表的字段，此处可做缓存
        $sql = "DESC $table";
        $fields = $this->db->query($sql)->result_array();
        $field_list = [];
        foreach ($fields as $field) {
            $field_list[$field['Field']] = $field['Type'];
        }
        $fields = array_keys($field_list);
        foreach ($this->data as $key=>$val) {
            if  (!in_array($key, $fields)) {
                $this->set_response( ['errcode'=>4009,'errmsg'=>"[{$key}]字段非法"]);
                exit;
            }
            if (strpos($field_list[$key], 'int') !==false && !is_numeric($val)) {
                $this->set_response( ['errcode'=>4008,'errmsg'=>"[{$key}]字段格式错误{$val}"]);
                exit;
            }
        }
        $ret = $this->db->insert($table, $this->data);
        if ($ret===TRUE) {
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
        echo json_encode($data);
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