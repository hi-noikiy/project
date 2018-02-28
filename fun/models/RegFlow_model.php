<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/16
 * Time: 21:03
 */
ini_set('display_errors', 'On');
define('LOG_QUERY_SQL', 'RegFlow');
class RegFlow_model extends CI_Model
{
    private $db_sdk;
    private $appid;
    private $bt;
    private $et;
    private $sday;
    private $serverid;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function initData($timestamp, $channelQueryId=false, $byHour=false)
    {
        //get channel list
        $initData     = [];
        if ($byHour===true) {
            for ($i=0; $i<24; $i++) {
                $i = str_pad($i, 2, 0, STR_PAD_LEFT);
                $initData[$i]['active']    = 0;
                $initData[$i]['device']    = 0;
                $initData[$i]['reg']  = 0;
                $initData[$i]['role']      = 0;
                $initData[$i]['reg_rate']  = 0;
                $initData[$i]['role_rate'] = 0;
                $initData[$i]['trans_rate']= 0;
            }
            return $initData;
        }
        $channel_list = include  APPPATH .'/config/channel_list.php';
        foreach ($channel_list as $channel_id=>$channel_name) {
            if (is_array($channelQueryId) && !in_array($channel_id, $channelQueryId)) continue;
            $initData[$channel_id]['active']    = 0;
            $initData[$channel_id]['device']    = 0;
            $initData[$channel_id]['register']  = 0;
            $initData[$channel_id]['role']      = 0;
            if ($byHour===false) continue;

        }
        return $initData;
    }

    public function RegFlow($appid, $startTime, $endTime, $channelId=0, $byHour=false)
    {
        //
        $db_sdk = $this->load->database('sdk', true);
        $query_str = "select count(*) as cnt,channel";
        $query_ext = '';//",FROM_UNIXTIME(created_at, '%H') as hour";

        $map = " where appid=? and created_at between ? and ?";
        $group = " group by channel,serverid";
        $group_detail = '';//",hour";
        if ($byHour==true) {
            $query_ext = ",FROM_UNIXTIME(created_at, '%H') as hour";
            $group_detail = ",hour";

        }
        $bind= [$appid, $startTime, $endTime];
        $sql_reg_his = $query_str . " from u_register where appid=$appid";
        if (is_numeric($channelId) && $channelId>0) {
            $map .= " AND channel=$channelId";
            $sql_reg_his .=  " AND channel=$channelId";
            //$bind[] = $channelId;
        }
        elseif (is_array($channelId) && count($channelId)>0) {
            $map .= " AND channel IN (".implode(',', $channelId).")";
            $sql_reg_his .= " AND channel IN (".implode(',', $channelId).")";
            //$bind[] = $channelId;
            //".implode(',', $channel)."
        }
        //设备激活数量
        $sql_device = $query_str . $query_ext . ' from u_device_active';
        $device = $db_sdk->query($sql_device . $map . $group.$group_detail, $bind);

        //注册数
        $sql_reg = $query_str . $query_ext . ' from u_register ';
        $register= $db_sdk->query($sql_reg . $map . $group.$group_detail, $bind);

        //角色数量
        $sql_role = $query_str . $query_ext . ' from u_roles ';
        $roles    = $db_sdk->query($sql_role . $map . $group.$group_detail, $bind);
        $db_sdk->reconnect();
        //总注册数
        $register_his= $db_sdk->query($sql_reg_his . $group);

        //创建了角色的设备数量
        $sql_role_mac = 'SELECT DISTINCT mac from u_roles ';
        $db_sdk->reconnect();
        $query_role_mac     = $db_sdk->query($sql_role_mac . $map, $bind);
        $role_macs = $query_role_mac->result();
        //print_r($role_macs);
        //print_r($device_role->result());
        //exit;
        $mac_list = [];
        foreach ($role_macs as $item) {
            $mac_list[] = $item->mac;
        }
        $map_role = $map . ' AND mac IN ?';
        $bind_role = $bind;
        $bind_role[] = $mac_list;
        $db_sdk->reconnect();
        $device_role = $db_sdk->query($sql_device . $map_role . $group . $group_detail, $bind_role);
        if (!$device_role) {
            print_r($db_sdk->error());
        }
        //注册了账号的设备数量
        $sql_reg_mac = 'SELECT DISTINCT mac from u_register ';
        $db_sdk->reconnect();
        $query_role_mac     = $db_sdk->query($sql_reg_mac . $map, $bind);
        if (!$query_role_mac) {
            print_r($db_sdk->error());
        }
        $role_macs = $query_role_mac->result();
        $mac_list = [];
        foreach ($role_macs as $item) {
            $mac_list[] = $item->mac;
        }
        $map_role = $map . ' AND mac IN ?';
        $bind_role = $bind;
        $bind_role[] = $mac_list;
        $device_reg = $db_sdk->query($sql_device . $map_role . $group . $group_detail, $bind_role);

        $sql_ac = "SELECT COUNT(DISTINCT accountid) as cnt,channel $query_ext FROM u_dayonline";
        $sql_ac .= $map; //  WHERE appid='$appid' AND created_at BETWEEN $startTime AND $endTime
        //if (is_numeric($channelId) && $channelId>0) $sql_ac .= " AND channel=$channelId";
        //elseif (is_array($channelId) && count($channelId)>0) $sql_ac .= " AND channel IN(".implode(',', $channelId).")";
        $sql_ac .= " GROUP BY serverid,channel $group_detail HAVING count(accountid)>2";
        //在线15分钟才算是活跃玩家，数据库中一条记录是指在线300秒（5分钟）
        $db_sdk->reconnect();
        $active = $db_sdk->query($sql_ac, $bind);
        //print_r($active->result_array());
        return [
            'device'        => $device ? $device->result_array() : [],
            'register'      => $register ? $register->result_array() : [],
            'role'          => $roles ? $roles->result_array() : [],
            'active'        => $active ? $active->result_array() : [],
            'register_his'  => $register_his? $register_his->result_array() : [],
            'device_role'   => $device_role ? $device_role->result_array() : [],
            'device_reg'    => $device_reg ? $device_reg->result_array() : [],
        ];
    }
}
