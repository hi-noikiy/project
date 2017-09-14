<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 15/12/23
 * Time: 21:02
 */
class access_token_model extends CI_Model
{
    const SALT = 'U591*!';
    const COLLECTION = 'auth_config';
    const MAX_REQUEST = 20000;
    private $db1;
    public function __construct()
    {
        parent::__construct();
        $this->db1 = $this->load->database('default', TRUE);
//        $this->load->library('mongo_db');
//        $this->mongo_db->switch_db('authdb');

        //TODO::redis 缓存
//        $redis = new Redis();
//        $redis->connect('127.0.0.1',6379);
//        $redis->auth('password');
    }

    public function __destruct()
    {
        // TODO: close database
        $this->db1->close();
    }

    public function get_access_token($appid, $secret)
    {
        $sql = "SELECT id,access_token,expire,total_request_today,max_request FROM auth_config WHERE appid=? AND secret=? LIMIT 1";
        $ret = $this->db1->query($sql,array($appid, $secret))->result_array();

        if (!$ret) {
            return ['errcode'=>4001,'errmsg'=>'invalid appid'];
        }
        $ret = array_shift($ret);
		//print_r($ret);
        if ($ret['expire']>time()) {
            return [
                'access_token'  => $ret['access_token'],
                'expire_in'     => $ret['expire'] - time(),
                'time'          => $_SERVER['REQUEST_TIME'],
            ];
        }


        //if ($ret[0]['total_request_today'] > $ret[0]['max_request']) {
        //    return ['errcode'=>4002,'errmsg'=>'api freq out of limit'];
        //}
        $access_token = self::generate_token();
        $sql_update = "UPDATE auth_config SET access_token=?,expire=?,total_request_today=total_request_today+1 WHERE id=? LIMIT 1";
        $res = $this->db1->query($sql_update, array(
                $access_token,
                time()+7200,
                (int)$ret['id'],
                )
        );
        //TODO::redis 缓存
//        $this->db->where(array('appid'=>$appid))
//            ->set([
//                'token'=>$access_token,
//                'expire_time'=>$_SERVER['REQUEST_TIME']+7200,
//            ])
//            ->inc(['total_request_today' => 1])
//            ->update(self::COLLECTION);
        return [
            'access_token'  => $access_token,
            'expire_in'     => 7200,
            'time'          => $_SERVER['REQUEST_TIME'],
        ];
    }

    public function check_access_token($access_token)
    {
//        echo 'acc='.$access_token;
        if (empty($access_token)) {
            return ['errcode'=>4003, 'errmsg'=>'access_token check fail'];
        }
        //TODO::redis 缓存
//        $redis = new Redis();
//        $redis->connect('127.0.0.1',6379);
//        $redis->auth('password');
        //$this->redis->get($access_token);

        $sql    = "SELECT appid,expire FROM auth_config WHERE access_token=? LIMIT 1";
        $query  = $this->db1->query($sql, [$access_token]);
        $ret    = $query->row_array();

//        $ret = $this->mongo_db->select(['expire_time','appid'])
//            ->where(['token'=>$access_token])
//            ->get(self::COLLECTION);
//        var_dump($ret);
        if (!$ret) return ['errcode'=>4003, 'errmsg'=>'access_token check fail'];
        if ($ret['expire']<time()) return ['errcode'=>4004, 'errmsg'=>'access_token expire'];
        return ['errcode'=>0, 'errmsg'=>'success','appid'=>$ret['appid']];
    }

    public function create_auth($game_id)
    {
        $data = [
            'game_id'=>$game_id,
        ];
        $data['appid']          = self::generate_appid();
        $data['secret']         = self::generate_random(18);
        $data['access_token']   = self::generate_token();
        $data['expire']         = $_SERVER['REQUEST_TIME'] + 7200;
        $data['max_request']    = self::MAX_REQUEST;
        $data['total_request_today'] = 0;
        $data['created_at']     = $_SERVER['REQUEST_TIME'];
        return $this->db1->insert('auth_config', $data);
//        return $this->mongo_db->insert(self::COLLECTION, $data);
    }

    public function generate_appid()
    {
        $prefix = 'u591';
        return $prefix . uniqid($prefix);
    }

    public function get_info( $game_id )
    {
        return $this->mongo_db->select(
            ['appid','secret','max_request','total_request_today','time'],
            ['_id']
            )
            ->where(['game_id'=>(int)$game_id])
            ->limit(1)
            ->get(self::COLLECTION);
    }

    private function generate_token()
    {
        $token = self::SALT . self::generate_random() . time();
        return md5($token);
    }

    private function generate_random($len=18)
    {
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $output = '';
        for ($i=0; $i<$len; $i++) {
            $random = mt_rand(0, 35);
            $output .= $str{$random};
        }
        return $output;
    }
}
