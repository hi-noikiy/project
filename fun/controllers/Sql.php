<?php

/**
 * Created by PhpStorm.
 * User: chenguangpeng
 * Date: 11/15-015
 * Time: 12:33
 */
class Sql extends CI_Controller
{
    public $db_sdk;
    public function __construct()
    {
        parent::__construct();
        $this->db_sdk = $this->load->database('sdk', true);

    }
    public  function run()
    {
        $handle = fopen(APPPATH . "controllers/gp.sql", "r");
        var_dump($handle);
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                try {
                    $res = $this->db_sdk->simple_query($buffer);
                    var_dump($res);
                    if (!$res) {
                        print_r($this->db_sdk->error());
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
//                echo $buffer;
            }
            fclose($handle);
        }
    }
    public function Online()
    {
        $handle = fopen(APPPATH . "controllers/gp.sql", "r");
        //this->db_sdk
    }
    public function channel_update()
    {
        $file = '/data/log/site/api/DayOnline/2016/11/17_now.log';
        $date = '20161117';
        $handle = fopen($file, "r");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                try {
                    $str = substr($buffer, 21);
                    parse_str($str, $data);
                    $arr = json_decode(base64_decode($data['data_raw']), true);
                    $sql = "update u_dayonline set online={$arr['online']},viplev={$arr['viplev']},lev={$arr['lev']},total_rmb={$arr['total_rmb']} where accountid={$arr['accountid']} and serverid={$arr['serverid']} and online_date=$date LIMIT 1";
                    //echo $sql,"\n";
                    $res = $this->db_sdk->simple_query($sql);
                    var_dump($res);
                    //if (!$res) {
                    //    print_r($this->db_sdk->error());
                    //}
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
//                echo $buffer;
            }
            fclose($handle);
        }
//        $query->
    }
}