<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/27
 * Time: 14:50
 */

defined('BASEPATH') OR exit('No direct script access allowed');
define('LOG_PATH', '/data/log/site/queue_debug');
include APPPATH . 'libraries/HttpSQSClient.php';
include APPPATH . 'config/item_level.php';

class RunQueueDebug Extends CI_Controller
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

    const SERVER_PLAYER_ACTIVE      = 1;
    const SERVER_PLAYING_METHOD     = 2;
    const SERVER_COMMON_CURRENCY    = 3;
    const SERVER_ELF_STARLEV        = 4;
    const SERVER_LEVEL_DIFFICULTY   = 5;
    const SERVER_PHOTO_LEVEL        = 6;
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
        if (strpos($verify_code, '_')===false)  return ['appid'=>0, 'errcode'=>0];
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
    public function RunRP()
    {
        echo APPPATH . "logs/rg1.log\n";
        $handle = fopen(APPPATH . "logs/rg1.log", "r");
        $i = 0;
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $date = substr($buffer, 0, 19);
                $date = str_replace('=', ':',$date);
                if ($i>2) exit('bbbb');
                parse_str($buffer, $arr);
                $data_pos = strpos($arr['data_raw'], ',');
                $str = substr($arr['data_raw'], 0, $data_pos);
                $data =  json_decode(base64_decode($str), true);
                $data['created_at'] = strtotime($date);
                $data['appid'] = 10002;
                $this->data = $data;
                $this->RegisterProcess();
                $i += 1;
            }
            echo "TOTAL:$i\n";
            fclose($handle);
        }
    }
    public function RegisterProcess()
    {
        //client_time
        //if ($this->data['type_id']==0) {
        //    $sql_chk = "SELECT id FROM u_register_process WHERE appid={$this->data['appid']} AND mac='{$this->data['mac']}' LIMIT 1";
        //    $query = $this->db->query($sql_chk);
        //    if ( $query->result() ) {
        //        $this->save('u_register_process_history', true, true);
        //        return true;
        //    }
        //}
        ////第二次启动之后的数据都往历史表里面记录
        //$sql_chk = "SELECT id FROM u_register_process_history WHERE appid={$this->data['appid']} AND mac='{$this->data['mac']}' and type_id=0 LIMIT 1";
        //$query = $this->db->query($sql_chk);
        //if ( $query->result() ) {
        //    $this->save('u_register_process_history');
        //    return true;
        //}
        $this->save('u_register_process', true, true);
    }
    /**
     * Api constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database('sdk');
    }

    public function run()
    {
        $i = 0;

        do {
            $i += 1;
            $this->data = $this->data_multi = '';
            $this->db->reconnect();
            $queue_data = 'access_token=10002_e588330a1e9860af678a7660027222cf&request_method=GameServerData&request_data=ewoJInR5cGVfaWQiOgk1LAoJImFwcGlkIjoJIjEwMDAyIiwKCSJzZXJ2ZXJpZCI6CTkwOCwKCSJjaGFubmVsIjoJNjMwMDEsCgkiYWNjb3VudGlkIjoJMTMwLAoJInVzZXJpZCI6CTEwMTUxMTMsCgkibGV2IjoJMiwKCSJ2aXBsZXYiOgkwLAoJImNsaWVudF90aW1lIjoJMTQ3OTEwNjc0MCwKCSJjcmVhdGVfdGltZSI6CTE0NzkxMDY0NzUsCgkibGV2ZWxfaWQiOgkzMDExMDExLAoJImNvcHlfdHlwZSI6CTEsCgkiaXNfZmlyc3RfYmF0dGxlIjoJMSwKCSJ3aW5mbGFnIjoJMSwKCSJnZXRzdGFyIjoJMywKCSJmaWdodGluZyI6CTEwMDgKfQ==';
            parse_str($queue_data, $raw_data);
            print_r($raw_data);
            $access_token = $raw_data['access_token'];
            $request_method = $raw_data['request_method'];
            $timestamp = isset($raw_data['timestamp']) && $raw_data['timestamp']>0 ? $raw_data['timestamp'] : time();
            $_data = $raw_data['request_data'];
            $_data = str_replace(["\n", " "], ['','+'],$_data);
            //替换空格为+,去掉\n


            $this->json = base64_decode($_data);
            $this->data = json_decode($this->json, true);
            print_r($this->data);
            if (!$this->data) {
                parent::log("数据格式错误,请检查数据编码是否为utf8/是否为正确的JSON对象", LOG_PATH . '/queue_error.log');
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
            usleep(10000);
        } while($i>5);

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
        echo $sql,"\n";
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
                if (isset($this->data['nomal_copy'])) {
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
                if ($is_first_battle==2) {
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
                    print_r($sets);
                }

                $other_fields = ['max_star','max_star_times','total_times','total_fighting','total_lev'];
                break;
        }
        $check_unique = $this->unique_check($table_name, $map, $other_fields);
        var_dump($check_unique);
        //新增
        if ($check_unique==0) {
            if ($type_id == self::SERVER_PLAYING_METHOD) $this->data['playing_times'] = 1;
            elseif ($type_id == self::SERVER_ELF_STARLEV && isset($this->data['nomal_copy'])) {
                $this->data['nomal_copy']  = $GLOBALS['nomal_copy'][$this->data['nomal_copy']];
                $this->data['nomal_elite'] = $GLOBALS['nomal_elite'][$this->data['nomal_elite']];
            }
            $this->data['log_date'] = $log_date;
            $ret = $this->db->insert($table_name, $this->data);
            print_r($this->db->error());
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
                    print_r($check_unique);
                    print_r($sets);
                    //根据提交过来的星级，跟旧的对比，比旧的记录大就更新
                    if ($check_unique->max_star< $getstar)  $sets['max_star'] = $getstar;
                    //max_star等于3之前，要累加这个字段的次数
                    if ($check_unique->max_star < 3) $sets['max_star_times'] = 1;
                    $sets['avg_fighting'] = "=". ceil(($check_unique->total_fighting + $this->data['fighting']) / ($check_unique->total_times+1));
                    $sets['avg_lev']      = "=". ceil(($check_unique->total_lev + $this->data['lev']) / ($check_unique->total_times+1));
                }
                elseif ($type_id == self::SERVER_ELF_STARLEV) {
                    print_r($this->data);
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
        $row   = $query->row();
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
        if ($replace===false) {
            $ret = $this->db->insert($table, $this->data);
        } else {
            $ret = $this->db->replace($table, $this->data);
            var_dump($ret);
            print_r($this->db->error());
        }
        if ($ret===TRUE && $response===TRUE) {
            echo "OK\n";
            $ret = ['errcode'=>0,'errmsg'=>'success'];
            $this->set_response($ret);
        }
        else {
            parent::log($table . "数据写入失败,数据:".$this->json
                .",msg:".json_encode($this->db->error()), LOG_PATH . '/queue_error_debug.log');
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


}